<?php

namespace Marvirc {

use Hoa\Socket\Client;
use Hoa\Http\Response\Response;
use Hoa\Http\Request;

class Url {

    /**
     * @var string contains error message for code
     */
    protected static $errorMessage;

    /**
     * getErrorMessage
     *
     * @static
     * @access public
     * @return string
     */
    public static function getErrorMessage()
    {
        return static::$errorMessage;
    }

    /**
     * check Make a request to url and define which return code
     * set static error properties to display error during the process
     *
     * @param string $url
     * @static
     * @access public
     * @return string
     */
    public static function checkUrl($url) {
        $url = parse_url($url);

        $client = new Client('tcp://'. $url['host'] . ':80');
        $client->connect();

        if ('https' === $url['scheme']) {
            $client->setEncryption(true, Client::ENCRYPTION_TLS);
        }

        $request = new Request();
        $request->setMethod($request::METHOD_GET);
        $request->setUrl($url['path']);

        $request['Host']       = $url['host'];
        $request['Connection'] = 'close';

        $client->writeAll($request);

        $response = new Response(false);
        $response->parse($client->readAll());

        $client->close();

        switch ($response['status']) {
            case Response::STATUS_OK:
            case Response::STATUS_MOVED_PERMANENTLY:
                break;
            case Response::STATUS_FORBIDDEN:
            case Response::STATUS_NOT_FOUND:
            case Response::STATUS_INTERNAL_SERVER_ERROR:
                static::$errorMessage = $response['status'];
                break;
            default:
                static::$errorMessage = 'Error while getting status code!';
                break;
        }

        return $response['status'];
    }
}

}
