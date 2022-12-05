<?php
/**
 * User: heropoo
 * Datetime: 2022/7/1 1:44 上午
 */

use Moon\Routing\Router;
use Moon\Request\Request;

/** @var Router $router */

$router->group(['middleware' => \App\Middleware\BasicAuth::class], function () use ($router) {
    $router->get('/', 'IndexController::index');
    $router->get('/projects', 'IndexController::projects');
    $router->post('/publish', 'IndexController::publish');
    $router->get('/logs', 'IndexController::logs');
    $router->get('/logs/diff', 'IndexController::diff');
    $router->controller('user', 'UserController');
});