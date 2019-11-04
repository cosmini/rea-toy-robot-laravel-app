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

    /**
     * @covers App\Console\Commands\ToyRobotCommand::getIsPlaced
     */
    public function testIsPlacedAttributeGetter()
    {
        $this->assertFalse($this->robotCommand->getIsPlaced());
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::isExitCommand
     */
    public function testIsExitCommandMethod()
    {
        $this->assertTrue($this->robotCommand->isExitCommand('EXIT'));
        $this->assertTrue($this->robotCommand->isExitCommand('QUIT'));
        $this->assertFalse($this->robotCommand->isExitCommand('STOP'));
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::isValidRobotCommand
     */
    public function testItCanValidateRobotCommands()
    {
        $this->assertFalse($this->robotCommand->isValidRobotCommand(''));
    }
}
