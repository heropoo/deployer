<?php
//发布项目列表
return [
    'project1' => [
        'name' => '示例项目1',
        'path' => '/var/www/path1', //项目在目标机器上的路径
        'branch' => 'master',
        'hosts' => [ //目标机器列表
            'prod-1', 'prod-2'
        ],
        'group' => '', //项目分组
    ],
    'project2' => [
        'name' => '示例项目2',
        'path' => '/var/www/path2', //项目在目标机器上的路径
        'branch' => 'master',
        'hosts' => [ //目标机器列表
            'prod-1'
        ],
        'group' => '', //项目分组
    ],
    'project2_test' => [
        'name' => '示例项目2测试',
        'path' => '/var/www/path2test', //项目在目标机器上的路径
        'branch' => 'master',
        'hosts' => [ //目标机器列表
            'prod-1'
        ],
        'group' => '', //项目分组
    ],
];
