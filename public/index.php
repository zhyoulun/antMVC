<?php
define('ANT_PATH', dirname(__DIR__));

//debug mode switch
define('ANT_DEBUG', true);
//composer switch
define('ANT_COMPOSER', false);

if(ANT_COMPOSER) require ANT_PATH.'/vendor/autoload.php';
require ANT_PATH.'/core/init.php';

$ant = new \ant\core\Ant(ANT_PATH.'/config/application.ini', $_SERVER['ANT_ENV']);
//$ant = new \ant\core\Ant(ANT_PATH.'/config/application.ini');
$ant->run();