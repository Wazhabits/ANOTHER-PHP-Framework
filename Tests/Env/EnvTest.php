<?php


namespace Tests;

require_once "Core/Loader/Interface/LoaderBase.php";
require_once "Core/Loader/Loader.php";

use Core\Env;
use Core\Loader;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->expectedConfiguration = [
            "SETTINGSWITHVALUE" =>"value",
            "SETTINGSWITHOUTVALUE" => "",
            "SETTINGSNEXTTOBLANKVALUE" => "working"
        ];
        if (!defined("PATH_CORE"))
            define("PATH_CORE", __DIR__ . "/../.." . DIRECTORY_SEPARATOR . "Core");
        Loader::explore(PATH_CORE, "Interface");
        Loader::explore(PATH_CORE, "", "Interface");
        $this->env = new Env(__DIR__ . DIRECTORY_SEPARATOR . ".env");
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @var Env
     */
    private $env;

    /**
     * @var array $expectedConfiguration
     */
    private $expectedConfiguration;


    public function testReadEnvFile() {
        $this->assertEquals($this->expectedConfiguration, $this->env->getConfiguration());
    }

    public function testSetCustomEnv() {
        $this->env->set("CustomEnvSetting", "custom");
        $this->assertEquals("custom", $this->env->getConfiguration("CUSTOMENVSETTING"));
    }

    public function testGetEnv() {
        $this->env->set("CustomEnvSetting", "custom");
        $this->assertEquals(null, $this->env->getConfiguration("UNEXISTINGENVSETTING"));
        $this->assertEquals("custom", $this->env->getConfiguration("CUSTOMENVSETTING"));
        $this->assertEquals("", $this->env->getConfiguration("SETTINGSWITHOUTVALUE"));
    }
}