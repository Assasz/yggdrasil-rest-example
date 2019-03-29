<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\GetOneRequest;
use CreativeNotes\Application\Service\NoteModule\Response\GetOneResponse;
use Yggdrasil\Utils\Service\AbstractService;
use Yggdrasil\Utils\Annotation\Drivers;
use Yggdrasil\Utils\Annotation\Repository;

/**
 * Class GetOneService
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
class GetOneService extends AbstractService
{
    /**
     * Gets one note
     *
     * @param GetOneRequest $request
     * @return GetOneResponse
     */
    public function process(GetOneRequest $request): GetOneResponse
    {
        $note = $this->noteRepository->pick($request->getNoteId());

        $response = new GetOneResponse();

        return (empty($note)) ? $response : $response
            ->setSuccess(true)
            ->setNote($note);
    }
}
