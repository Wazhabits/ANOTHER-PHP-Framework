<?php


namespace Framework\Controller;

use Core\Controller;

class DefaultController extends Controller
{
    /**
     * @route /
     */
    public function index()
    {
        var_dump("COUCOU"); die;
    }
}