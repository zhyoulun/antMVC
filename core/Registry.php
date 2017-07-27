<?php

namespace ant\core;


class Registry
{
    protected static $_instance = null;
    protected $_entries = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * 根据name获取value，如果为空则返回null
     *
     * @param $name
     * @return mixed|null
     */
    public static function get($name)
    {
        if (key_exists($name, self::getInstance()->_entries)) {
            return self::getInstance()->_entries[$name];
        }
        return null;
    }

    /**
     * 查看是否存在键为name的值
     *
     * @param $name
     * @return bool
     */
    public static function has($name)
    {
        return key_exists($name, self::getInstance()->_entries);
    }

    /**
     * 设置$name=>$value
     *
     * @param $name
     * @param $value
     * @return true
     */
    public static function set($name, $value)
    {
        self::getInstance()->_entries[$name] = $value;
        return true;
    }

    /**
     * 删除键为$name的值
     *
     * @param $name
     * @return true
     */
    public static function del($name)
    {
        unset(self::getInstance()->_entries[$name]);
        return true;
    }

    private static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();

            self::$_instance->_entries = array();
        }

        return self::$_instance;
    }
}