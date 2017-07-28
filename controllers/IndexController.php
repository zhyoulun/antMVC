<?php

namespace ant\controllers;

use ant\core\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        echo 'hello world';
//        echo "<pre>";
////        var_dump(Registry::get('config'));
//        var_dump($_SERVER);
//        echo "</pre>";
    }
}