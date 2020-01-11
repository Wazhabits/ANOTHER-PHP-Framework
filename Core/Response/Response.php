<?php


namespace Core;


class Response
{
    static $response = [];

    static function init() {
        self::$response = [
            "code" => 200,
            "header" => [
                "Content-Type" => "text/html;charset=UTF-8"
            ]
        ];
    }

    static function setStatus($code) {
        self::$response["code"] = $code;
    }

    static function setHeader($header) {
        if (is_array($header)) {
            foreach ($header as $element => $value)
                self::$response["header"][$element] = $value;
        } else {
            self::$response["header"][explode(":", $header)[0]] = str_replace(explode(":", $header)[0], "", $header);
        }
    }

    static function send() {
        foreach (self::$response["header"] as $property => $value) {
            header($property . ": " . $value);
        }
        http_response_code(self::$response["code"]);
    }
}