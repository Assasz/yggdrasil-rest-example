<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\DriverInterface\ValidatorInterface;
use CreativeNotes\Application\Exception\BrokenContractException;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Response\EditResponse;
use Yggdrasil\Core\Service\AbstractService;
use Yggdrasil\Core\Service\ServiceInterface;
use Yggdrasil\Core\Service\ServiceRequestInterface;
use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class EditService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class EditService extends AbstractService implements ServiceInterface
{
    /**
     * Edits note
     *
     * @param ServiceRequestInterface $request
     * @return ServiceResponseInterface
     */
    public function process(ServiceRequestInterface $request): ServiceResponseInterface
    {
        $this->validateContracts();

        $note = $this->getEntityManager()->getRepository('Entity:Note')->find($request->getNoteId());

        $response = new EditResponse();

        if (!empty($note)) {
            $note
                ->setTitle($request->getTitle())
                ->setContent($request->getContent());

            if ($this->getValidator()->isValid($note)) {
                $this->getEntityManager()->flush();

                $response
                    ->setSuccess(true)
                    ->setNote($note);
            }
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
        if (!$this->getValidator() instanceof ValidatorInterface) {
            throw new BrokenContractException(ValidatorInterface::class);
        }

        if (!$this->getEntityManager() instanceof EntityManagerInterface) {
            throw new BrokenContractException(EntityManagerInterface::class);
        }

        if (!$this->getEntityManager()->getRepository('Entity:Note') instanceof NoteRepositoryInterface) {
            throw new BrokenContractException(NoteRepositoryInterface::class);
        }
    }
}