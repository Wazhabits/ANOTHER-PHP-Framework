<?php


namespace Framework\Controller;


use Core\Controller;
use Core\Kernel;

class DocumentationController extends Controller
{
    /**
     * @site framework.ddev.site
     * @route /documentation
     */
    public function documentation() {
        $this->render("documentation", ["annotation" => Kernel::getAnnotation()->getDocumentation()]);
    }
}