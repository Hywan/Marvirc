<?php

namespace Marvirc\Bin {

use Hoa\Console\Dispatcher\Kit;
use Hoa\Console\GetOption;

class Welcome extends Kit {

    protected $options = array(
        array('help', GetOption::NO_ARGUMENT, 'h'),
        array('help', GetOption::NO_ARGUMENT, '?')
    );



    public function main ( ) {

        while(false !== $c = $this->getOption($v)) switch($c) {

            case 'h':
            case '?':
                return $this->usage();
              break;

            case '__ambiguous':
                $this->resolveOptionAmbiguity($v);
              break;
        }
    }

    public function usage ( ) {

        echo 'Usage   : welcome <options>', "\n",
             'Options :', "\n",
             $this->makeUsageOptionsList(array(
                 'help' => 'This help.'
             ));

        return;
    }
}

}
