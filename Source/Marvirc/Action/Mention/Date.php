<?php

namespace Marvirc\Action\Mention;

use Marvirc\Action;

class Date implements Action\IAction
{
    public static function getPattern()
    {
        return '#\b(date|time|today)\b#i';
    }

    public static function getUsage()
    {
        return 'Get the current datetime from the server.';
    }

    public static function compute(array $data)
    {
        return date('g\hi, l jS, F Y');
    }
}
