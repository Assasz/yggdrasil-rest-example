<?php

namespace CreativeNotes\Infrastructure\Driver;

use CreativeNotes\Application\AppInterface\ValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\DriverInterface;
use Yggdrasil\Core\Exception\MissingConfigurationException;

/**
 * Class ValidatorDriver
 *
 * [Symfony Validator] Validator driver
 *
 * @package CreativeNotes\Infrastructure\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class ValidatorDriver implements DriverInterface, ValidatorInterface
{
    /**
     * Instance of driver
     *
     * @var DriverInterface
     */
    private static $driverInstance;

    /**
     * Instance of validator
     *
     * @var RecursiveValidator
     */
    protected static $validatorInstance;

    /**
     * Prevents object creation and cloning
     */
    private function __construct() {}

    private function __clone() {}

    /**
     * Installs validator driver
     *
     * @param ConfigurationInterface $appConfiguration Configuration needed to configure validator
     * @return DriverInterface
     *
     * @throws MissingConfigurationException if resource_path is not configured
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            if (!$appConfiguration->isConfigured(['resource_path'], 'validator')) {
                throw new MissingConfigurationException(['resource_path'], 'validator');
            }

            $configuration = $appConfiguration->getConfiguration();

            $constraintsPath = dirname(__DIR__, 4) .
                '/src/' . $configuration['validator']['resource_path'] . '/constraints.yaml';

            $validator = Validation::createValidatorBuilder()
                ->addYamlMapping($constraintsPath)
                ->getValidator();

            self::$validatorInstance = $validator;
            self::$driverInstance = new ValidatorDriver();
        }

        return self::$driverInstance;
    }

    /**
     * Checks if given entity object is valid or not
     *
     * @param object $entity Entity object to validate
     * @return bool
     */
    public function isValid(object $entity): bool
    {
        $errors = self::$validatorInstance->validate($entity);

        return count($errors) < 1;
    }
}
