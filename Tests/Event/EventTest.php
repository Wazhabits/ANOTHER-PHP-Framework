<?php


namespace Tests;

require_once "Core/Event/Interface/Event.php";
require_once "Core/Event/Event.php";

use Core\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testAddEvent() {
        Event::add("test/event.1");
        Event::add("test/event.2", "Example\\Test::example");
        $this->assertEquals([
            "test/event.1" => [],
            "test/event.2" => [
                "Example\\Test::example"
            ]
        ], Event::$event);
    }

    public function testExecEventValid() {
        Event::add("test/event.second", "Tests\EventTest::eventListener");
        Event::exec("test/event.second", $bool);
        $this->assertTrue($bool);
        $this->assertFalse(Event::exec("test/event.nonExistingEvent"));
    }

    public function testExecEventNonValid() {
        $this->assertFalse(Event::exec("test/event.nonExistingEvent"));
        $this->assertFalse(Event::exec("*"));
        $this->assertFalse(Event::exec(""));
        $this->assertFalse(Event::exec(false));
        $this->assertFalse(Event::exec(null));
    }

    /**
     * This function will be call by Event::exec
     * @param $args
     */
    static function eventListener(&$args) {
        $args = true;
    }
}