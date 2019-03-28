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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Core\Driver\DriverCollection;
use Yggdrasil\Utils\Entity\EntitySerializer;

/**
 * Class NoteController
 *
 * @package CreativeNotes\Ports\Controller
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @property ContainerDriver $container
 */
class NoteController extends ApiController
{
    /**
     * NoteController constructor.
     *
     * @param DriverCollection $drivers
     * @param Request $request
     * @param Response $response
     */
    public function __construct(DriverCollection $drivers, Request $request, Response $response)
    {
        parent::__construct($drivers, $request, $response);

        $this->installDrivers(['container']);
        $this->enableCors();
    }

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
        if (!$this->getRequest()->isMethod('GET')) {
            return $this->methodNotAllowed();
        }

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
        if (!$this->getRequest()->isMethod('GET')) {
            return $this->methodNotAllowed();
        }

        $request = (new GetOneRequest())
            ->setNoteId($id);

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

        $request = (new GetRequest())
            ->setSearchTerm($this->fromBody('searchTerm'));

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
        if (!$this->getRequest()->isMethod('POST')) {
            return $this->methodNotAllowed();
        }

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
        if (!$this->getRequest()->isMethod('PUT')) {
            return $this->methodNotAllowed();
        }

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
        if (!$this->getRequest()->isMethod('DELETE')) {
            return $this->methodNotAllowed();
        }

        $request = (new DeleteRequest())
            ->setNoteId($id);

        $response = $this->container->getService(DeleteService::class)->process($request);

        if (!$response->isSuccess()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
