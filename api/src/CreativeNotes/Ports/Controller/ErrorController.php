<?php

namespace CreativeNotes\Ports\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Core\Controller\ErrorControllerInterface;
use Yggdrasil\Core\Driver\DriverCollection;
use Yggdrasil\Utils\Annotation\CORS;

/**
 * Class ErrorController
 *
 * Executes HTTP errors actions
 * Can be extended with code 4xx and 5xx actions, feel free to customize as needed
 *
 * @package CreativeNotes\Ports\Controller
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @CORS()
 */
class ErrorController extends ApiController implements ErrorControllerInterface
{
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
