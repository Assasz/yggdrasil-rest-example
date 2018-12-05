<?php

namespace CreativeNotes\Application\RepositoryInterface;

/**
 * Interface NoteRepositoryInterface
 *
 * @package CreativeNotes\Application\RepositoryInterface
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
interface NoteRepositoryInterface
{
    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param mixed $id          The identifier.
     * @param int?  $lockMode    One of the \Doctrine\DBAL\LockMode::* constants
     *                           or NULL if no specific lock mode should be used
     *                           during the search.
     * @param int?  $lockVersion The lock version.
     * @return object? The entity instance or NULL if the entity can not be found.
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     * Finds entities by a set of criteria
     *
     * @param array  $criteria
     * @param array? $orderBy
     * @param int?   $limit
     * @param int?   $offset
     * @return array The objects
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * Searches notes
     *
     * @param string $searchTerm
     * @return array
     */
    public function search(string $searchTerm): array;
}
