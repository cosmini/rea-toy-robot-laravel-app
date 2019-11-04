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
     * Accepted commands by the robot
     *
     * @var array
     */
    private $validRobotCommands = [
        'PLACE',
        'MOVE',
        'LEFT',
        'RIGHT',
        'REPORT',
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
     * @var integer
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
                die(0);
            }
        }
    }

    /**
     * Determine if robot is placed on the table or not
     *
     * @return bool
     */
    public function getIsPlaced()
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
}
