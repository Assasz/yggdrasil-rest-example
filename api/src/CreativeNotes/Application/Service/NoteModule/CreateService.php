<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\DriverInterface\ValidatorInterface;
use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
use CreativeNotes\Application\Service\NoteModule\Response\CreateResponse;
use CreativeNotes\Domain\Entity\Note;
use Yggdrasil\Utils\Service\AbstractService;

/**
 * Class CreateService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @property EntityManagerInterface $entityManager
 * @property ValidatorInterface $validator
 */
class CreateService extends AbstractService
{
    /**
     * Creates note
     *
     * @param CreateRequest $request
     * @return CreateResponse
     */
    public function process(CreateRequest $request): CreateResponse
    {
        $note = (new Note())
            ->setTitle($request->getTitle())
            ->setContent($request->getContent());

        $response = new CreateResponse();

        if (!$this->validator->isValid($note)) {
            return $response;
        }

        $this->entityManager->persist($note);
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
            EntityManagerInterface::class => $this->getDriver('entityManager'),
            ValidatorInterface::class     => $this->getDriver('validator')
        ];
    }
}
