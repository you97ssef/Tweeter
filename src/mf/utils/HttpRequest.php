<?php

namespace mf\utils;

class HttpRequest extends AbstractHttpRequest
{
    function __construct()
    {
        $this->script_name = $_SERVER["SCRIPT_NAME"];
        if (isset($_SERVER["PATH_INFO"]))
            $this->path_info = $_SERVER["PATH_INFO"];
        $this->root = dirname($_SERVER["SCRIPT_NAME"]);
        if ($this->root === "/")
            $this->root = "";
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->get = $_GET;
        $this->post = $_POST;
    }
}
