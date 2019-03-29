<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\DeleteRequest;
use CreativeNotes\Application\Service\NoteModule\Response\DeleteResponse;
use Yggdrasil\Utils\Service\AbstractService;
use Yggdrasil\Utils\Annotation\Drivers;
use Yggdrasil\Utils\Annotation\Repository;

/**
 * Class DeleteService
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
class DeleteService extends AbstractService
{
    /**
     * Deletes note
     *
     * @param DeleteRequest $request
     * @return DeleteResponse
     */
    public function process(DeleteRequest $request): DeleteResponse
    {
        $note = $this->noteRepository->pick($request->getNoteId());

        $response = new DeleteResponse();

        if (empty($note)) {
            return $response;
        }

        $this->entityManager->remove($note);
        $this->entityManager->flush();

        return $response->setSuccess(true);
    }
}
