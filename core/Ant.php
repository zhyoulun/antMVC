<?php
/**
 * Created by PhpStorm.
 * User: zhangyoulun-bt
 * Date: 2017/7/26
 * Time: 19:00
 */

namespace ant\core;


class Ant
{
    public function run()
    {
        //catch fatal error
        register_shutdown_function(array($this, 'shutdown_function'));

        try{
            new A();
            echo "entry";
        }catch (Exception $e){
            echo "<pre>";
            var_dump($e);
            echo "</pre>";
        }
    }

    private function shutdown_function()
    {
        $e = error_get_last();
        echo "<pre>";
        var_dump($e);
        echo "</pre>";
    }

    public static function autoload($className)
    {
        $file = substr($className, 4);//remove 'ant\' head
        $file = str_replace('\\', '/', ANT_PATH."/{$file}.php");
        if(is_file($file))
            include($file);
        elseif(ANT_DEBUG){
            throw new Exception("class {$className} not found, file: ".$file);
        }
    }
}