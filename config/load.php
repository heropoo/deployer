<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 1:00 上午
 */

$config = require __DIR__ . '/deployer.php';

if (file_exists(__DIR__ . '/deployer.local.php')) {
    $config_local = require __DIR__ . '/deployer.local.php';
    $config = array_merge($config, $config_local);
}

if (file_exists(__DIR__ . '/users.local.php')) {
    $users = require __DIR__ . '/users.local.php';
    $config['users'] = $users;
}

if (file_exists(__DIR__ . '/projects.local.php')) {
    $projects = require __DIR__ . '/projects.local.php';
    foreach (glob(__DIR__ . "/projects/*.local.php") as $filename) {
        $include_projects = require $filename;
        $projects = array_merge($projects, $include_projects);
    }
    $config['projects'] = $projects;
}

if (file_exists(__DIR__ . '/hosts.local.php')) {
    $projects = require __DIR__ . '/hosts.local.php';
    $config['hosts'] = $projects;
}

//if(!empty($config['timezone'])){
//    date_default_timezone_set($config['timezone']);
//}

return $config;