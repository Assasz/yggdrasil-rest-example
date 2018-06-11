<?php

namespace CreativeNotes\Application\Service\NoteModule;

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
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(ServiceRequestInterface $request): ServiceResponseInterface
    {
        $note = $this->getEntityManager()->getRepository('Entity:Note')->find($request->getNoteId());

        $response = new DeleteResponse();

        if(!empty($note)){
            $this->getEntityManager()->remove($note);
            $this->getEntityManager()->flush();

            $response->setSuccess(true);
        }

        return $response;
    }
}