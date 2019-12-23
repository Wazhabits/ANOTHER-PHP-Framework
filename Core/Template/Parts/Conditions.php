<?php


namespace Core\Template\Parts;


class Conditions
{
    public function __construct(&$buffer, &$args)
    {
        $this->build($buffer, $args);
    }

    /**
     * This function return the result of a condition in template
     * @param $condition
     * @param $args
     * @return mixed
     */
    private function exec(&$condition, &$args) {
        new Vars($condition, $args, "'");
        return eval('return ' . $condition . ';');
    }

    /**
     * This function build a simple if/else statement in template
     * @param $buffer
     * @param $args
     */
    private function build(&$buffer, &$args) {
        preg_match_all("/\[if:(.*)\](.*)\[if\]/s", $buffer, $matches);
        $conditions = $matches[1];
        foreach ($conditions as $index => $condition) {
            preg_match_all("/{then}(.*){then}/s", $buffer, $then);
            $then = $then[1];
            preg_match_all("/{else}(.*){else}/s", $buffer, $else);
            $else = $else[1];
            if ($this->exec( $condition, $args)) {
                $buffer = str_replace($matches[0][$index], $then[0], $buffer);
            } else {
                $buffer = str_replace($matches[0][$index], $else[0], $buffer);
            }
        }
    }
}