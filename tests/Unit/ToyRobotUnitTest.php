<?php

namespace Tests\Unit;

use App\Console\Commands\ToyRobotCommand;
use Tests\TestCase;

class ToyRobotUnitTest extends TestCase
{
    private $robotCommand;

    public function setUp()
    {
        parent::setUp();

        $this->robotCommand = new ToyRobotCommand();
    }

    public function testIsPlacedAttributeGetter()
    {
        $this->assertFalse($this->robotCommand->getIsPlaced());
    }

    public function testIsExitCommandMethod()
    {
        $this->assertTrue($this->robotCommand->isExitCommand('EXIT'));
        $this->assertTrue($this->robotCommand->isExitCommand('QUIT'));
        $this->assertFalse($this->robotCommand->isExitCommand('STOP'));
    }
}
