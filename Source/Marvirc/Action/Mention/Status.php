<?php

namespace Marvirc\Action\Mention {

class Status implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\b(what(\'s|\s+is)\s+up|how\s+are\s+you\b#i';
    }

    public static function getUsage ( ) {

        return 'Get my current status.';
    }

    public static function compute ( Array $data ) {

        $start = \DateTime::createFromFormat('U', $_SERVER['REQUEST_TIME']);
        $now   = \DateTime::createFromFormat('U', time());
        $diff  = $now->diff($start);

        return 'Fine, thank you. I am waked up since ' .
               $start->format('l, jS F Y') .
               ' (since ' .
               static::p($diff->y, 'year') .
               static::p($diff->m, 'month') .
               static::p($diff->d, 'day') .
               static::p($diff->h, 'hour') .
               static::p($diff->i, 'minute', ' and ') .
               static::p($diff->s, 'second', '')
               . '… approximately). My memory is ' .
               round(memory_get_usage(true) / (1 << 10), 2) . 'Kb.';
    }

    protected static function p ( $c, $word, $after = ', ' ) {

        if(0 === $c)
            return;

        if(1 === $c)
            return $c . ' ' . $word . $after;

        return $c . ' ' . $word . 's' . $after;
    }
}

}
