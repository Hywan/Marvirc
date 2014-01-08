<?php

require '/usr/local/lib/Hoa/Core/Core.php';

Hoa\Core::getInstance()->getParameters()->setParameters(array(
    'namespace.prefix.Marvirc' => __DIR__ . DS . 'Source' . DS
));
