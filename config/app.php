<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/12/21
 * Time: 11:42
 */

return [
    'title' => 'Deployer',    //部署系统标题

    'deployer_log_file' => dirname(__DIR__) . '/runtime/logs/deployer-' . date('Y-m-d') . '.log',    //日志路径

    'secret_key' => 'W4sxjDXyLcnoNWauDH3pI0nrdSdmYUKL',       //加密密钥 上线请修改！！

    'users' => require __DIR__ . '/users.php', //管理员账号

    'projects' => [   //发布项目列表
        'project1' => '示例项目1',
        'project2' => '示例项目2',
    ],

    'servers' => [    //目标机器列表
        'prod-1' => 'http://192.168.31.4:8081',  //如果部署机器和目标机器在同一内网，建议使用内网地址
        'prod-2' => 'http://192.168.31.5:8081',
        'prod-3' => 'http://192.168.31.6:8081'
    ]
];