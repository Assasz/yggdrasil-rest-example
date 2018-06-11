<?php

namespace CreativeNotes\Ports\Controller;

use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
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
     * List GET action
     * Routes: /api/note/list, /api, /api/note
     *
     * @return JsonResponse
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function listGetAction(): JsonResponse
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
        $request = new GetRequest();
        $request->setSearchTerm($this->fromBody('searchTerm'));

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
        $request = new CreateRequest();
        $request->setTitle($this->fromBody('title'));
        $request->setContent($this->fromBody('content'));

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
}