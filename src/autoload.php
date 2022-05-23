<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 9:07 上午
 */

require_once __DIR__ . '/helpers.php';

spl_autoload_register(function ($className) {
    $prefix = 'Deployer';
    if (strpos($className, $prefix) === 0) {
        $relativeClass = substr($className, strlen($prefix));
        include_once __DIR__ . DIRECTORY_SEPARATOR
            . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
    }
});