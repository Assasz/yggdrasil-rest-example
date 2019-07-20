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
     * @throws MissingConfigurationException if db_name, db_host, db_user or db_password are not configured
     * @throws DBALException
     * @throws ORMException
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            $requiredConfig = ['db_name', 'db_user', 'db_password', 'db_host', 'resource_path'];

            if (!$appConfiguration->isConfigured($requiredConfig, 'entity_manager')) {
                throw new MissingConfigurationException($requiredConfig, 'entity_manager');
            }

            $connectionParams = [
                'dbname'   => $appConfiguration->get('db_name', 'entity_manager'),
                'user'     => $appConfiguration->get('db_user', 'entity_manager'),
                'password' => $appConfiguration->get('db_password', 'entity_manager'),
                'host'     => $appConfiguration->get('db_host', 'entity_manager'),
                'port'     => $appConfiguration->get('db_port', 'entity_manager') ?? 3306,
                'driver'   => $appConfiguration->get('db_driver', 'entity_manager') ?? 'pdo_mysql',
                'charset'  => $appConfiguration->get('db_charset', 'entity_manager') ?? 'UTF8'
            ];

            $fullResourcePath = dirname(__DIR__, 4) . '/src/' . $appConfiguration->get('resource_path', 'entity_manager');
            $entityNamespace = $appConfiguration->get('root_namespace', 'framework') . 'Domain\Entity';

            $driver = new SimplifiedYamlDriver([
                $fullResourcePath => $entityNamespace
            ]);

            $config = Setup::createConfiguration();
            $config->setMetadataDriverImpl($driver);
            $config->addEntityNamespace('Entity', $entityNamespace);

            if ('prod' === $appConfiguration->get('env', 'framework') && $appConfiguration->hasDriver('cache')) {
                $cache = $appConfiguration->installDriver('cache')->getComponentInstance();

                $cacheDriver = new RedisCache();
                $cacheDriver->setRedis($cache);

                $config->setQueryCacheImpl($cacheDriver);
                $config->setResultCacheImpl($cacheDriver);
                $config->setMetadataCacheImpl($cacheDriver);
            }

            $connection = DriverManager::getConnection($connectionParams, $config);
            $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

            self::$managerInstance = EntityManager::create($connection, $config);
            self::$driverInstance  = new EntityManagerDriver();
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
     * Returns given repository
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
