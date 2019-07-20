<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use CreativeNotes\Infrastructure\Configuration\Api\ApiConfiguration;
use Yggdrasil\Core\Kernel;
use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$apiConfiguration = new ApiConfiguration();

if ('prod' === $apiConfiguration->get('env', 'framework')) {
    ini_set('display_errors', 0);
}

(new Kernel($apiConfiguration))
    ->handle(Request::createFromGlobals())
    ->send();
