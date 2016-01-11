<?php

namespace Marvirc\Bin;

use Hoa\Console\Cursor;
use Hoa\Console\Dispatcher\Kit;
use Hoa\Console\GetOption;

class Welcome extends Kit
{
    protected $options = [
        ['help', GetOption::NO_ARGUMENT, 'h'],
        ['help', GetOption::NO_ARGUMENT, '?']
    ];



    public function main()
    {
        while (false !== $c = $this->getOption($v)) {
            switch ($c) {

            case 'h':
            case '?':
                return $this->usage();

              break;

            case '__ambiguous':
                $this->resolveOptionAmbiguity($v);

              break;
        }
        }

        echo 'Hello, I am Marvirc, a dead ';
        Cursor::colorize('foreground(yellow)');
        echo 'simple';
        Cursor::colorize('normal');
        echo ', extremely ';
        Cursor::colorize('foreground(yellow)');
        echo 'modular';
        Cursor::colorize('normal');
        echo ' and blazzing ';
        Cursor::colorize('foreground(yellow)');
        echo 'fast IRC bot';
        Cursor::colorize('normal');
        echo ' (yup, that\'s it).', "\n\n",

             'Here is the list of my available commands:', "\n",
             '  * ';
        Cursor::colorize('foreground(green)');
        echo 'join';
        Cursor::colorize('normal');
        echo ': connect Marvirc to a channel.', "\n\n",

             'That\'s all for now.', "\n",
             'Type:', "\n", '    ';
        Cursor::colorize('foreground(yellow)');
        echo $_SERVER['argv'][0], ' ';
        Cursor::colorize('foreground(green)');
        echo 'command';
        Cursor::colorize('foreground(yellow)');
        echo ' --help';
        Cursor::colorize('normal');
        echo ' to get more informations.', "\n";
    }

    public function usage()
    {
        echo 'Usage   : welcome <options>', "\n",
             'Options :', "\n",
             $this->makeUsageOptionsList([
                 'help' => 'This help.'
             ]);

        return;
    }
}
