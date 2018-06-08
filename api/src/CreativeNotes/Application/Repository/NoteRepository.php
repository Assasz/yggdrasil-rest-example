<?php

namespace CreativeNotes\Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class NoteRepository
 *
 * @package CreativeNotes\Application\Repository
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class NoteRepository extends EntityRepository
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