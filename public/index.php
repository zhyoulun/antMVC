<?php
define('ANT_PATH', dirname(__DIR__));

define('ANT_DEBUG', false);

require ANT_PATH.'/vendor/autoload.php';
require ANT_PATH.'/core/init.php';

$ant = new \ant\core\Ant();
$ant->run();