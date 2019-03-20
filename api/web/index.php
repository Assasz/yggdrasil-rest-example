<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use CreativeNotes\Infrastructure\Configuration\AppConfiguration;
use Yggdrasil\Core\Kernel;

$appConfiguration = new AppConfiguration();

if ('prod' === $appConfiguration->getConfiguration()['framework']['env']) {
    ini_set('display_errors', 0);
}

(new Kernel($appConfiguration))
    ->handle(Request::createFromGlobals())
    ->send();
