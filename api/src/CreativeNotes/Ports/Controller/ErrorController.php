<?php

namespace CreativeNotes\Ports\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;

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
     * Bad Request action
     *
     * @return JsonResponse
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function defaultAction(): JsonResponse
    {
        $view = $this->renderPartial('error/default.html.twig', [
            'message' => $this->getResponse()->getContent()
        ]);

        return $this->json([$view], $this->getResponse()->getStatusCode());
    }
}