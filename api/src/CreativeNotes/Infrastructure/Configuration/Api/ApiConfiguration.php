<?php

namespace CreativeNotes\Infrastructure\Configuration\Api;

use Yggdrasil\Core\Configuration\AbstractConfiguration;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use CreativeNotes\Infrastructure\Driver\ContainerDriver;
use CreativeNotes\Infrastructure\Driver\EntityManagerDriver;
use CreativeNotes\Infrastructure\Driver\ErrorHandlerDriver;
use CreativeNotes\Infrastructure\Driver\RouterDriver;
use CreativeNotes\Infrastructure\Driver\ValidatorDriver;

/**
 * Class ApiConfiguration
 *
 * Manages configuration of API
 *
 * @package CreativeNotes\Infrastructure\Configuration\Api
 */
class ApiConfiguration extends AbstractConfiguration implements ConfigurationInterface
{
    /**
     * Returns application config path
     *
     * @return string
     */
    protected function getConfigPath(): string
    {
        return 'CreativeNotes/Infrastructure/Configuration/Api';
    }

    /**
     * Returns application drivers registry
     *
     * @return array
     */
    protected function getDriversRegistry(): array
    {
        return [
            'router' => RouterDriver::class,
            'errorHandler' => ErrorHandlerDriver::class,
            'entityManager' => EntityManagerDriver::class,
            'container' => ContainerDriver::class,
            'validator' => ValidatorDriver::class
        ];
    }
}
