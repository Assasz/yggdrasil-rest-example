<?php

namespace CreativeNotes\Infrastructure\Driver;

use Symfony\Component\Yaml\Yaml;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\DriverInterface;
use Yggdrasil\Core\Exception\MissingConfigurationException;
use Yggdrasil\Core\Routing\Router;
use Yggdrasil\Core\Routing\RoutingConfiguration;
use Yggdrasil\Core\Driver\RouterDriver as AbstractDriver;

/**
 * Class RouterDriver
 *
 * [Yggdrasil] Router driver, required in driver registry
 *
 * @package CreativeNotes\Infrastructure\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class RouterDriver extends AbstractDriver implements DriverInterface
{
    /**
     * Instance of driver
     *
     * @var DriverInterface
     */
    private static $driverInstance;

    /**
     * Prevents object creation and cloning
     */
    private function __construct() {}

    private function __clone() {}

    /**
     * Installs router driver
     *
     * @param ConfigurationInterface $appConfiguration Configuration needed to configure router
     * @return DriverInterface
     *
     * @throws MissingConfigurationException if default_controller, default_action, base_url or resource_path are not configured
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            $requiredConfig = ['default_controller', 'default_action', 'base_url', 'resource_path'];

            if (!$appConfiguration->isConfigured($requiredConfig, 'router')) {
                throw new MissingConfigurationException($requiredConfig, 'router');
            }

            $passiveActionsPath = dirname(__DIR__, 4) . '/src/' . $appConfiguration->get('resource_path', 'router') . '/passive_actions.yaml';

            if (file_exists($passiveActionsPath)) {
                $passiveActions = Yaml::parseFile($passiveActionsPath);
            }

            $routingConfig = (new RoutingConfiguration())
                ->setBaseUrl($appConfiguration->get('base_url', 'router'))
                ->setControllerNamespace($appConfiguration->get('root_namespace', 'framework') . 'Ports\Controller\\')
                ->setDefaultController($appConfiguration->get('default_controller', 'router'))
                ->setDefaultAction($appConfiguration->get('default_action', 'router'))
                ->setNotFoundMsg($appConfiguration->get('not_found_msg', 'router') ?? 'Not found.')
                ->setPassiveActions($passiveActions ?? []);

            if ($appConfiguration->isConfigured(['rest_routing'], 'router')) {
                $routingConfig->enableRestRouting();
            }

            self::$routerInstance = new Router($routingConfig);
            self::$driverInstance = new RouterDriver();
        }

        return self::$driverInstance;
    }
}
