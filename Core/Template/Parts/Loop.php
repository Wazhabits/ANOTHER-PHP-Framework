<?php


namespace Core\Template\Parts;


class Loop
{
    public function __construct(&$buffer, &$args)
    {
        $this->build($buffer, $args);
    }

    /**

    /**
     * This function make a foreach in templates.
     * Entry :
     * ...
     * [foreach:liste as element]
     * <li>bou:'{key:element}:{element}'</li>
     * [foreach]
     * ....
     * @param &$buffer
     * @param &$args
     */
    function build(&$buffer, &$args) {
        preg_match_all("/\[foreach:(.*)\sas\s(\w*)](.*)\[foreach]/U", $buffer, $matches);
        $index = 0;
        while ($index < count($matches[0])) {
            $var = $matches[1][$index];
            $content = $matches[3][$index];

            $final = "";
            foreach ($args[$var] as $key => $value) {
                $tempContent = $content;
                $this->putKey($tempContent, "{key:" . $matches[2][$index] . "}", $key);
                $this->putValue($tempContent, "{" . $matches[2][$index] . "}", $value);
                $final .= $tempContent;
            }
            $buffer = str_replace($matches[0][$index], $final, $buffer);
            $index++;
        }
    }

    private function putKey(&$buffer,  $replace, $key) {
        $buffer = str_replace($replace, $key, $buffer);
    }

    private function putValue(&$buffer,  $replace, $value) {
        if (!is_array($value))
            $buffer = str_replace($replace, $value, $buffer);
    }
}