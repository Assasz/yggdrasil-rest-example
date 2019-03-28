<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\DriverInterface\ValidatorInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\EditRequest;
use CreativeNotes\Application\Service\NoteModule\Response\EditResponse;
use Yggdrasil\Utils\Service\AbstractService;

/**
 * Class EditService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @property ValidatorInterface $validator
 * @property EntityManagerInterface $entityManager
 * @property NoteRepositoryInterface $noteRepository
 */
class EditService extends AbstractService
{
    /**
     * Edits note
     *
     * @param EditRequest $request
     * @return EditResponse
     */
    public function process(EditRequest $request): EditResponse
    {
        $note = $this->noteRepository->pick($request->getNoteId());

        $response = new EditResponse();

        if (empty($note)) {
            return $response->setFound(false);
        }

        $note
            ->setTitle($request->getTitle())
            ->setContent($request->getContent());

        if (!$this->validator->isValid($note)) {
            return $response;
        }

        $this->entityManager->flush();

        return $response
            ->setSuccess(true)
            ->setNote($note);
    }

    /**
     * Returns contracts between service and external suppliers
     *
     * @return array
     */
    protected function getContracts(): array
    {
        return [
            ValidatorInterface::class      => $this->getDriver('validator'),
            EntityManagerInterface::class  => $this->getDriver('entityManager'),
            NoteRepositoryInterface::class => $this->getRepositoryProvider('entityManager')->getRepository('Entity:Note')
        ];
    }
}
