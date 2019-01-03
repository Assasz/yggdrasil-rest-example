<?php

namespace CreativeNotes\Infrastructure\Driver;

use Yggdrasil\Utils\Templating\FormExtension;
use Yggdrasil\Utils\Templating\RoutingExtension;
use Yggdrasil\Utils\Templating\StandardExtension;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\DriverInterface;
use Yggdrasil\Core\Driver\TemplateEngineDriver as AbstractDriver;
use Yggdrasil\Core\Exception\MissingConfigurationException;

/**
 * Class TemplateEngineDriver
 *
 * [Twig] Template Engine driver
 *
 * @package CreativeNotes\Infrastructure\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class TemplateEngineDriver extends AbstractDriver implements DriverInterface
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
     * Installs template engine driver
     *
     * @param ConfigurationInterface $appConfiguration Configuration needed to configure template engine
     * @return DriverInterface
     *
     * @throws MissingConfigurationException if view_path, form_path or application_name is not configured
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            $requiredConfig = ['view_path', 'form_path', 'application_name'];

            if (!$appConfiguration->isConfigured($requiredConfig, 'template_engine')) {
                throw new MissingConfigurationException($requiredConfig, 'template_engine');
            }

            $configuration = $appConfiguration->getConfiguration();

            $basePath = dirname(__DIR__, 4) . '/src/';
            $viewPath = $basePath . $configuration['template_engine']['view_path'];
            $formPath = $basePath . $configuration['template_engine']['form_path'];

            $loader = new \Twig_Loader_Filesystem($viewPath);
            $twig   = new \Twig_Environment(
                $loader, ['cache' => (!DEBUG) ? dirname(__DIR__, 4) . '/var/twig' : false]
            );

            $twig->addExtension(new StandardExtension($configuration['template_engine']['application_name']));
            $twig->addExtension(new RoutingExtension($appConfiguration->loadDriver('router')));
            $twig->addExtension(new FormExtension($formPath));
            $twig->addExtension(new \Twig_Extensions_Extension_Date());

            self::$engineInstance = $twig;
            self::$driverInstance = new TemplateEngineDriver();
        }

        return self::$driverInstance;
    }

    /**
     * Renders given view
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        return self::$engineInstance->render($view, $params);
    }

    /**
     * Adds global to template engine
     *
     * @param string $name
     * @param mixed $value
     */
    public function addGlobal(string $name, $value): void
    {
        self::$engineInstance->addGlobal($name, $value);
    }
}
