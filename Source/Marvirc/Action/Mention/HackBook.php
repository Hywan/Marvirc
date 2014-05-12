<?php

namespace Marvirc\Action\Mention {

use Marvirc\Url;
use Hoa\Http\Response\Response;

class HackBook implements \Marvirc\Action\IAction {


    protected static $hostname = 'hoa-project.net';

    public static function getPattern ( ) {

        return '#\bhack\s+(book\s+)?(?<subject>\w+(?:[/\\\]\w+)?)(?:\s+(?<lang>\w{2}))?#i';
    }

    public static function getUsage ( ) {

        return 'Get link to a chapter of the hack book.';
    }

    public static function compute ( Array $data ) {

        $pattern = static::getPattern();
        preg_match($pattern, $data['message'], $matches);

        $subject = str_replace('\\', '/', $matches['subject']);

        if(false !== strpos($subject, '/'))
            list(, $library) = explode('/', $subject);
        else
            $library = $subject;

        $path = (isset($matches['lang']) ? ucfirst(strtolower($matches['lang'])) . '/' : '') .
            'Literature/Hack/' . ucfirst(strtolower($library)) . '.html';

        $url = 'http://' . static::$hostname . '/' . $path;

        $code = Url::checkUrl($url);

        return (Response::STATUS_OK === $code
            || Response::STATUS_MOVED_PERMANENTLY === $code) ? $url : Url::getErrorMessage();
    }
}

}
