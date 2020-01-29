<?php


namespace Modules\Documentation\Event;

use Core\Database;
use Core\Kernel;
use Core\Logger;
use Modules\Documentation\Maker;
use Modules\Documentation\Model\Classes;
use Modules\Documentation\Model\Method;
use Modules\Documentation\Model\Property;

class KernelBootEvent
{
    /**
     * @event core/kernel.boot
     */
    static function makeDocumentation() {
        Logger::log("documentation", "Builder running", Logger::$DEFAULT_LEVEL);
        $maker = new Maker();
        $maker->extract();
    }
}