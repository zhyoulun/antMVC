<?php
require ANT_PATH.'/core/AutoLoad.php';

use ant\core\AutoLoad;

spl_autoload_register(array(new AutoLoad(),'autoload'));
