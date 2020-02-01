<?php


namespace Core;


class Response
{
    static $response = [];
    static $SENT = false;

    static function initialize() {
        self::$response = [
            "code" => 200,
            "header" => [
                "Content-Type" => "text/html;charset=UTF-8"
            ]
        ];
    }

    /**
     * @param $code
     */
    static function setStatus($code) {
        self::$response["code"] = $code;
    }

    /**
     * @param $header
     */
    static function setHeader($header) {
        if (is_array($header)) {
            foreach ($header as $element => $value)
                self::$response["header"][$element] = $value;
        } else {
            self::$response["header"][explode(":", $header)[0]] = str_replace(explode(":", $header)[0], "", $header);
        }
    }

    static function send() {
        if (self::$SENT)
            return;
        self::$SENT = true;
        if (Environment::getConfiguration("SHOW_EXECUTION_TIME") === "true")
            self::$response["header"]["fw-exec-time"] = Environment::getExecutionTime() . "ms";
        foreach (self::$response["header"] as $property => $value) {
            header($property . ": " . $value);
        }
        http_response_code(self::$response["code"]);
    }
}