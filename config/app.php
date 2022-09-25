<?php

return [
    'debug' => env('APP_DEBUG', true),
    'environment' => env('APP_ENV', 'production'),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'Asia/Shanghai',
    'components' => [
        'db' => [
            'class' => '\Moon\Db\SqliteConnection',
            //'auto_inject_by_class'=> true, // default true
            'master' => [
                'dsn' => 'sqlite:' . realpath(__DIR__ . '/../runtime/') . '/deployer.db',
                'options' => [
                    PDO::ATTR_PERSISTENT => true
                ]
            ]
        ],
    ],
];