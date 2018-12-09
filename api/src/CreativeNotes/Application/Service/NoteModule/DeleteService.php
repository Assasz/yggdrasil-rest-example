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
        $note = $this->getEntityManager()->getRepository('Entity:Note')->find($request->getNoteId());

        $response = new DeleteResponse();

        if (!empty($note)) {
            $this->getEntityManager()->remove($note);
            $this->getEntityManager()->flush();

            $response->setSuccess(true);
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
