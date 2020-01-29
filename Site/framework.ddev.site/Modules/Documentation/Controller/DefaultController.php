<?php

namespace Modules\Documentation\Controller;

use Core\Controller;
use Core\Logger;
use Core\Response;
use Framework\Repository\ClassesRepository;
use Modules\Documentation\Maker;

class DefaultController extends Controller
{
    /**
     * @var ClassesRepository $repository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ClassesRepository();
    }

    /**
     * Get all documentation (json)
     */
    public function index() {
        $result = $this->repository->findAll();
        Response::setHeader(["Content-Type" => "application/json"]);
        Response::send();
        echo json_encode($result);
    }

    /**
     * Documentation maker
     */
    public function make() {
        Logger::log("documentation", "Builder running", Logger::$DEFAULT_LEVEL);
        $maker = new Maker();
        $maker->extract();
        Response::setHeader(["location" => "/api/documentation"]);
    }

    /**
     * Retrieve a class documentation
     * @param $args
     */
    public function get($args) {
        $classname = urldecode($args["route"]["arguments"]["classname"]);
        echo json_encode($this->repository->findOne("classname", $classname));
    }
}