<?php


namespace Framework\Controller;

use Core\Controller;

class DefaultController extends Controller
{
    /**
     * @site framework.ddev.site
     * @route /test/test
     */
    public function index()
    {
        var_dump("COUCOU");
    }
}