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
     * If robot is placed on the table or not
     *
     * @var bool
     */
    private $isPlaced = false;

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
        //
    }
}
