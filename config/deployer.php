<?php
/**
 * User: heropoo
 * Date: 2018/12/21
 * Time: 11:42
 */

return [
    //部署系统标题
    'title' => 'Deployer',

    //日志路径
    'deployer_log_file' => dirname(__DIR__) . '/runtime/logs/deployer.log',

    //加密密钥
    'secret_key' => '',

    //私钥
    'private_key' => '',

    //发布项目列表
    'projects' => [],

    //目标机器列表
    'hosts' => [],

    //管理员账号
    'users' => [],
];