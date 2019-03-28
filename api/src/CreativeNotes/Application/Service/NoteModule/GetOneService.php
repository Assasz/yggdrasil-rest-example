<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\GetOneRequest;
use CreativeNotes\Application\Service\NoteModule\Response\GetOneResponse;
use Yggdrasil\Utils\Service\AbstractService;

/**
 * Class GetOneService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
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

    /**
     * Returns contracts between service and external suppliers
     *
     * @return array
     */
    protected function getContracts(): array
    {
        return [
            EntityManagerInterface::class  => 'entityManager',
            NoteRepositoryInterface::class => $this->getDriver('entityManager')->getRepository('Entity:Note')
        ];
    }
}
