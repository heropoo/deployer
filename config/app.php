<?php
/**
 * User: heropoo
 * Date: 2018/12/21
 * Time: 11:42
 */

return [
    'title' => 'Deployer',    //部署系统标题
    'timezone' => 'Asia/Shanghai',

    'deployer_log_file' => dirname(__DIR__) . '/runtime/logs/deployer-deployer-' . date('Y-m-d') . '.log',    //日志路径
    'server_log_file' => dirname(__DIR__) . '/runtime/logs/deployer-server-' . date('Y-m-d') . '.log',    //日志路径

    'secret_key' => '',       //加密密钥

    'private_key' => '',    //私钥

    'projects' => [],  //发布项目列表

    'hosts' => [],  //目标机器列表

    'users' => [], //管理员账号,
];