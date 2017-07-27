<?php

namespace ant\models;

use ant\core\Exception;

class DemoModel
{
    public function __construct()
    {
        throw new Exception('hello world');
    }
}