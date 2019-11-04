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
    protected $description = 'Start simulation of a toy robot moving on a square tabletop. Valid input commands are PLACE X,Y,F; MOVE; LEFT, RIGHT, REPORT.';

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

            if (!$this->isRobotPlacedOnTable()) {
                $this->info('To start the simulator place the robot on the table using PLACE X,Y,FACING');
                $this->info('Example: PLACE 2,3,EAST');
                continue;
            }

            if (!$this->engageCommand($input)) {
                $this->warn('Command ignored.');
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
        return preg_match('/^(PLACE [0-5],[0-5],(EAST|WEST|NORTH|SOUTH)|MOVE|LEFT|RIGHT|REPORT)$/', $input) === 1;
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

            case $input === 'MOVE':
                // TODO: implement move command including validation to stay on the table
                break;

            case $input === 'LEFT':
            case $input === 'RIGHT':
                $this->direction = $this->turnRobot($input);
                $success = true;
                break;

            case $input === 'REPORT':
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
