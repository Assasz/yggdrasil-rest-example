<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use CreativeNotes\Infrastructure\Config\AppConfiguration;

try {
    $application = new Application('Yggdrasil CLI', '0.1');

    $appConfig = new AppConfiguration();
    $entityManager = $appConfig->loadDriver('entityManager');

    $helperSet = ConsoleRunner::createHelperSet($entityManager);
    $application->setHelperSet($helperSet);

    ConsoleRunner::addCommands($application);

    // register commands here

    $application->run();
} catch (Exception $e) {
    echo 'Console error: '.$e->getMessage();
}