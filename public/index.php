<?php
define('ANT_PATH', dirname(__DIR__));

//debug mode switch
define('ANT_DEBUG', true);

require ANT_PATH.'/vendor/autoload.php';
require ANT_PATH.'/core/init.php';

$ant = new \ant\core\Ant();
$ant->run();