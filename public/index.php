<?php
/**
 * User: heropoo
 * Date: 2018/4/23
 * Time: 16:34
 */

require __DIR__.'/../vendor/autoload.php';

use Moon\Application;

$app = new Application(dirname(__DIR__));
APP::setVersion('v1.6.2');
$app->run();