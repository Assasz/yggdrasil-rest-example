<?php

namespace CreativeNotes\Ports\Controller;

use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
use CreativeNotes\Application\Service\NoteModule\Request\DeleteRequest;
use CreativeNotes\Application\Service\NoteModule\Request\EditRequest;
use CreativeNotes\Application\Service\NoteModule\Request\GetOneRequest;
use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Component\DoctrineComponent\EntitySerializer;
use Yggdrasil\Core\Controller\ApiController;

/**
 * Class NoteController
 *
 * @package CreativeNotes\Ports\Controller
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class NoteController extends ApiController
{
    /**
     * Items GET action
     * Routes: /api/note/items, /api/note, /api
     *
     * @return JsonResponse
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function itemsGetAction(): JsonResponse
    {
        $request = new GetRequest();

        $service = $this->getContainer()->get('note.get');
        $response = $service->process($request);

        $view = $this->renderPartial('note/_list.html.twig', [
            'notes' => $response->getNotes()
        ]);

        return $this->json([$view]);
    }

    /**
     * Item GET action
     * Route: /api/note/item/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     */
    public function itemGetAction(int $id)
    {
        $request = (new GetOneRequest())
            ->setNoteId($id);

        $service = $this->getContainer()->get('note.get_one');
        $response = $service->process($request);

        if(!$response->isSuccess()){
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json(EntitySerializer::toArray([$response->getNote()]));
    }

    /**
     * Search POST action
     * Route: /api/note/search
     *
     * @return JsonResponse
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function searchPostAction(): JsonResponse
    {
        $request = (new GetRequest())
            ->setSearchTerm($this->fromBody('searchTerm'));

        $service = $this->getContainer()->get('note.get');
        $response = $service->process($request);

        $view = $this->renderPartial('note/_list.html.twig', [
            'notes' => $response->getNotes()
        ]);

        return $this->json([$view]);
    }

    /**
     * Create POST action
     * Route: /api/note/create
     *
     * @return JsonResponse|Response
     *
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function createPostAction()
    {
        $request = (new CreateRequest())
            ->setTitle($this->fromBody('title'))
            ->setContent($this->fromBody('content'));

        $service = $this->getContainer()->get('note.create');
        $response = $service->process($request);

        if(!$response->isSuccess()){
            return $this->badRequest('Bad request. Provided data is invalid.');
        }

        $view = $this->renderPartial('note/_item.html.twig', [
            'note' => $response->getNote()
        ]);

        return $this->json([$view]);
    }

    /**
     * Edit PUT action
     * Route: /api/note/edit/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function editPutAction(int $id)
    {
        $request = (new EditRequest())
            ->setNoteId($id)
            ->setTitle($this->fromBody('title'))
            ->setContent($this->fromBody('content'));

        $service = $this->getContainer()->get('note.edit');
        $response = $service->process($request);

        if(!$response->isSuccess()){
            return $this->badRequest('Bad request. Requested note doesn\'t exist or provided data is invalid.');
        }

        $view = $this->renderPartial('note/_item.html.twig', [
            'note' => $response->getNote()
        ]);

        return $this->json([$view]);
    }

    /**
     * Item DELETE action
     * Route: /api/note/item/{id}
     *
     * @param int $id
     * @return JsonResponse|Response
     */
    public function itemDeleteAction(int $id)
    {
        $request = (new DeleteRequest())
            ->setNoteId($id);

        $service = $this->getContainer()->get('note.delete');
        $response = $service->process($request);

        if(!$response->isSuccess()){
            return $this->notFound('Not found. Requested note doesn\'t exist.');
        }

        return $this->json();
    }
}