<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\DriverInterface\ValidatorInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\EditRequest;
use CreativeNotes\Application\Service\NoteModule\Response\EditResponse;
use Yggdrasil\Utils\Service\AbstractService;
use Yggdrasil\Utils\Annotation\Drivers;
use Yggdrasil\Utils\Annotation\Repository;

/**
 * Class EditService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @Drivers(install={EntityManagerInterface::class:"entityManager", ValidatorInterface::class:"validator"})
 * @Repository(name="Entity:Note", contract=NoteRepositoryInterface::class, repositoryProvider="entityManager")
 *
 * @property EntityManagerInterface $entityManager
 * @property ValidatorInterface $validator
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
}
