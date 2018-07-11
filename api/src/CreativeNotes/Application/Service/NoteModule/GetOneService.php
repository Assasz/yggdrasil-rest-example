<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\Service\NoteModule\Response\GetOneResponse;
use Yggdrasil\Core\Service\AbstractService;
use Yggdrasil\Core\Service\ServiceInterface;
use Yggdrasil\Core\Service\ServiceRequestInterface;
use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class GetOneService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetOneService extends AbstractService implements ServiceInterface
{
    /**
     * Gets one note
     *
     * @param ServiceRequestInterface $request
     * @return ServiceResponseInterface
     */
    public function process(ServiceRequestInterface $request): ServiceResponseInterface
    {
        $note = $this->getEntityManager()->getRepository('Entity:Note')->find($request->getNoteId());

        $response = new GetOneResponse();

        if(!empty($note)){
            $response
                ->setSuccess(true)
                ->setNote($note);
        }

        return $response;
    }
}