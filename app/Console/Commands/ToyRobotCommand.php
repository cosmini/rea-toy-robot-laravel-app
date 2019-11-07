<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ToyRobotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:toy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start toy robot simulation';


    /**
     * Table dimension (units) on axis X
     *
     * @var integer
     */
    private $unitsAxisX;

    /**
     * Table dimension (units) on axis Y
     *
     * @var integer
     */
    private $unitsAxisY;

    /**
     * The mapping of cardinal points and their value
     *
     * @var array
     */
    private $cardinalPoints = [
        'EAST' => 0,
        'NORTH' => 1,
        'WEST' => 2,
        'SOUTH' => 3,
    ];

    /**
     * Valid commands to exit the simulator
     *
     * @var array
     */
    private $validExitCommands = [
        'EXIT',
        'QUIT',
    ];

    /**
     * Value of coordinate X
     *
     * @var integer
     */
    private $coordinateX;

    /**
     * Value of coordinate Y
     *
     * @var integer
     */
    private $coordinateY;

    /**
     * Value of direction, any of the cardinal points
     *
     * @var string
     */
    private $direction;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->unitsAxisX = env('TOY_ROBOT_UNITS_AXIS_X', 5);
        $this->unitsAxisY = env('TOY_ROBOT_UNITS_AXIS_Y', 5);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while (!feof(STDIN)) {
            $input = rtrim(trim(fgets(STDIN)));
            if ($this->isExitCommand($input)) {
                $this->info('Closing robot simulator. Bye!');
                break;
            }

            if (!$this->isValidRobotCommand($input)) {
                $this->warn('Invalid command. Allowed options are: PLACE, MOVE, LEFT, RIGHT and REPORT');
                continue;
            }

            if (!$this->engageCommand($input)) {
                continue;
            }
        }
    }

    /**
     * Determine if robot is placed on the table or not
     *
     * @return bool
     */
    public function isRobotPlacedOnTable()
    {
        return isset($this->coordinateX, $this->coordinateY, $this->direction);
    }

    /**
     * Determine if received command is for exit
     *
     * @param string $input
     *
     * @return bool
     */
    public function isExitCommand($input)
    {
        return in_array($input, $this->validExitCommands);
    }


    /**
     * Determine if a command is valid
     *
     * @param string $input
     *
     * @return bool
     */
    public function isValidRobotCommand($input)
    {
        return preg_match("/^(PLACE [0-{$this->unitsAxisX}],[0-{$this->unitsAxisX}],(EAST|WEST|NORTH|SOUTH)|MOVE|LEFT|RIGHT|REPORT)$/", $input) === 1;
    }

    /**
     * Get robot direction after left or right commands
     *
     * @param string $turn
     *
     * @return string
     */
    public function turnRobot(string $turn)
    {
        $turnShift = $turn === 'LEFT' ? 1 : -1;
        $directionValue = $this->cardinalPoints[$this->direction];

        $newDirection = $directionValue + $turnShift;

        if ($newDirection < 0) {
            return array_flip($this->cardinalPoints)[3];
        } else if ($newDirection > 3) {
            return array_flip($this->cardinalPoints)[0];
        }

        return array_flip($this->cardinalPoints)[$newDirection];
    }

    /**
     * Determine if a move will be within the table boundaries and move the robot
     *
     * @return bool
     */
    public function moveRobot()
    {
        $success = false;

        switch ($this->direction) {
            case 'EAST':
                if ($this->coordinateX + 1 < $this->unitsAxisX) {
                    $this->coordinateX += 1;
                    $success = true;
                }
                break;

            case 'NORTH':
                if ($this->coordinateY + 1 < $this->unitsAxisY) {
                    $this->coordinateY += 1;
                    $success = true;
                }
                break;

            case 'WEST':
                if ($this->coordinateX - 1 >= 0) {
                    $this->coordinateX -= 1;
                    $success = true;
                }
                break;

            case 'SOUTH':
                if ($this->coordinateY - 1 >= 0) {
                    $this->coordinateY -= 1;
                    $success = true;
                }
                break;
        }

        return $success;
    }

    /**
     * Take action from command
     *
     * @param string $input
     *
     * @return bool
     */
    public function engageCommand($input)
    {
        $success = false;

        switch ($input) {
            case starts_with($input, 'PLACE'):
                $placement = explode(',', str_replace('PLACE ', '', $input));

                $this->coordinateX = $placement[0];
                $this->coordinateY = $placement[1];
                $this->direction = $placement[2];

                $success = true;
                break;

            case $input === 'MOVE' && $this->isRobotPlacedOnTable():
                $success = $this->moveRobot();
                break;

            case $input === 'LEFT' && $this->isRobotPlacedOnTable():
            case $input === 'RIGHT' && $this->isRobotPlacedOnTable():
                $this->direction = $this->turnRobot($input);
                $success = true;
                break;

            case $input === 'REPORT' && $this->isRobotPlacedOnTable():
                $this->info(
                    implode(',', [
                        $this->coordinateX,
                        $this->coordinateY,
                        $this->direction
                    ])
                );
                $success = true;
                break;
        }

        return $success;
    }
}
