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
