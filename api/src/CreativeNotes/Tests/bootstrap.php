<?php

$loader = require dirname(__DIR__, 3) . '/vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);
