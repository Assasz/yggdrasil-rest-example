<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\Exception\BrokenContractException;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Response\DeleteResponse;
use Yggdrasil\Core\Service\AbstractService;
use Yggdrasil\Core\Service\ServiceInterface;
use Yggdrasil\Core\Service\ServiceRequestInterface;
use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class DeleteService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class DeleteService extends AbstractService implements ServiceInterface
{
    /**
     * Deletes note
     *
     * @param ServiceRequestInterface $request
     * @return ServiceResponseInterface
     */
    public function process(ServiceRequestInterface $request): ServiceResponseInterface
    {
        $this->validateContracts();

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
     * Validates contracts between service and external suppliers
     *
     * @throws BrokenContractException
     */
    private function validateContracts(): void
    {
        if (!$this->getEntityManager() instanceof EntityManagerInterface) {
            throw new BrokenContractException(EntityManagerInterface::class);
        }

        if (!$this->getEntityManager()->getRepository('Entity:Note') instanceof NoteRepositoryInterface) {
            throw new BrokenContractException(NoteRepositoryInterface::class);
        }
    }
}