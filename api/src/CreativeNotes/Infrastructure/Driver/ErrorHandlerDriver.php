<?php

namespace CreativeNotes\Infrastructure\Driver;

use Whoops\Run;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\DriverInterface;
use Yggdrasil\Utils\Logger\ErrorLogger;
use Yggdrasil\Core\Exception\MissingConfigurationException;

/**
 * Class ErrorHandlerDriver
 *
 * [Whoops] Error Handler driver
 *
 * @package CreativeNotes\Infrastructure\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class ErrorHandlerDriver implements DriverInterface
{
    /**
     * Instance of driver
     *
     * @var DriverInterface
     */
    private static $driverInstance;

    /**
     * Instance of error handler
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
     * Installs error handler driver
     *
     * @param ConfigurationInterface $appConfiguration Configuration needed to configure error handler
     * @return DriverInterface
     *
     * @throws MissingConfigurationException if handler or log_path is not configured
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            $requiredConfig = ['handler', 'log_path'];

            if (!$appConfiguration->isConfigured($requiredConfig, 'error_handler')) {
                throw new MissingConfigurationException($requiredConfig, 'error_handler');
            }

            $configuration = $appConfiguration->getConfiguration();
            $run = new Run();

            if ('dev' === $configuration['framework']['env']) {
                $handler = 'Whoops\Handler\\' . $configuration['error_handler']['handler'] ?? 'PrettyPageHandler';
                $run->pushHandler(new $handler());
            } else {
                $run->pushHandler(function () use ($appConfiguration) {
                    echo json_encode(['message' => 'Internal server error.']);
                });
            }

            $logger = (new ErrorLogger())
                ->setLogPath(dirname(__DIR__, 4) . $configuration['error_handler']['log_path'] . '/error_logs.txt');

            $run->pushHandler(function ($exception) use ($logger) {
                $logger->log($exception);
            });

            $run->register();

            self::$handlerInstance = $run;
            self::$driverInstance = new ErrorHandlerDriver();
        }

        return self::$driverInstance;
    }
}
