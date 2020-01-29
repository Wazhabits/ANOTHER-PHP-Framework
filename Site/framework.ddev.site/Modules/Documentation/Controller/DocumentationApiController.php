<?php

namespace Modules\Documentation\Controller;

use Core\Controller;
use Core\Database\Manager;
use Core\Response;
use Framework\Model\Classes;
use Framework\Repository\ClassesRepository;

class DocumentationApiController extends Controller
{
    /**
     * @route /api/documentation
     * @route /api/documentation/{test}
     */
    public function documentationApi() {
        $repository = new ClassesRepository();
        $result = $repository->findAll();
        Response::setHeader(["Content-Type" => "application/json"]);
        Response::send();
        echo json_encode($result);
    }
}