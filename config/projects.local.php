<?php
//发布项目列表
return [
    'project1' => [
        'name' => '示例项目1',
        'dist_servers' => [ //目标机器列表
            'prod-1-1' => [
                'name' => '示例服务器1-1',
                'user' => 'www-data',//目标机器用户
                'url' => 'http://192.168.31.4:8081', //目标机器url
                'path' => '/var/www/path1', //项目在目标机器上的路径
            ],
            'prod-1-2' => [
                'name' => '示例服务器1-2',
                'user' => 'www-data',
                'url' => 'http://192.168.31.5:8081',
                'path' => '/var/www/path1',
            ],
        ]
    ],
    'project2' => [
        'name' => '示例项目2',
        'dist_servers' => [ //目标机器列表
            'prod-2-1' => [
                'name' => '示例服务器2-1',
                'user' => 'www-data',
                'url' => 'http://192.168.31.4:8081',
                'path' => '/var/www/path2', //项目在目标机器上的路径
            ]
        ]
    ]
];