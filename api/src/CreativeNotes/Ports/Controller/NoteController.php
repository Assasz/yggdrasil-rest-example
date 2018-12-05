<?php

namespace CreativeNotes\Ports\Controller;

use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
use CreativeNotes\Application\Service\NoteModule\Request\DeleteRequest;
use CreativeNotes\Application\Service\NoteModule\Request\EditRequest;
use CreativeNotes\Application\Service\NoteModule\Request\GetOneRequest;
use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Entity\EntitySerializer;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Core\Driver\DriverCollection;

/**
 * Class NoteController
 *
 * @package CreativeNotes\Ports\Controller
 * @author Paweł Antosiak <contact@pawelantosiak.com>
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

        $this->enableCors();
    }

  /**
     * All action
     * GET: /note/all, /note, /
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

        $response = $this->getContainer()->getService('note.get')->process($request);

        $view = $this->renderPartial('note/_list.html.twig', [
            'notes' => $response->getNotes()
        ]);

        return $this->json([$view]);
    }

    /**
     * Get action
     * GET: /note/get/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function getAction(int $id)
    {
        if (!$this->getRequest()->isMethod('GET')) {
            return $this->methodNotAllowed();
        }

        $request = (new GetOneRequest())
            ->setNoteId($id);

        $response = $this->getContainer()->getService('note.get_one')->process($request);

        if (!$response->isSuccess()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json(EntitySerializer::toArray([$response->getNote()]));
    }

    /**
     * Search action
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

        $request = (new GetRequest())
            ->setSearchTerm($this->fromBody('searchTerm'));

        $response = $this->getContainer()->getService('note.get')->process($request);

        $view = $this->renderPartial('note/_list.html.twig', [
            'notes' => $response->getNotes()
        ]);

        return $this->json([$view]);
    }

    /**
     * Create action
     * POST: /note/create
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

        $request = (new CreateRequest())
            ->setTitle($this->fromBody('title'))
            ->setContent($this->fromBody('content'));

        $response = $this->getContainer()->getService('note.create')->process($request);

        if (!$response->isSuccess()) {
            return $this->badRequest('Bad request. Provided data is invalid.');
        }

        $view = $this->renderPartial('note/_item.html.twig', [
            'note' => $response->getNote()
        ]);

        return $this->json([$view]);
    }

    /**
     * Edit action
     * PUT: /note/edit/{id}
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

        $request = (new EditRequest())
            ->setNoteId($id)
            ->setTitle($this->fromBody('title'))
            ->setContent($this->fromBody('content'));

        $response = $this->getContainer()->getService('note.edit')->process($request);

        if (!$response->isSuccess()) {
            return $this->badRequest('Bad request. Requested note doesn\'t exist or provided data is invalid.');
        }

        $view = $this->renderPartial('note/_item.html.twig', [
            'note' => $response->getNote()
        ]);

        return $this->json([$view]);
    }

    /**
     * Delete action
     * DELETE: /note/delete/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function deleteAction(int $id)
    {
        if (!$this->getRequest()->isMethod('DELETE')) {
            return $this->methodNotAllowed();
        }

        $request = (new DeleteRequest())
            ->setNoteId($id);

        $response = $this->getContainer()->getService('note.delete')->process($request);

        if (!$response->isSuccess()) {
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json();
    }
}