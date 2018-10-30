<?php

namespace CreativeNotes\Infrastructure\Configuration;

use Yggdrasil\Core\Configuration\AbstractConfiguration;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use CreativeNotes\Infrastructure\Driver\ContainerDriver;
use CreativeNotes\Infrastructure\Driver\EntityManagerDriver;
use CreativeNotes\Infrastructure\Driver\ExceptionHandlerDriver;
use CreativeNotes\Infrastructure\Driver\RouterDriver;
use CreativeNotes\Infrastructure\Driver\TemplateEngineDriver;
use CreativeNotes\Infrastructure\Driver\ValidatorDriver;

/**
 * Class AppConfiguration
 *
 * Manages configuration of application
 *
 * @package CreativeNotes\Infrastructure\Configuration
 */
class AppConfiguration extends AbstractConfiguration implements ConfigurationInterface
{
    /**
     * Returns application config path
     *
     * @return string
     */
    protected function getConfigPath(): string
    {
        return 'CreativeNotes/Infrastructure/Configuration';
    }

    /**
     * Returns application drivers registry
     *
     * @return array
     */
    protected function getDriverRegistry(): array
    {
        return [
            'exceptionHandler' => ExceptionHandlerDriver::class,
            'router' => RouterDriver::class,
            'entityManager' => EntityManagerDriver::class,
            'templateEngine' => TemplateEngineDriver::class,
            'container' => ContainerDriver::class,
            'validator' => ValidatorDriver::class
        ];
    }
}