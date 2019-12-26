<?php

namespace Core\Template\Parts;

class Vars
{
    /**
     * Vars constructor.
     * @param $buffer
     * @param $args
     * @param string $quote
     */
    public function __construct(&$buffer, &$args, $quote = "")
    {
        $matches = [];
        preg_match_all("/{([\w|\w.\w+]*)}/", $buffer, $matches);
        foreach ($matches[1] as $vars) {
            $path = explode(".", $vars);
            $value = $this->getVarsValue($args, $path, 0, $quote);
            $buffer = ($value !== false) ?
                str_replace("{" . $vars . "}", $value, $buffer)
                : $buffer = str_replace("{" . $vars . "}", "false", $buffer);
        }
    }

    /**
     * @param $args
     * @param $path
     * @param int $index
     * @param string $quote
     * @return bool|string
     */
    private function getVarsValue($args, $path, $index = 0, $quote = "")
    {
        if (count($path) - 1 > $index) {
            return (isset($args[$path[$index]])) ?
                $this->getVarsValue($args[$path[$index]], $path, $index + 1, $quote)
                : false;
        } else
            return $quote . str_replace($quote, "\\" . $quote, $args[$path[$index]]) . $quote;
    }
}