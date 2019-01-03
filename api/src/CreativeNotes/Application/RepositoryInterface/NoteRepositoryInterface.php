<?php

namespace CreativeNotes\Application\RepositoryInterface;
use CreativeNotes\Domain\Entity\Note;

/**
 * Interface NoteRepositoryInterface
 *
 * @package CreativeNotes\Application\RepositoryInterface
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
interface NoteRepositoryInterface
{
    /**
     * Returns a note by its primary key / identifier.
     *
     * @param mixed $id          The identifier.
     * @param int?  $lockMode    One of the \Doctrine\DBAL\LockMode::* constants
     *                           or NULL if no specific lock mode should be used
     *                           during the search.
     * @param int?  $lockVersion The lock version.
     * @return Note? The entity instance or NULL if the entity can not be found.
     */
    public function pick($id, int $lockMode = null, int $lockVersion = null): Note;

    /**
     * Returns notes by a set of criteria
     *
     * @param array  $criteria
     * @param array? $orderBy
     * @param int?   $limit
     * @param int?   $offset
     * @return array The objects
     */
    public function fetch(array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null): array;

    /**
     * Searches notes by specific term
     *
     * @param string $searchTerm
     * @return array
     */
    public function search(string $searchTerm): array;
}
