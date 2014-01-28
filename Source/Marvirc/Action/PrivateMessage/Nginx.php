<?php

namespace Marvirc\Action\PrivateMessage {

use Hoa\Console;

class Nginx implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\bnginx\s+(?<action>status)\b#i';
    }

    public static function getUsage ( ) {

        return 'Get informations about nginx.';
    }

    public static function compute ( Array $data ) {

        return file_get_contents(
            'http://status.hoa-project.net/nginx/status'
        );
    }
}

}
