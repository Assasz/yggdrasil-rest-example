<?php

namespace CreativeNotes\Infrastructure\Repository;

use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
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
     * Searches notes
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