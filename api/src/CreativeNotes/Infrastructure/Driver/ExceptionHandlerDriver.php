<?php

namespace CreativeNotes\Infrastructure\Driver;

use Whoops\Run;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\DriverInterface;
use Yggdrasil\Utils\ExceptionLogger;
use Yggdrasil\Core\Exception\MissingConfigurationException;

/**
 * Class ExceptionHandlerDriver
 *
 * [Whoops] Exception Handler driver
 *
 * @package CreativeNotes\Infrastructure\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class ExceptionHandlerDriver implements DriverInterface
{
    /**
     * Instance of driver
     *
     * @var DriverInterface
     */
    private static $driverInstance;

    /**
     * Instance of exception handler
     *
     * @var Run
     */
    private static $handlerInstance;

    /**
     * Prevents object creation and cloning
     */
    private function __construct() {}

    private function __clone() {}

    /**
     * Installs exception handler driver
     *
     * @param ConfigurationInterface $appConfiguration Configuration needed to configure exception handler
     * @return DriverInterface
     *
     * @throws MissingConfigurationException if handler or log_path is not configured
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            $requiredConfig = ['handler', 'log_path'];

            if (!$appConfiguration->isConfigured($requiredConfig, 'exception_handler')) {
                throw new MissingConfigurationException($requiredConfig, 'exception_handler');
            }

            $configuration = $appConfiguration->getConfiguration();

            $run = new Run();

            if (DEBUG) {
                $handler = 'Whoops\Handler\\' . $configuration['exception_handler']['handler'] ?? 'PrettyPageHandler';
                $run->pushHandler(new $handler());
            } else {
                $run->pushHandler(function () use ($appConfiguration) {
                    $view = $appConfiguration->loadDriver('templateEngine')->render('error/_500.html.twig');

                    echo json_encode($view);
                });
            }

            $logger = (new ExceptionLogger())
                ->setLogPath(dirname(__DIR__, 4) . '/src/' . $configuration['exception_handler']['log_path'] . '/exceptions.txt');

            $run->pushHandler(function ($exception) use ($logger) {
                $logger->log($exception);
            });

            $run->register();

            self::$handlerInstance = $run;
            self::$driverInstance = new ExceptionHandlerDriver();
        }

        return self::$driverInstance;
    }
}
