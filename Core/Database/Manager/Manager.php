<?php


namespace Core\Database;


use Core\Kernel;

class Manager
{
    /**
     * @var Connection
     */
    private static $connection = [];

    /**
     * @param string $driver
     * @return Connection|false
     */
    static function getConnection($driver) {
        return (isset(self::$connection[$driver])) ? self::$connection[$driver] : false;
    }

    /**
     * @param Connection &$connection
     */
    static function setConnection(&$connection) {
       self::$connection[$connection->getName()] = $connection;
    }
    /**
     * @param $string
     * @return string
     */
    static function getTableName($string) {
        if (strpos($string, "\\") !== false) {
            $value = strtolower(str_replace("\\", "_", $string));
        } else {
            $index = 0;
            $string = explode("_", $string);
            $classname = "";
            while ($index < count($string)) {
                $classname .= ucfirst($string[$index]);
                if ($index !== count($string) - 1)
                    $classname .= "\\";
                $index++;
            }
            $value = $classname;
        }
        return $value;
    }

    /**
     * @param $elements
     * @param array $result
     * @return array<\Core\Database\Model\Model>
     */
    static function convert(&$elements, $result = []) {
        if ($elements === false)
            return [];
        $table = Manager::getTableName(array_keys($elements)[0]);
        $elements = $elements[array_keys($elements)[0]];
        foreach ($elements as $sorting => $element) {
            $result[] = new $table($element, $sorting);
        }
        return $result;
    }
}