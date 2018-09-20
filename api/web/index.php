<?php

define('DEBUG', true);

if(!DEBUG) {
    ini_set('display_errors', 0);
}

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use CreativeNotes\Infrastructure\Configuration\AppConfiguration;
use Yggdrasil\Core\Kernel;

(new Kernel(new AppConfiguration()))
    ->handle(Request::createFromGlobals())
    ->send();
