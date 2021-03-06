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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Utils\Annotation\Services;
use Yggdrasil\Utils\Annotation\CORS;

/**
 * Class NotesController
 *
 * @package CreativeNotes\Ports\Controller
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @Services(install={
 *     GetService::class,
 *     GetOneService::class,
 *     CreateService::class,
 *     EditService::class,
 *     DeleteService::class
 * })
 * @CORS()
 *
 * @property GetService $noteGetService
 * @property GetOneService $noteGetOneService
 * @property CreateService $noteCreateService
 * @property EditService $noteEditService
 * @property DeleteService $noteDeleteService
 */
class NotesController extends ApiController
{
    /**
     * All notes action
     * GET: /notes, /
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function allAction()
    {
        $request = new GetRequest();
        $response = $this->noteGetService->process($request);

        return $this->json(['notes' => $response->getNotes()]);
    }

    /**
     * Single note action
     * GET: /notes/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function singleAction(int $id)
    {
        $request = (new GetOneRequest())->setNoteId($id);
        $response = $this->noteGetOneService->process($request);

        if (!$response->isSuccess()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json(['note' => $response->getNote()]);
    }

    /**
     * Search notes action
     * GET: /notes/search/{query}
     *
     * @param string $query
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function searchAction(string $query)
    {
        if (!$this->getRequest()->isMethod('GET')) {
            return $this->methodNotAllowed();
        }

        $request = (new GetRequest())->setSearchTerm($query);
        $response = $this->noteGetService->process($request);

        return $this->json(['notes' => $response->getNotes()]);
    }

    /**
     * Create note action
     * POST: /notes
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

        $response = $this->noteCreateService->process($request);

        if (!$response->isSuccess()) {
            return $this->unprocessableEntity('Unprocessable entity. Provided data is invalid.');
        }

        return $this->json(['note' => $response->getNote()], Response::HTTP_CREATED);
    }

    /**
     * Edit note action
     * PUT: /notes/{id}
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

        $response = $this->noteEditService->process($request);

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
     * DELETE: /notes/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function destroyAction(int $id)
    {
        $request = (new DeleteRequest())->setNoteId($id);
        $response = $this->noteDeleteService->process($request);

        if (!$response->isSuccess()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
