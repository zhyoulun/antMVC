<?php

namespace ant\core;


class BaseController
{
    public function getQueryParam($name, $value='')
    {
        return isset($_GET[$name])?$_GET[$name]:$value;
    }

    public function getPostParam($name, $value='')
    {
        return isset($_POST[$name])?$_POST[$name]:$value;
    }

    public function getRequestParam($name, $value='')
    {
        return isset($_REQUEST[$name])?$_REQUEST[$name]:$value;
    }
}