<?php

namespace CreativeNotes\Infrastructure\Configuration\Test;

use CreativeNotes\Infrastructure\Driver\ValidatorDriver;
use Yggdrasil\Core\Configuration\AbstractConfiguration;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use CreativeNotes\Infrastructure\Driver\EntityManagerDriver;

/**
 * Class ApiConfiguration
 *
 * Manages configuration of test environment
 *
 * @package CreativeNotes\Infrastructure\Configuration\Test
 */
class TestConfiguration extends AbstractConfiguration implements ConfigurationInterface
{
    /**
     * Returns application config path
     *
     * @return string
     */
    protected function getConfigPath(): string
    {
        return 'CreativeNotes/Infrastructure/Configuration/Test';
    }

    /**
     * Returns application drivers registry
     *
     * @return array
     */
    protected function getDriversRegistry(): array
    {
        return [
            'validator' => ValidatorDriver::class,
            'entityManager' => EntityManagerDriver::class
        ];
    }
}
