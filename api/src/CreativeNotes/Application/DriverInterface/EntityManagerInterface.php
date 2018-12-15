<?php

namespace CreativeNotes\Application\DriverInterface;

use Doctrine\ORM\EntityRepository;

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
     * @return EntityRepository
     */
    public function getRepository(string $name): EntityRepository;

    /**
     * Persists given entity object
     *
     * @param object $entity Entity object to persist
     */
    public function persist(object $entity): void;

    /**
     * Removes given entity object
     *
     * @param object $entity Entity object to remove
     */
    public function remove(object $entity): void;

    /**
     * Flushes all changes to entity objects
     *
     * @param object? $entity Entity object to flush, if provided
     */
    public function flush(object $entity = null): void;
}
