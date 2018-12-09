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
        $note = $this->getEntityManager()->getRepository('Entity:Note')->find($request->getNoteId());

        $response = new GetOneResponse();

        if (!empty($note)) {
            $response
                ->setSuccess(true)
                ->setNote($note);
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
            EntityManagerInterface::class  => $this->getEntityManager(),
            NoteRepositoryInterface::class => $this->getEntityManager()->getRepository('Entity:Note')
        ];
    }
}
