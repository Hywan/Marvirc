<?php

namespace Marvirc\Action\Mention {

use Hoa\File\Finder;

class Help implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\bhelp(\s+(?<action>\w+))?\b#i';
    }

    public static function getUsage ( ) {

        return 'This help.';
    }

    public static function compute ( Array $data ) {

        $pattern = static::getPattern();

        preg_match($pattern, $data['message'], $matches);

        if(!isset($matches['action'])) {

            $finder = new Finder();
            $finder->in(__DIR__)
                   ->files()
                   ->name('#\.php$#');

            $out = array();

            foreach($finder as $entry) {

                $name      = substr($entry->getBasename(), 0, -4);
                $classname = __NAMESPACE__ . '\\' . $name;
                $out[]     = $name . "\t\t" . $classname::getUsage();
            }

            return implode("\n", $out);
        }

        $name      = $matches['action'];
        // file_exists.
        $classname = __NAMESPACE__ . '\\' . $name;

        return $classname::getUsage() . "\n" .
               $classname::getPattern();
    }
}

}
