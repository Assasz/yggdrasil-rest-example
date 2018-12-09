<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\RepositoryInterface\NoteRepositoryInterface;
use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use CreativeNotes\Application\Service\NoteModule\Response\GetResponse;
use Yggdrasil\Utils\Service\AbstractService;

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
