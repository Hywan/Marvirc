<?php

namespace Marvirc\Action\Mention {

use Hoa\Console;

class Uptime implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\buptime\b#i';
    }

    public static function getUsage ( ) {

        return 'Get the uptime of the server.';
    }

    public static function compute ( Array $data ) {

        return Console\Processus::execute('uptime');
    }
}

}
