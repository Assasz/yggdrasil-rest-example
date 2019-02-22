<?php

namespace CreativeNotes\Infrastructure\Driver;

use Doctrine\Common\Cache\RedisCache;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\DriverInterface;
use Yggdrasil\Core\Exception\MissingConfigurationException;
use Yggdrasil\Utils\Seeds\SeederInterface;

/**
 * Class EntityManagerDriver
 *
 * [Doctrine ORM] Entity Manager driver
 *
 * @package CreativeNotes\Infrastructure\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class EntityManagerDriver implements DriverInterface, EntityManagerInterface, SeederInterface
{
    /**
     * Instance of driver
     *
     * @var DriverInterface
     */
    private static $driverInstance;

    /**
     * Instance of entity manager
     *
     * @var EntityManager
     */
    private static $managerInstance;

    /**
     * Prevents object creation and cloning
     */
    private function __construct() {}

    private function __clone() {}

    /**
     * Installs entity manager driver
     *
     * @param ConfigurationInterface $appConfiguration Configuration needed to configure entity manager
     * @return DriverInterface
     *
     * @throws MissingConfigurationException if db_name, db_host, db_user, db_password or entity_namespace are not configured
     * @throws DBALException
     * @throws ORMException
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            $requiredConfig = ['db_name', 'db_user', 'db_password', 'db_host', 'entity_namespace'];

            if (!$appConfiguration->isConfigured($requiredConfig, 'entity_manager')) {
                throw new MissingConfigurationException($requiredConfig, 'entity_manager');
            }

            $configuration = $appConfiguration->getConfiguration();

            $connectionParams = [
                'dbname'   => $configuration['entity_manager']['db_name'],
                'user'     => $configuration['entity_manager']['db_user'],
                'password' => $configuration['entity_manager']['db_password'],
                'host'     => $configuration['entity_manager']['db_host'],
                'port'     => $configuration['entity_manager']['db_port']    ?? 3306,
                'driver'   => $configuration['entity_manager']['db_driver']  ?? 'pdo_mysql',
                'charset'  => $configuration['entity_manager']['db_charset'] ?? 'UTF8'
            ];

            $fullResourcePath = dirname(__DIR__, 4) . '/src/' . $configuration['entity_manager']['resource_path'];

            $driver = new SimplifiedYamlDriver([
                $fullResourcePath => $configuration['entity_manager']['entity_namespace']
            ]);

            $config = Setup::createConfiguration();
            $config->setMetadataDriverImpl($driver);
            $config->addEntityNamespace('Entity', $configuration['entity_manager']['entity_namespace']);

            if (!DEBUG && $appConfiguration->hasDriver('cache')) {
                $cache = $appConfiguration->loadDriver('cache')->getComponentInstance();

                $cacheDriver = new RedisCache();
                $cacheDriver->setRedis($cache);

                $config->setQueryCacheImpl($cacheDriver);
                $config->setResultCacheImpl($cacheDriver);
                $config->setMetadataCacheImpl($cacheDriver);
            }

            $connection = DriverManager::getConnection($connectionParams, $config);
            $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

            self::$managerInstance = EntityManager::create($connection, $config);
            self::$driverInstance = new EntityManagerDriver();
        }

        return self::$driverInstance;
    }

    /**
     * Returns entity manager instance
     *
     * @return EntityManager
     */
    public function getComponentInstance(): EntityManager
    {
        return self::$managerInstance;
    }

    /**
     * Return given repository
     *
     * @param string $name Name of repository
     * @return EntityRepository
     */
    public function getRepository(string $name): EntityRepository
    {
        return self::$managerInstance->getRepository($name);
    }

    /**
     * Persists given entity object
     *
     * @param object $entity Entity object to persist
     *
     * @throws ORMException
     */
    public function persist(object $entity): void
    {
        self::$managerInstance->persist($entity);
    }

    /**
     * Removes given entity object
     *
     * @param object $entity Entity object to remove
     *
     * @throws ORMException
     */
    public function remove(object $entity): void
    {
        self::$managerInstance->remove($entity);
    }

    /**
     * Flushes all changes to entity objects
     *
     * @param object? $entity Entity object to flush, if provided
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function flush(object $entity = null): void
    {
        self::$managerInstance->flush($entity);
    }

    /**
     * Truncates given table
     *
     * @param string $table Name of entity table
     * @return bool
     *
     * @throws ConnectionException
     */
    public function truncate(string $table): bool
    {
        $conn = self::$managerInstance->getConnection();

        $conn->beginTransaction();

        try {
            $conn->query('SET FOREIGN_KEY_CHECKS=0');
            $conn->query('TRUNCATE TABLE ' . $table);
            $conn->query('SET FOREIGN_KEY_CHECKS=1');
            $conn->commit();

            self::$managerInstance->flush();
        } catch (\Throwable $t) {
            $conn->rollBack();

            return false;
        }

        return true;
    }
}
