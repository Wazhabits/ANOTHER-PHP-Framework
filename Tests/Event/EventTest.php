<?php


namespace Tests;

require_once "Core/Event/Interface/Event.php";
require_once "Core/Event/Event.php";

use Core\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private $expectedEvent = [
        "test/event.first" => [

        ],
    ];
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        Event::add("test/event.first");
        parent::__construct($name, $data, $dataName);
    }

    public function testAddEvent() {
        $this->assertEquals($this->expectedEvent, Event::$event);
    }

    public function testExecEvent() {
        Event::add("test/event.second", "Tests\EventTest::eventListener");
        Event::exec("test/event.second", $bool);
        $this->assertTrue($bool);
    }

    /**
     * This function will be call by Event::exec
     * @param $args
     */
    static function eventListener(&$args) {
        $args = true;
    }
}