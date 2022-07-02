<?php
/**
 * User: heropoo
 * Datetime: 2022/6/14 12:33 上午
 */

class App
{
    const VERSION = '1.6.0';

    /** @var \Moon\Container\Container $container */
    public static $container;

    /** @var \Moon\Application $instance */
    public static $instance;

    public static function get($name)
    {
        return static::$container->get($name);
    }
}