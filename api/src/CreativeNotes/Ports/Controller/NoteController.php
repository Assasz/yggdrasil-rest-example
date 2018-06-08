<?php

namespace CreativeNotes\Ports\Controller;

use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}