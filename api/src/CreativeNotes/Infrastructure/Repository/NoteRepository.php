<?php

namespace CreativeNotes\Infrastructure\Repository;

use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Domain\Entity\Note;
use Doctrine\ORM\EntityRepository;

/**
 * Class NoteRepository
 *
 * @package CreativeNotes\Application\RepositoryInterface
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class NoteRepository extends EntityRepository implements NoteRepositoryInterface
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
    public function pick($id, int $lockMode = null, int $lockVersion = null): Note
    {
        return $this->find($id, $lockMode, $lockVersion);
    }

    /**
     * Returns notes by a set of criteria
     *
     * @param array  $criteria
     * @param array? $orderBy
     * @param int?   $limit
     * @param int?   $offset
     * @return array
     */
    public function fetch(array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Searches notes by specific term
     *
     * @param string $searchTerm
     * @return array
     */
    public function search(string $searchTerm): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.title like :searchTerm or n.content like :searchTerm')
            ->orderBy('n.createDate', 'desc')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
}