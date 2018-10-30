<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\Service\NoteModule\Response\GetResponse;
use Yggdrasil\Core\Service\AbstractService;
use Yggdrasil\Core\Service\ServiceInterface;
use Yggdrasil\Core\Service\ServiceRequestInterface;
use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class GetService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetService extends AbstractService implements ServiceInterface
{
    /**
     * Gets notes
     *
     * @param ServiceRequestInterface $request
     * @return ServiceResponseInterface
     */
    public function process(ServiceRequestInterface $request): ServiceResponseInterface
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
}