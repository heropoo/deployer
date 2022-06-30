<?php
/**
 * User: heropoo
 * Datetime: 2022/7/1 2:13 上午
 */


return [
    'driver' => 'file', //file or redis
    'name' => 'DEPLOYER-SESSION',
    'cookie_lifetime' => 3 * 3600,  //3hour
    //'read_and_close' => true,
    'cookie_httponly' => true,
    'savePath' => App::$instance->getRootPath() . '/runtime/sessions'
];