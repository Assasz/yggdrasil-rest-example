<?php

namespace CreativeNotes\Application\Service\NoteModule;

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
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(ServiceRequestInterface $request): ServiceResponseInterface
    {
        $note = $this->getEntityManager()->getRepository('Entity:Note')->find($request->getNoteId());

        $response = new EditResponse();

        if(!empty($note)){
            $note->setTitle($request->getTitle());
            $note->setContent($request->getContent());

            $errors = $this->getValidator()->validate($note);

            if(count($errors) < 1){
                $this->getEntityManager()->flush();

                $response->setSuccess(true);
                $response->setNote($note);
            }
        }

        return $response;
    }
}