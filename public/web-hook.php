<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/3/30
 * Time: 22:22
 */

//log_info('input:'.var_export(file_get_contents('php://input'), 1));
//log_info('get:'.var_export($_GET, 1));
//log_info('post:'.var_export($_POST, 1));

ini_set('display_errors', true);
echo 'update....';
$res = exec('/bin/bash /var/www/cloud-school/fc6a626c43d1976104c0241e19273b67');
var_dump($res);
log_info('res:'.var_export($res, 1));

function log_info($msg){
    $msg = '['.date('Y-m-d H:i:s').']'.$msg.PHP_EOL;
    $file = dirname(__DIR__).'/storage/logs/web-hook.log';
    error_log($msg, 3, $file);
}