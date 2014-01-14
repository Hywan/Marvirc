<?php

if(file_exists('/usr/local/lib/Hoa/Core/Core.php'))
    require '/usr/local/lib/Hoa/Core/Core.php';
elseif(file_exists(__DIR__ . '/vendor/autoload.php'))
    require __DIR__ . '/vendor/autoload.php';

Hoa\Core::getInstance()->getParameters()->setParameters(array(
    'namespace.prefix.Marvirc' => __DIR__ . DS . 'Source' . DS
));
