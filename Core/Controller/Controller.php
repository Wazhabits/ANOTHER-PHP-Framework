<?php


namespace Core;

use Core\Controller\Controller as Base;


class Controller implements Base
{
    public function render($templatePath, $args = []) {
        Template::render($templatePath, $args);
    }
}