<?php

namespace CreativeNotes\Infrastructure\Driver;

use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\ExceptionHandlerDriver as AbstractDriver;

/**
 * Class ExceptionHandlerDriver
 *
 * Overridden core driver
 *
 * @package CreativeNotes\Infrastructure\Resource\Driver
 */
class ExceptionHandlerDriver extends AbstractDriver
{
    /**
     * Returns handler for production mode
     *
     * @param ConfigurationInterface $appConfiguration
     * @return \Closure
     */
    protected static function getProdHandler(ConfigurationInterface $appConfiguration): \Closure
    {
        return function () use ($appConfiguration) {
            $view = $appConfiguration->loadDriver('templateEngine')->render('error/500.html.twig');

            echo json_encode($view);
        };
    }
}