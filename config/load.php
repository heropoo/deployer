<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 1:00 上午
 */

$config = require __DIR__ . '/app.php';

if (file_exists(__DIR__ . '/app.local.php')) {
    $config_local = require __DIR__ . '/app.local.php';
    $config = array_merge($config, $config_local);
}

if (file_exists(__DIR__ . '/user.local.php')) {
    $users = require __DIR__ . '/user.local.php';
    $config['users'] = $users;
}

if (file_exists(__DIR__ . '/projects.local.php')) {
    $projects = require __DIR__ . '/projects.local.php';
    $config['projects'] = $projects;
}

if(!empty($config['timezone'])){
    date_default_timezone_set($config['timezone']);
}

return $config;