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
     * @covers App\Console\Commands\ToyRobotCommand::isRobotPlacedOnTable
     */
    public function testIsRobotPlacedOnTableMethodReturnsFalseOnInit()
    {
        $this->assertFalse($this->robotCommand->isRobotPlacedOnTable());
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

        $this->assertFalse($this->robotCommand->isValidRobotCommand('PLACE 6,6,FOO'));

        $this->assertTrue($this->robotCommand->isValidRobotCommand('PLACE 0,0,SOUTH'));

        $this->assertTrue($this->robotCommand->isValidRobotCommand('MOVE'));

        $this->assertTrue($this->robotCommand->isValidRobotCommand('LEFT'));

        $this->assertTrue($this->robotCommand->isValidRobotCommand('RIGHT'));

        $this->assertTrue($this->robotCommand->isValidRobotCommand('REPORT'));
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::engageCommand
     */
    public function testItCanPlaceRobotOnTheTable()
    {
        $this->assertTrue($this->robotCommand->engageCommand('PLACE 0,0,EAST'));
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::turnRobot
     */
    public function testItCanTurnLeft()
    {
        $this->robotCommand->engageCommand('PLACE 0,0,EAST');
        $this->assertEquals('NORTH', $this->robotCommand->turnRobot('LEFT'));

        $this->robotCommand->engageCommand('PLACE 0,0,NORTH');
        $this->assertEquals('WEST', $this->robotCommand->turnRobot('LEFT'));

        $this->robotCommand->engageCommand('PLACE 0,0,WEST');
        $this->assertEquals('SOUTH', $this->robotCommand->turnRobot('LEFT'));

        $this->robotCommand->engageCommand('PLACE 0,0,SOUTH');
        $this->assertEquals('EAST', $this->robotCommand->turnRobot('LEFT'));
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::turnRobot
     */
    public function testItCanTurnRight()
    {
        $this->robotCommand->engageCommand('PLACE 0,0,EAST');
        $this->assertEquals('SOUTH', $this->robotCommand->turnRobot('RIGHT'));

        $this->robotCommand->engageCommand('PLACE 0,0,SOUTH');
        $this->assertEquals('WEST', $this->robotCommand->turnRobot('RIGHT'));

        $this->robotCommand->engageCommand('PLACE 0,0,WEST');
        $this->assertEquals('NORTH', $this->robotCommand->turnRobot('RIGHT'));

        $this->robotCommand->engageCommand('PLACE 0,0,NORTH');
        $this->assertEquals('EAST', $this->robotCommand->turnRobot('RIGHT'));
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::engageCommand
     */
    public function testItCanMoveRobot()
    {
        // init place on the table
        $this->robotCommand->engageCommand('PLACE 0,0,EAST');

        // move robot and test output
        $this->assertTrue($this->robotCommand->engageCommand('MOVE'));
    }
}
