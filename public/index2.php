<?php
/**
 * User: heropoo
 * Date: 2018/4/23
 * Time: 16:34
 */

$config = require dirname(__DIR__) . '/src/bootstrap.php';

$realm = md5($config['secret_key']);

$users = $config['users'];

$projects = $config['projects'];

$username = isset($_SERVER['PHP_AUTH_USER']) ? trim($_SERVER['PHP_AUTH_USER']) : '';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="' . $realm . '"');
    header('HTTP/1.0 401 Unauthorized');
    die('401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login</button>');
} else {
    $pwd = $config['secret_key'] . trim($_SERVER['PHP_AUTH_PW']);
    if (!key_exists($username, $users) || !password_verify($pwd, $users[$username])) {
        header('WWW-Authenticate: Basic realm="' . $realm . '"');
        header('HTTP/1.0 401 Unauthorized');
        die('401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>');
    }
}

$path_info = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if($path_info === '/logs'){
    require __DIR__.'/../views/logs.php';
}else{
    require __DIR__.'/../views/home.php';
}
