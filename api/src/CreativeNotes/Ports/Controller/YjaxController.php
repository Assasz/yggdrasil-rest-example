<?php

namespace CreativeNotes\Ports\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;

/**
 * Class YjaxController
 *
 * Yjax plugin controller
 *
 * @package CreativeNotes\Ports\Controller
 */
class YjaxController extends ApiController
{
    /**
     * Routes GET action
     * Route: /api/yjax/routes
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function routesGetAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->badRequest();
        }

        return $this->json($this->getRouter()->getQueryMap());
    }
}