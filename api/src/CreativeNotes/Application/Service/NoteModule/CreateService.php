<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\Service\NoteModule\Response\CreateResponse;
use CreativeNotes\Domain\Entity\Note;
use Yggdrasil\Core\Service\AbstractService;
use Yggdrasil\Core\Service\ServiceInterface;
use Yggdrasil\Core\Service\ServiceRequestInterface;
use Yggdrasil\Core\Service\ServiceResponseInterface;

/**
 * Class CreateService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class CreateService extends AbstractService implements ServiceInterface
{
    /**
     * Creates note
     *
     * @param ServiceRequestInterface $request
     * @return ServiceResponseInterface
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(ServiceRequestInterface $request): ServiceResponseInterface
    {
        $note = (new Note())
            ->setTitle($request->getTitle())
            ->setContent($request->getContent());

        $response = new CreateResponse();

        $errors = $this->getValidator()->validate($note);

        if(count($errors) < 1){
            $this->getEntityManager()->persist($note);
            $this->getEntityManager()->flush();

            $response
                ->setSuccess(true)
                ->setNote($note);
        }

        return $response;
    }
}