<?php

namespace ant\core;


class Response
{
    public $headers = array();
    private $version;
    private $statusCode = 200;
    private $statusText = 'OK';
    public $content;

    public static $httpStatuses = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        118 => 'Connection timed out',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        210 => 'Content Different',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway or Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        507 => 'Insufficient storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    );

    public function __construct()
    {
        $this->headers['X-POWERED-BY'] = 'antMVC';

        if ($this->version === null) {
            if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
                $this->version = '1.0';
            } else {
                $this->version = '1.1';
            }
        }
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        $this->statusText = isset(self::$httpStatuses[$this->statusCode]) ? self::$httpStatuses[$this->statusCode] : 'Common Error';
    }

    public function setStatusText($statusText)
    {
        $this->statusText = $statusText;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getStatusText()
    {
        return $this->statusText;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    private function sendHeaders()
    {
        if (headers_sent()) {
            return;
        }

        if (!empty($this->headers)) {
            foreach ($this->headers as $name => $value) {
                $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
                header("$name: $value");
            }
        }
        header("HTTP/{$this->version} {$this->statusCode} {$this->statusText}");
    }

    private function sendContent()
    {
        echo $this->content;
    }
}