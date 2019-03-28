<?php

namespace CreativeNotes\Ports\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Core\Controller\ErrorControllerInterface;
use Yggdrasil\Core\Driver\DriverCollection;

/**
 * Class ErrorController
 *
 * Executes HTTP errors actions
 * Can be extended with code 4xx and 5xx actions, feel free to customize as needed
 *
 * @package CreativeNotes\Ports\Controller
 */
class ErrorController extends ApiController implements ErrorControllerInterface
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
     * Default error action
     *
     * @return JsonResponse
     */
    public function defaultAction(): JsonResponse
    {
        return $this->json(['message' => $this->getResponse()->getContent()], $this->getResponse()->getStatusCode());
    }
}
