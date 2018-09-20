<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use CreativeNotes\Infrastructure\Configuration\AppConfiguration;
use CreativeNotes\Ports\Command\EntityGenerateCommand;

try {
    $application = new Application('Yggdrasil CLI', 'dev');
    $appConfiguration = new AppConfiguration();

    $consoleModule = (!isset($argv[1])) ?: explode(':', $argv[1])[0];

    switch (true) {
        case in_array($consoleModule, ['dbal', 'orm']):
            $entityManager = $appConfiguration->loadDriver('entityManager');
            $helperSet = ConsoleRunner::createHelperSet($entityManager);

            $application->setHelperSet($helperSet);
            ConsoleRunner::addCommands($application);
            break;
        default:
            // register commands here
            $application->add(new EntityGenerateCommand($appConfiguration));
            break;
    }

    $application->run();
} catch (Throwable $t) {
    echo 'Console error: ' . $t->getMessage();
}
