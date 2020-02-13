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
        $this->render("index", []);
    }
}