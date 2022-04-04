<?php
/**
 * 用户密码生成工具
 * User: heropoo
 * Date: 2018/12/21
 * Time: 12:21
 */

$config = require __DIR__ . '/config/app.php';

if ($_SERVER['argc'] < 2) {
    exit('Please input a password to generate password hash.');
}
echo $pwd = password_hash($config['secret_key'] . trim($_SERVER['argv'][1]), PASSWORD_DEFAULT) . PHP_EOL;
