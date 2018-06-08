<?php

namespace CreativeNotes\Infrastructure\Driver;

use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\Base\DriverInterface;
use Whoops\Run;
use Whoops\Handler\JsonResponseHandler;

/**
 * Class ExceptionHandlerDriver
 *
 * Exception handler driver, necessary for exception handling to work
 * Whoops is framework default exception handler
 *
 * @package Yggdrasil\Core\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class ExceptionHandlerDriver implements DriverInterface
{
    /**
     * Instance of exception handler
     *
     * @var Run
     */
    private static $handlerInstance;

    /**
     * ExceptionHandlerDriver constructor.
     *
     * Should be private to prevent object creation. Same with __clone
     */
    private function __construct() {}

    private function __clone() {}

    /**
     * Returns instance of exception handler
     *
     * @param ConfigurationInterface $appConfiguration Configuration needed to configure exception handler
     * @return Run
     */
    public static function getInstance(ConfigurationInterface $appConfiguration): Run
    {
        if(self::$handlerInstance === null) {
            $handler = new Run();
            $handler->pushHandler(new JsonResponseHandler());
            $handler->register();

            self::$handlerInstance = $handler;
        }

        return self::$handlerInstance;
    }
}