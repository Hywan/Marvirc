<?php

namespace Marvirc\Action\Mention {

use Hoa\Http\Response\Response;

class SourceCode implements \Marvirc\Action\IAction {

    public static function getPattern ( ) {

        return '#\bsource\s+(?<library>[\w\-/\?\.=\\\\]+)(?:\s+(?<parameters>[\w\-/?\.=\#]+))?#i';
    }

    public static function getUsage ( ) {

        return 'Get link to a source code. ' .
               'Use `source` followed by a classname or path.';
    }

    public static function compute ( Array $data ) {

        $extension  = null;
        $parameters = null;

        $pattern = static::getPattern();
        preg_match($pattern, $data['message'], $matches);
        $library = $matches['library'];

        if(false !== strpos($matches['library'], '\\')) {

            $library = str_replace('\\', '/',        $library);
            $library = str_replace('Hoa', 'Library', $library);

            if(2 < count(preg_split('#/#', $library, null, PREG_SPLIT_NO_EMPTY)))
                $extension = '.php';
        }

        $library = trim(str_replace('//', '/', $library), '/');
        $url     = 'http://central.hoa-project.net/Resource/' . ucfirst($library);

        if(isset($matches['parameters']))
            $parameters = ucfirst($matches['parameters']);

        $url  .= $parameters . $extension;
        $code  = \Marvirc\Url::check($url);

        return (   $code === \Hoa\Http\Response::STATUS_OK
                || $code === \Hoa\Http\Response::STATUS_MOVED_PERMANENTLY)
                   ? $url
                   : \Marvirc\Url::getErrorMessage();
    }
}

}
