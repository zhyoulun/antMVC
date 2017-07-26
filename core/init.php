<?php
require ANT_PATH.'/core/Ant.php';

use ant\core\Ant;

spl_autoload_register(array(new Ant(),'autoload'));
