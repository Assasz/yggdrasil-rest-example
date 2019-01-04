<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\DeleteRequest;
use CreativeNotes\Application\Service\NoteModule\Response\DeleteResponse;
use Yggdrasil\Utils\Service\AbstractService;

/**
 * Class DeleteService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
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

    /**
     * Returns contracts between service and external suppliers
     *
     * @example [EntityManagerInterface::class => $this->getDriver('entityManager')]
     *
     * @return array
     */
    protected function getContracts(): array
    {
        return [
            EntityManagerInterface::class  => $this->getDriver('entityManager'),
            NoteRepositoryInterface::class => $this->getDriver('entityManager')->getRepository('Entity:Note')
        ];
    }
}
