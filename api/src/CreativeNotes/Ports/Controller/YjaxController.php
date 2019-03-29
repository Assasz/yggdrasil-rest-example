<?php

namespace CreativeNotes\Ports\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yggdrasil\Core\Controller\ApiController;
use Yggdrasil\Core\Annotation\CORS;

/**
 * Class YjaxController
 *
 * Yjax plugin controller
 *
 * @package CreativeNotes\Ports\Controller
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 *
 * @CORS()
 */
class YjaxController extends ApiController
{
    /**
     * Action used by Yjax to resolve routes to remote actions
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

        return $this->json($this->getRouter()->getQueryMap());
    }
}
