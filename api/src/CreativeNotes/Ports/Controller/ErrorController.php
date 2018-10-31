<?php

namespace CreativeNotes\Ports\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Core\Driver\DriverCollection;

/**
 * Class ErrorController
 *
 * Executes HTTP errors actions
 * Can be extended with code 4xx and 5xx actions, feel free to customize as needed
 *
 * @package CreativeNotes\Ports\Controller
 */
class ErrorController extends ApiController
{
    /**
     * ErrorController constructor.
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
     * Bad Request action
     *
     * @return JsonResponse
     */
    public function code400Action(): JsonResponse
    {
        $view = $this->renderPartial('error/400.html.twig', [
            'message' => $this->getResponse()->getContent()
        ]);

        return $this->json([$view], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Not Found action
     *
     * @return JsonResponse
     */
    public function code404Action(): JsonResponse
    {
        $view = $this->renderPartial('error/404.html.twig', [
            'message' => $this->getResponse()->getContent()
        ]);

        return $this->json([$view], Response::HTTP_NOT_FOUND);
    }

    /**
     * Default error action
     *
     * @return JsonResponse
     */
    public function defaultAction(): JsonResponse
    {
        $view = $this->renderPartial('error/default.html.twig', [
            'message' => $this->getResponse()->getContent()
        ]);

        return $this->json([$view], $this->getResponse()->getStatusCode());
    }
}