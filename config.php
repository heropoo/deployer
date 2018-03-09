<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/3/9
 * Time: 16:14
 */

return [
    'git'=> [
        //git仓库地址
        'repository'=> 'git@github.com:heropoo/deployer.git',

        //使用账号密码访问或者密钥访问
        'username'=>'',
        'password'=> '',

        'deploy_key' => __DIR__.'/storage/id_rsa.pub',  // This deploy key always have pull access.
    ],
];