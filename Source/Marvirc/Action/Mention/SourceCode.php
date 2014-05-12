<?php

namespace Marvirc\Action\Mention {

use Marvirc\Url;
use Hoa\Http\Response\Response;

class SourceCode implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\bsource\s+(?<library>[\w\-/\?\.=\\\\]+)(?:\s+(?<parameters>[\w-/?.=\#]+))?#i';
    }

    public static function getUsage ( ) {

        return 'Get link to a source code. Use keywords "source" followed by class or path';
    }

    public static function compute ( Array $data ) {

        $extension  = null;
        $parameters = null;

        $pattern = static::getPattern();
        preg_match($pattern, $data['message'], $matches);

        if (false !== strpos($matches['library'], '\\')) {
            $matches['library'] = str_replace('\\', '/', $matches['library']);
            $matches['library'] = str_replace('Hoa', 'Library', $matches['library']);

            if (2 < count(preg_split('#/#', $matches['library'], null, PREG_SPLIT_NO_EMPTY))) {
                $extension = '.php';
            }
        }

        $matches['library'] = trim(str_replace('//', '/', $matches['library']), '/');

        $url = 'http://central.hoa-project.net/Resource/' . ucfirst($matches['library']);

        if (isset($matches['parameters'])) {
            $parameters = ucfirst($matches['parameters']);
        }

        $url .= $parameters . $extension;

        $code = Url::checkUrl($url);

        return (Response::STATUS_OK === $code
            || Response::STATUS_MOVED_PERMANENTLY === $code) ? $url : Url::getErrorMessage();
    }
}

}
