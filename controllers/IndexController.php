<?php

namespace ant\controllers;

use ant\core\BaseController;
use ant\core\Registry;

class IndexController extends BaseController
{
    public function indexAction()
    {
        echo "<pre>";
//        var_dump($_SERVER);
//        var_dump(parse_ini_file(ANT_PATH.'/config/application.ini', true));
        var_dump(Registry::get('config'));
        echo "</pre>";
    }
}