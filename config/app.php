<?php
/**
 * User: heropoo
 * Date: 2018/12/21
 * Time: 11:42
 */

date_default_timezone_set('Asia/Shanghai');

$config = [
    'title' => 'Deployer',    //部署系统标题

    'deployer_log_file' => dirname(__DIR__) . '/runtime/logs/deployer-deployer-' . date('Y-m-d') . '.log',    //日志路径
    'server_log_file' => dirname(__DIR__) . '/runtime/logs/deployer-server-' . date('Y-m-d') . '.log',    //日志路径

    'secret_key' => 'W4sxjDXyLcnoNWauDH3pI0nrdSdmYUKL',       //加密密钥 上线请修改！！

    'projects' => [   //发布项目列表
        'project1' => '示例项目1',
        'project2' => '示例项目2',
    ],

    'project_paths' => [   //项目在目标机器上的路径
        'project1' => '/var/www/project1',
        'project2' => '/var/www/project2',
    ],

    'servers' => [    //目标机器列表
        'prod-1' => 'http://192.168.31.4:8081',  //如果部署机器和目标机器在同一内网，建议使用内网地址
        'prod-2' => 'http://192.168.31.5:8081',
        'prod-3' => 'http://192.168.31.6:8081'
    ],

    'server_users' => [    //目标机器上执行用来git命令的用户
        'prod-1' => 'root',
        'prod-2' => 'www-data',
        'prod-3' => 'www-data'
    ],

    'current_server'=>'prod-1', //在目标机器部署代码时，指定下当前机器

    'users' => [ //管理员账号
        //以下两个示例账户 上线请删除或者修改！！
        'admin'=>'$2y$10$SgqN4g1/XR/3I.GnduKHy.KGo5uhfzLigFgSvei1gcr7tmHL9rrhm',     //admin123
        'demo'=>'$2y$10$HMB7SEsPzCHdA2mrm.Nd5eWrupWB20bi2YSfUgUq2M8CNUInhdnme'   //demo123
    ],
];

if(file_exists(__DIR__.'/app.local.php')){
    $config_local = require_once __DIR__.'/app.local.php';
    $config = array_merge($config, $config_local);
}

return $config;