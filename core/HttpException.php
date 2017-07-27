<?php

namespace ant\core;

class HttpException extends Exception
{
    public function __construct($code = 0, $message = "")
    {
        if($message==''){
            $message = isset(Response::$httpStatuses[$code])?Response::$httpStatuses[$code]:'Common Error';
        }
        parent::__construct($message, $code, null);
    }

}