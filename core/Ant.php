<?php

namespace ant\core;

class Ant
{
    public function run()
    {
        register_shutdown_function(array($this, 'catch_fatal_error'));

        try{
            new A();
            echo "entry";
        }catch (Exception $e){
            echo "<pre>";
            var_dump($e);
            echo "</pre>";
        }
    }

    /**
     * catch fatal error
     */
    private function catch_fatal_error()
    {
        $e = error_get_last();
        echo "<pre>";
        var_dump($e);
        echo "</pre>";
    }

    /**
     * auto load
     *
     * @param $className
     * @throws Exception
     */
    public static function autoload($className)
    {
        $file = substr($className, 4);//remove "ant\" head
        $file = str_replace('\\', '/', ANT_PATH."/{$file}.php");
        if(is_file($file))
            include($file);
        elseif(ANT_DEBUG){
            throw new Exception("class {$className} not found, file: ".$file);
        }
    }
}