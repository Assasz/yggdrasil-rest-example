<?php

namespace CreativeNotes\Ports\Controller;

use CreativeNotes\Application\Service\NoteModule\CreateService;
use CreativeNotes\Application\Service\NoteModule\DeleteService;
use CreativeNotes\Application\Service\NoteModule\EditService;
use CreativeNotes\Application\Service\NoteModule\GetOneService;
use CreativeNotes\Application\Service\NoteModule\GetService;
use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
use CreativeNotes\Application\Service\NoteModule\Request\DeleteRequest;
use CreativeNotes\Application\Service\NoteModule\Request\EditRequest;
use CreativeNotes\Application\Service\NoteModule\Request\GetOneRequest;
use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use CreativeNotes\Infrastructure\Driver\ContainerDriver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Utils\Annotation\Drivers;
use Yggdrasil\Utils\Annotation\CORS;

/**
 * Class NotesController
 *
 * @package CreativeNotes\Ports\Controller
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @Drivers(install={"container"})
 * @CORS()
 *
 * @property ContainerDriver $container
 */
class NotesController extends ApiController
{
    /**
     * All notes action
     * GET: /note, /
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function allAction()
    {
        $request = new GetRequest();
        $response = $this->container->getService(GetService::class)->process($request);

        return $this->json(['notes' => $response->getNotes()]);
    }

    /**
     * Single note action
     * GET: /note/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function singleAction(int $id)
    {
        $request = (new GetOneRequest())->setNoteId($id);
        $response = $this->container->getService(GetOneService::class)->process($request);

        if (!$response->isSuccess()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json(['note' => $response->getNote()]);
    }

    /**
     * Search notes action
     * POST: /note/search
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function searchAction()
    {
        if (!$this->getRequest()->isMethod('POST')) {
            return $this->methodNotAllowed();
        }

        if (!$this->inBody(['searchTerm'])) {
            return $this->badRequest('Bad request. Some of required data is missing in request body.');
        }

        $request = (new GetRequest())->setSearchTerm($this->fromBody('searchTerm'));
        $response = $this->container->getService(GetService::class)->process($request);

        return $this->json(['notes' => $response->getNotes()]);
    }

    /**
     * Create note action
     * POST: /note
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function createAction()
    {
        if (!$this->inBody(['title', 'content'])) {
            return $this->badRequest('Bad request. Some of required data is missing in request body.');
        }

        $request = (new CreateRequest())
            ->setTitle($this->fromBody('title'))
            ->setContent($this->fromBody('content'));

        $response = $this->container->getService(CreateService::class)->process($request);

        if (!$response->isSuccess()) {
            return $this->unprocessableEntity('Unprocessable entity. Provided data is invalid.');
        }

        return $this->json(['note' => $response->getNote()], Response::HTTP_CREATED);
    }

    /**
     * Edit note action
     * PUT: /note/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function editAction(int $id)
    {
        if (!$this->inBody(['title', 'content'])) {
            return $this->badRequest('Bad request. Some of required data is missing in request body.');
        }

        $request = (new EditRequest())
            ->setNoteId($id)
            ->setTitle($this->fromBody('title'))
            ->setContent($this->fromBody('content'));

        $response = $this->container->getService(EditService::class)->process($request);

        if (!$response->isFound()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        if (!$response->isSuccess()) {
            return $this->unprocessableEntity('Unprocessable entity. Provided data is invalid.');
        }

        return $this->json(['note' => $response->getNote()]);
    }

    /**
     * Destroy note action
     * DELETE: /note/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function destroyAction(int $id)
    {
        $request = (new DeleteRequest())->setNoteId($id);
        $response = $this->container->getService(DeleteService::class)->process($request);

        if (!$response->isSuccess()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
