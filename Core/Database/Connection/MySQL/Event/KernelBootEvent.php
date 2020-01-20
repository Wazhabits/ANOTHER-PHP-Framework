<?php

namespace Core\Database\Connection\MySQL\Event;

use Core\Database\Connection\MySQL\Reader\Model;
use Core\Loader;

class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function listenKernelBoot(&$injection) {
           $injection["mysql"]["reader"] = new Model();
    }
}