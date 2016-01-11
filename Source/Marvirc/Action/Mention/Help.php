<?php

namespace Marvirc\Action\Mention;

use Hoa\File\Finder;
use Marvirc\Action;

class Help implements Action\IAction
{
    public static function getPattern()
    {
        return '#\bhelp(\s+(?<action>[\w\\\/]+))?\b#i';
    }

    public static function getUsage()
    {
        return 'This help.';
    }

    public static function compute(array $data)
    {
        $pattern = static::getPattern();

        preg_match($pattern, $data['message'], $matches);

        if (!isset($matches['action'])) {
            $finder = new Finder();
            $finder->in(dirname(__DIR__) . DS . 'Message')
                   ->in(dirname(__DIR__) . DS . 'Mention')
                   ->in(dirname(__DIR__) . DS . 'PrivateMessage')
                   ->files()
                   ->name('#\.php$#');

            $out = [];

            foreach ($finder as $entry) {
                $name      = substr($entry->getBasename(), 0, -4);
                $type      = basename(dirname($entry->getPathname()));
                $classname = 'Marvirc\Action\\' . $type . '\\' . $name;
                $out[]     = $type . '/' . $name . "\t\t" . $classname::getUsage();
            }

            return implode("\n", $out);
        }

        $name      = $matches['action'];
        $classname = 'Marvirc\Action\\' . str_replace('/', '\\', $name);

        if (false === class_exists($classname)) {
            return $name . ' does not exist.';
        }

        return $classname::getUsage() . "\n\n" .
               'Pattern:' . "\n" .
               "\t" . $classname::getPattern();
    }
}
