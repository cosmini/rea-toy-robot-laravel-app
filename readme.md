## About Toy Robot

It is a command line simulator of a robot moving on a table that has no obstructions.
The robot is free to roam around, but cannot go outside the table boundaries.

Toy robot accepts the following commands:

-   `PLACE`
    -   will place robot on the table
    -   receives location on x axis (horizontal), y axis (vertical) and cardinal direction
    -   robot should be placed on the table to start
    -   same command can be used to replace the position once is on the table
    -   example: `PLACE 2,3,NORTH`
-   `MOVE`
    -   move robot by one unit in the current direction
    -   command is being ignored if next step is outside table boundaries
-   `LEFT`
    -   turns robot 90 degrees to left
-   `RIGHT`
    -   turns robot 90 degrees to right
-   `REPORT`
    -   display current location and direction
    -   example: `2,3,NORTH`
-   `EXIT` - stop the simulator
-   `QUIT` - stop the simulator

Application has been built using [Laravel framework version 5.7](https://laravel.com/docs/5.7) because I'm familiar with it and I wanted to learn more about console commands.

This way I was able to customise the style of the command and replicate the REA brand colors.

## Requirements

-   Linux (Ubuntu)
-   PHP >=7.1 or greater with fileinfo enabled
    -   locate `php.ini` using `php --ini`
    -   find `;extension=php_fileinfo.dll`
    -   remove semi-colon to uncomment the line `extension=php_fileinfo.dll`
-   [composer](https://getcomposer.org/)

## Installation

-   `cd rea-toy-robot`
-   `composer install`

## Configuration

By editing the `.env` file, table size can be customised to increase the fun.

-   `TOY_ROBOT_UNITS_AXIS_X` - represents the maximum horizontal units
-   `TOY_ROBOT_UNITS_AXIS_Y` - represents the maximum vertical units

## Usage

`php artisan start:toy`

## Examples

-   Travelling from one side of the table to another and turning to all directions

```PLACE 0,0,WEST
MOVE
REPORT
0,0,WEST
LEFT // rotated to south
MOVE // ignored
MOVE // ignored
REPORT
0,0,SOUTH
RIGHT // direction east
RIGHT // direction north
REPORT
0,0,NORTH
MOVE
MOVE
MOVE
REPORT
0,3,NORTH // move 3 steps up north
MOVE
MOVE
REPORT
0,5,NORTH
MOVE // ignored
REPORT
0,5,NORTH
RIGHT // face east
MOVE // move 1 step to right
REPORT
1,5,EAST
MOVE
MOVE
MOVE
MOVE
REPORT
5,5,EAST
MOVE // ignored
REPORT
5,5,EAST
RIGHT // turn south
REPORT
5,5,SOUTH
MOVE
MOVE
MOVE
MOVE
MOVE
REPORT
5,0,SOUTH
MOVE // ignored
REPORT
5,0,SOUTH
RIGHT // turn west
REPORT
5,0,WEST
MOVE
MOVE
MOVE
MOVE
REPORT
1,0,WEST
MOVE
REPORT
0,0,WEST
MOVE // ignored
REPORT
0,0,WEST
EXIT
Closing robot simulator. Bye!
```

-   this will fail because of invalid command

```
foo
Invalid command. Allowed options are:
 * PLACE 0-5,0-5,EAST|NORTH|WEST|SOUTH
 * MOVE
 * LEFT
 * RIGHT
 * REPORT
 * EAST
 * NORTH
 * WEST
 * SOUTH
```

## Testing

Testing is done using `phpunit` which is installed by `composer`.

To start the tests use the below command:

`./vendor/phpunit/phpunit/phpunit --testdox`
