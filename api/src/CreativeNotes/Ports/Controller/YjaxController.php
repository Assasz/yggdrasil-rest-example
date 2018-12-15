<?php

namespace CreativeNotes\Ports\Controller;

use CreativeNotes\Infrastructure\Driver\RouterDriver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;

/**
 * Class YjaxController
 *
 * Yjax plugin controller
 *
 * @package CreativeNotes\Ports\Controller
 *
 * @property RouterDriver $router
 */
class YjaxController extends ApiController
{
    /**
     * Routes action
     * GET: /yjax/routes
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function routesAction()
    {
        if (!$this->isYjaxRequest()) {
            return $this->badRequest();
        }

        if (!$this->getRequest()->isMethod('GET')) {
            return $this->methodNotAllowed();
        }

        $this->enableCors();

        return $this->json($this->router->getQueryMap());
    }
}