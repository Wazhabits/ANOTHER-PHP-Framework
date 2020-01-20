<?php


namespace Core\Database;


class Manager
{
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