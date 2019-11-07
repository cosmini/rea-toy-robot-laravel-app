<?php

namespace Tests\Unit;

use App\Console\Commands\ToyRobotCommand;
use Tests\TestCase;

class ToyRobotCommandUnitTest extends TestCase
{
    private $robotCommand;

    public function setUp()
    {
        parent::setUp();

        $this->robotCommand = new ToyRobotCommand();
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->robotCommand = null;
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::isRobotPlacedOnTable
     */
    public function testItNeedsToBePlacedOnTableAtStart()
    {
        $this->assertFalse($this->robotCommand->isRobotPlacedOnTable());
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::isExitCommand
     */
    public function testItCanStopSimulatorIfExitCommandReceived()
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

        $this->assertFalse(
            $this->robotCommand->isValidRobotCommand(
                'PLACE ' . implode(',', [
                    env('TOY_ROBOT_UNITS_AXIS_X') + 1,
                    env('TOY_ROBOT_UNITS_AXIS_Y') + 1,
                    'FOO'
                ])
            )
        );

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
     * @covers App\Console\Commands\ToyRobotCommand::engageCommand
     */
    public function testItCannotPlaceRobotOutsideTheTable()
    {
        $this->assertTrue(
            $this->robotCommand->engageCommand(
                'PLACE ' . implode(',', [
                    env('TOY_ROBOT_UNITS_AXIS_X') + 1,
                    env('TOY_ROBOT_UNITS_AXIS_Y') + 1,
                    'EAST'
                ])
            )
        );
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::turnRobot
     * @using App\Console\Commands\ToyRobotCommand::engageCommand
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
     * @using App\Console\Commands\ToyRobotCommand::engageCommand
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
     * @covers App\Console\Commands\ToyRobotCommand::moveRobot
     * @using App\Console\Commands\ToyRobotCommand::engageCommand
     */
    public function testItCanMoveRobotIfInsideTableBoundaries()
    {
        $this->robotCommand->engageCommand('PLACE 0,0,EAST');
        $this->assertTrue($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE 0,0,NORTH');
        $this->assertTrue($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE 0,' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',EAST');
        $this->assertTrue($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE 0,' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',SOUTH');
        $this->assertTrue($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',0,WEST');
        $this->assertTrue($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',0,NORTH');
        $this->assertTrue($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',WEST');
        $this->assertTrue($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',SOUTH');
        $this->assertTrue($this->robotCommand->moveRobot());
    }

    /**
     * @covers App\Console\Commands\ToyRobotCommand::moveRobot
     * @using App\Console\Commands\ToyRobotCommand::moveRobot
     */
    public function testItCannotMoveRobotIfOutsideTableBoundaries()
    {
        $this->robotCommand->engageCommand('PLACE 0,0,WEST');
        $this->assertFalse($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE 0,0,SOUTH');
        $this->assertFalse($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE 0,' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',WEST');
        $this->assertFalse($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE 0,' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',NORTH');
        $this->assertFalse($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',0,EAST');
        $this->assertFalse($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',0,SOUTH');
        $this->assertFalse($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',EAST');
        $this->assertFalse($this->robotCommand->moveRobot());

        $this->robotCommand->engageCommand('PLACE ' . env('TOY_ROBOT_UNITS_AXIS_X') . ',' . env('TOY_ROBOT_UNITS_AXIS_Y') . ',NORTH');
        $this->assertFalse($this->robotCommand->moveRobot());
    }
}
