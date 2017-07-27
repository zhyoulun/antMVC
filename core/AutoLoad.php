<?php

namespace ant\core;


class AutoLoad
{
    /**
     * auto load
     *
     * @param $className
     * @throws Exception
     */
    public static function autoload($className)
    {
        $file = substr($className, 4);//remove "ant\" head
        $file = str_replace('\\', '/', ANT_PATH . "/{$file}.php");
        if (is_file($file)) {
            include($file);
        } elseif (ANT_DEBUG) {
            throw new Exception("class {$className} not found, file: " . $file);
        }
    }
}