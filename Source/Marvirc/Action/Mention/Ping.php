<?php

namespace Marvirc\Action\Mention;

use Marvirc\Action;

class Ping implements Action\IAction
{
    public static function getPattern()
    {
        return '#\bping\b#i';
    }

    public static function getUsage()
    {
        return 'Ping the bot.';
    }

    public static function compute(array $data)
    {
        return 'pong!';
    }
}
