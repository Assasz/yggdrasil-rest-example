<?php

namespace CreativeNotes\Infrastructure\Config;

use Yggdrasil\Core\Configuration\AbstractConfiguration;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\ContainerDriver;
use Yggdrasil\Core\Driver\EntityManagerDriver;
use CreativeNotes\Infrastructure\Driver\ExceptionHandlerDriver;
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
     * AppConfiguration constructor.
     *
     * Register drivers here
     */
    public function __construct()
    {
        // Config directory of application
        parent::__construct('CreativeNotes/Infrastructure/Config');

        $this->drivers = [
            'exceptionHandler' => ExceptionHandlerDriver::class,
            'router' => RouterDriver::class,
            'entityManager' => EntityManagerDriver::class,
            'templateEngine' => TemplateEngineDriver::class,
            'container' => ContainerDriver::class,
            'validator' => ValidatorDriver::class
        ];
    }
}