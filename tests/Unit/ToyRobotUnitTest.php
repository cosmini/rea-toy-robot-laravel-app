<?php

namespace Tests\Unit;

use App\Console\Commands\ToyRobotCommand;
use Tests\TestCase;

class ToyRobotUnitTest extends TestCase
{
    private $robotCommand;

    public function setUp()
    {
        $this->robotCommand = new ToyRobotCommand();
    }

    public function testItNeedsToBePlacedOnTheTableAfterStart()
    {
        $this->assertTrue($this->robotCommand->isPlaced);
    }
}
