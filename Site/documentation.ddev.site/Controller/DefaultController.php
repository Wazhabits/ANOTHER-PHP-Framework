<?php


namespace Documentation\Controller;


use Core\Controller;

class DefaultController extends Controller
{
    /**
     * @site documentation.ddev.site
     * @route /
     */
    public function index() {
        $this->render("layout/index", []);
    }

    /**
     * @site documentation.ddev.site
     * @route /start
     */
    public function start() {
        $this->render("layout/start", []);
    }
}