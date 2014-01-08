<?php

namespace {

require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '/Hoa.link.php';

Hoa\Core::enableErrorHandler();
Hoa\Core::enableExceptionHandler();

try {

    $router = new Hoa\Router\Cli();
    $router->get(
        'g',
        '(?<command>\w+)?(?<_tail>.*?)',
        'Main',
        'Main',
        array('command' => 'welcome')
    );

    $dispatcher = new Hoa\Dispatcher\Basic(array(
        'synchronous.controller'
            => 'Marvirc\Bin\(:%variables.command:lU:)',
        'synchronous.action'
            => 'main'
    ));
    $dispatcher->setKitName('Hoa\Console\Dispatcher\Kit');
    exit($dispatcher->dispatch($router));
}
catch ( \Hoa\Core\Exception $e ) {

    $message = $e->raise(true);
    $code    = 1;
}
catch ( \Exception $e ) {

    $message = $e->getMessage();
    $code    = 2;
}

Hoa\Console\Cursor::colorize('foreground(white) background(red)');
echo $message, "\n";
Hoa\Console\Cursor::colorize('normal');
exit($code);

}
