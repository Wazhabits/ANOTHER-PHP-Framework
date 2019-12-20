<?php


namespace Core\Template\Parts;


class Conditions
{
    private $buffer;
    private $args;

    public function __construct(&$buffer, &$args)
    {
        $this->buffer = &$buffer;
        $this->args = $args;
    }

    /**
     * This function call each other function and build if>elseif>else part of templates
     */
    public function build() {
        $matchesIf = [];
        preg_match_all("/({if:(.*?)}(.*?){:if})/s", $this->buffer, $matchesIf);
        $conditions = &$matchesIf[2];
        $content = &$matchesIf[3];
        $ifIndex = 0;
        while ($ifIndex < count($conditions)) {
            $condition = &$conditions[$ifIndex];
            $matchesThen = [];
            $matchesElseIf = [];
            $matchesElse = [];
            $newContent = $content[$ifIndex];
            preg_match_all("/({then}(.*?){:then})/s", $newContent, $matchesThen);
            preg_match_all("/({elseif:(.*?)}(.*?){:elseif})/s", $newContent, $matchesElseIf);
            preg_match_all("/({else}(.*?){:else})/s", $newContent, $matchesElse);
            if ($this->getConditionResult($condition) === false) {
                // Case False : Remove true part
                $newContent = str_replace($matchesThen[1][$ifIndex], "", $newContent);
                if (count($matchesElseIf[1])) {
                    $elseIfIndex = 0;
                    $eraser = false;
                    while ($elseIfIndex < count($matchesElseIf[2])) {
                        if ($eraser === true) {
                            // Elseif = true found, remove all other
                            $newContent = str_replace($matchesElseIf[1][$elseIfIndex], "", $newContent);
                        } else {
                            // Case elseif = true
                            if ($this->getConditionResult($matchesElseIf[2][$elseIfIndex])) {
                                $newContent = preg_replace("/{elseif:" . $matchesElseIf[2][$elseIfIndex] . "}(.*?){:elseif}/s", "$1", $newContent);
                                // Removing else part
                                $newContent = str_replace($matchesElse[1][$ifIndex], "", $newContent);
                                $eraser = true;
                            }
                            // Case elseif = false
                            else {
                                // Removing this elseif
                                $newContent = str_replace($matchesElseIf[1][$elseIfIndex], "", $newContent);
                            }
                        }
                        $elseIfIndex++;
                    }
                }
                $newContent = preg_replace("/{else}(.*?){:else}/s", "$1", $newContent);
            } else {
                // Case True : remove false part
                $newContent = str_replace($matchesElse[1][$ifIndex], "", $newContent);
                $newContent = str_replace($matchesElseIf[1][$ifIndex], "", $newContent);
                $newContent = preg_replace("/{then}(.*?){:then}/s", "$1", $newContent);
            }
            $this->buffer = str_replace($matchesIf[1], $newContent, $this->buffer);
            $ifIndex++;
        }
    }

    private function getConditionResult($condition) {
        $result = true;
        $conditionParts = [];
        preg_match_all("/(.*\s.*\s.*)/", $condition, $conditionParts);
        if (count($conditionParts[1])) {
            $conditionPartIndex = 0;
            while ($conditionPartIndex < count($conditionParts[1])) {
                $conditionArray = explode(" ", $conditionParts[1][$conditionPartIndex]);
                $leftPart = $conditionArray[0];
                $operator = $conditionArray[1];
                $rightPart = $conditionArray[2];
                if (!isset($this->args[$leftPart])) {
                    return false;
                }
                switch ($operator) {
                    case "===":
                        if ($this->args[$leftPart] !== $rightPart)
                            $result = false;
                        break;
                    case "!==":
                        if ($this->args[$leftPart] === $rightPart)
                            $result = false;
                        break;
                    case ">":
                        if ($this->args[$leftPart] <= $rightPart)
                            $result = false;
                        break;
                    case "<":
                        if ($this->args[$leftPart] >= $rightPart)
                            $result = false;
                        break;
                    case ">=":
                        if ($this->args[$leftPart] < $rightPart)
                            $result = false;
                        break;
                    case "<=":
                        if ($this->args[$leftPart] > $rightPart)
                            $result = false;
                        break;
                    default:
                        break;
                }
                $conditionPartIndex++;
            }
        }
        return $result;
    }
}