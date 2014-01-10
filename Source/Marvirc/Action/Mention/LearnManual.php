<?php

namespace Marvirc\Action\Mention {

class LearnManual implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\blearn\s+(manual\s+)?(?<subject>\w+(?:[/\\\]\w+)?)(?:\s+(?<lang>\w{2}))?#i';
    }

    public static function getUsage ( ) {

        return 'Get link to a chapter of the learn manual.';
    }

    public static function compute ( Array $data ) {

        $pattern = static::getPattern();
        preg_match($pattern, $data['message'], $matches);

        $subject = str_replace('\\', '/', $matches['subject']);

        if(false !== strpos($subject, '/'))
            list(, $libray) = explode('/', $subject);
        else
            $library = $subject;

        return 'http://hoa-project.net/' .
               (isset($matches['lang']) ? ucfirst(strtolower($matches['lang'])) . '/' : '') .
               'Literature/Learn/' . ucfirst(strtolower($library)) . '.html';
    }
}

}
