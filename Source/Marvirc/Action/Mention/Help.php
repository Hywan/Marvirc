<?php

namespace Marvirc\Action\Mention {

class Help implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#help#i';
    }

    public static function getUsage ( ) {

        return 'This help.';
    }

    public static function compute ( Array $data ) {

        return 'Youpla lala';
    }
}

}
