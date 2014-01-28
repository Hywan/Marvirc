<?php

namespace Marvirc\Action\PrivateMessage {

class Ping implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\bping\b#i';
    }

    public static function getUsage ( ) {

        return 'Ping the bot.';
    }

    public static function compute ( Array $data ) {

        return 'pong!';
    }
}

}
