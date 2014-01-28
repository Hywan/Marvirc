<?php

namespace Marvirc\Action\PrivateMessage {

class Date implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\b(date|time|today)\b#i';
    }

    public static function getUsage ( ) {

        return 'Get the current datetime from the server.';
    }

    public static function compute ( Array $data ) {

        return date('g\hi, l jS, F Y');
    }
}

}
