<?php

namespace CreativeNotes\Infrastructure\Config;

use Yggdrasil\Core\Configuration\AbstractConfiguration;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\ContainerDriver;
use Yggdrasil\Core\Driver\EntityManagerDriver;
use Yggdrasil\Core\Driver\ExceptionHandlerDriver;
use Yggdrasil\Core\Driver\RouterDriver;
use Yggdrasil\Core\Driver\TemplateEngineDriver;
use Yggdrasil\Core\Driver\ValidatorDriver;

/**
 * Class AppConfiguration
 *
 * Manages configuration of application
 *
 * @package CreativeNotes\Infrastructure\Config
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
        return 'CreativeNotes/Infrastructure/Config';
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