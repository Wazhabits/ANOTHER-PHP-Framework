<?php

namespace Core\Database\Connection\MySQL\Reader;

use Core\Database\Connection\Mysql\Type\BaseType;
use Core\Database\Manager;
use Core\Files;
use Core\Kernel;

class Model
{
    private $modelsDocumentation;
    private $schema = [];

    public function __construct() {
        $this->parseClasses();
        $this->buildSchemaConfiguration();
        $this->buildSchema();
        $this->buildSql();
        $this->cache();
    }

    /**
     * THis function build the json part of schema array, to save to file
     */
    private function buildSchema() {
        foreach ($this->schema["local"] as $table => $schemaConfiguration) {
            $this->schema["json"][$table] = json_encode($schemaConfiguration);
        }
    }

    private function buildSql() {
        foreach ($this->schema["local"] as $table => $schemaConfiguration) {
            $this->schema["sql"][$table] = "CREATE TABLE `" . $table . "` (\n";
            $index = 0;
            $lines = "";
            foreach ($schemaConfiguration as $column => $configuration) {
                if ($index < count($schemaConfiguration) - 1)
                    $lines .= $this->buildLine($column, $configuration);
                else
                    $lines .= $this->buildLine($column, $configuration, true);
                $index++;
            }
            $this->schema["sql"][$table] .= $lines . ");";
        }
    }

    /**
     * This function save schema if it is different than existing or new
     */
    private function cache() {
        foreach ($this->schema["json"] as $filename => $content) {
            $filepathSchema = "Cache/Database/Schema/" . date(Kernel::getEnvironment()->getConfiguration("LOG_FORMAT")) . "." . $filename . ".json";
            $filepathSql = "Cache/Database/Sql/" . date(Kernel::getEnvironment()->getConfiguration("LOG_FORMAT")) . "." . $filename . ".sql";
            if (!file_exists($filepathSchema) || filesize($filepathSchema) !== strlen($content))
                Files::put($filepathSchema, $content, true);
            if (!file_exists($filepathSql) || filesize($filepathSql) !== strlen($this->schema["sql"][$filename]))
                Files::put($filepathSql, $this->schema["sql"][$filename], true);
        }
    }

    /**
     * Extract model class from all explored classes
     */
    private function parseClasses() {

        foreach (($documentation = Kernel::getAnnotation()->getDocumentation())["classes"] as $workspace => $classes) {
            if ($workspace !== "Core")
                foreach ($classes as $class) {
                    if (strpos($class, "Model") !== false)
                        $this->modelsDocumentation[$workspace . "\\" . $class] = $documentation[$workspace . "\\" . $class];
                }
        }
    }

    /**
     * This function build schema from model class found
     */
    private function buildSchemaConfiguration() {
        foreach ($this->modelsDocumentation as $classname => $documentation) {
            $table = Manager::getTableName($classname);
            $properties = $documentation["properties"];
            foreach ($properties as $index => $configuration) {
                $column = strtolower($index);
                foreach ($configuration as $config => $value) {
                    if (in_array($config, ["type", "size", "default", "nullable", "primary", "ai", "unique", "foreign"]))
                        $this->schema["local"][$table][$column][$config] = trim($value[0]);
                }
            }
        }
    }

    private function buildLine($column, $configuration, $isLast = false) {
        if (!isset($configuration["type"]))
            return false;
        $sql = "`" . $column . "` " . strtoupper($configuration["type"]);
        if (isset($configuration["size"]))
            $sql .= "(" . $configuration["size"] . ")";
        if (isset($configuration["primary"]) && $configuration["primary"] === "true") {
            $sql .= " PRIMARY KEY";
        }
        if (isset($configuration["default"]))
            $sql .= " DEFAULT " . $this->format($configuration["default"]);
        if (isset($configuration["nullable"]))
            $sql .= ($configuration["nullable"] === "true") ? " NULL" : " NOT NULL";
        if (isset($configuration["unique"]) && $configuration["unique"] === "true")
            $sql .= " UNIQUE";
        if (isset($configuration["primary"]) && $configuration["primary"] === "true")
            $sql .= " AUTO_INCREMENT";
        if (!$isLast)
            $sql .= ",";
        return $sql . "\n";
    }

    private function format($value) {
        return str_replace("{time.current}", time(), $value);
    }
}