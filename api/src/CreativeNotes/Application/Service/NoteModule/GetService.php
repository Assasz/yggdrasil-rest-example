<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use CreativeNotes\Application\Service\NoteModule\Response\GetResponse;
use Yggdrasil\Utils\Service\AbstractService;
use Yggdrasil\Utils\Annotation\Drivers;
use Yggdrasil\Utils\Annotation\Repository;

/**
 * Class GetService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @Drivers(install={EntityManagerInterface::class:"entityManager"})
 * @Repository(name="Entity:Note", contract=NoteRepositoryInterface::class, repositoryProvider="entityManager")
 *
 * @property EntityManagerInterface $entityManager
 * @property NoteRepositoryInterface $noteRepository
 */
class GetService extends AbstractService
{
    /**
     * Gets notes
     *
     * @param GetRequest $request
     * @return GetResponse
     */
    public function process(GetRequest $request): GetResponse
    {
        if (!empty($request->getSearchTerm())) {
            $notes = $this->noteRepository->search($request->getSearchTerm());
        } else {
            $notes = $this->noteRepository->fetch([], ['createDate' => 'desc']);
        }

        return (new GetResponse())->setNotes($notes);
    }
}
