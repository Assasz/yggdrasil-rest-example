<?php

namespace CreativeNotes\Application\DriverInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface EntityManagerInterface
 *
 * @package CreativeNotes\Application\DriverInterface
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
interface EntityManagerInterface
{
    /**
     * Returns given repository
     *
     * @param string $name Name of repository
     * @return ObjectRepository|EntityRepository
     */
    public function getRepository(string $name);

    /**
     * Persists given entity object
     *
     * @param object $entity Entity object to persist
     *
     * @throws ORMException
     */
    public function persist(object $entity): void;

    /**
     * Removes given entity object
     *
     * @param object $entity Entity object to remove
     *
     * @throws ORMException
     */
    public function remove(object $entity): void;

    /**
     * Flushes all changes to entity objects
     *
     * @param object? $entity Entity object to flush, if provided
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function flush(object $entity = null): void;
}