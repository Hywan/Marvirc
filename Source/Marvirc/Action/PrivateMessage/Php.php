<?php

namespace Marvirc\Action\PrivateMessage {

use Hoa\Console;

class Php implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\bphp\s+(?<action>fpm\s+(?:full\s+)?status|version)\b#i';
    }

    public static function getUsage ( ) {

        return 'Get informations about PHP.';
    }

    public static function compute ( Array $data ) {

        $pattern = static::getPattern();
        preg_match($pattern, $data['message'], $matches);
        $action  = strtolower($matches['action']);

        if('version' === $action)
            return PHP_VERSION;

        $option = '';

        if(false !== strpos($action, 'full'))
            $option = '?full';

        return file_get_contents(
            'http://status.hoa-project.net/php/status' . $option
        );
    }
}

}
