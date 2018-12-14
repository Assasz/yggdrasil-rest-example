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

        if (!empty($note)) {
            $note
                ->setTitle($request->getTitle())
                ->setContent($request->getContent());

            if ($this->validator->isValid($note)) {
                $this->entityManager->flush();

                $response
                    ->setSuccess(true)
                    ->setNote($note);
            }
        }

        return $response;
    }

    /**
     * Returns contracts between service and external suppliers
     *
     * @example [EntityManagerInterface::class => $this->getEntityManager()]
     *
     * @return array
     */
    protected function getContracts(): array
    {
        return [
            ValidatorInterface::class      => $this->getValidator(),
            EntityManagerInterface::class  => $this->getEntityManager(),
            NoteRepositoryInterface::class => $this->getEntityManager()->getRepository('Entity:Note')
        ];
    }
}
