<?php

namespace Marvirc\Action\Mention {

class Ping implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#ping#i';
    }

    public static function getUsage ( ) {

        return 'Ping the bot.';
    }

    public static function compute ( Array $data ) {

        return 'pong!';
    }
}

}
