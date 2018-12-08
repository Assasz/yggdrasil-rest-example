<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\Exception\BrokenContractException;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use CreativeNotes\Application\Service\NoteModule\Response\GetResponse;
use Yggdrasil\Core\Service\AbstractService;

/**
 * Class GetService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetService extends AbstractService
{
    /**
     * Gets notes
     *
     * @param GetRequest $request
     * @return GetResponse
     */
    public function process(GetRequest $request): GetResponse
    {
        $this->validateContracts();

        if (!empty($request->getSearchTerm())) {
            $notes = $this->getEntityManager()->getRepository('Entity:Note')->search($request->getSearchTerm());
        } else {
            $notes = $this->getEntityManager()->getRepository('Entity:Note')->findBy([], ['createDate' => 'desc']);
        }

        $response = (new GetResponse())
            ->setNotes($notes);

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