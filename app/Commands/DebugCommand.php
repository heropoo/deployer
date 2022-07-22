<?php
/**
 * Date: 2019-11-06
 * Time: 10:58
 */
namespace App\Commands;

use Moon\Routing\Route;
use Moon\Routing\Router;

class DebugCommand
{
    public function routes()
    {
        /** @var Router $router */
        $router = \App::$container->get('router');
        $routes = $router->getRoutes();
        echo "| Name | Methods | Path | Action | Middleware |\n";
        echo "-----------------------------------------------\n";
        foreach ($routes as $route) {
            /** @var Route $route */
            echo '| '.$route->getName();
            echo ' | '.json_encode($route->getMethods());
            echo ' | '.$route->getPath();
            echo ' | '.json_encode($route->getAction());
            echo ' | '.json_encode($route->getMiddleware());
            echo " |\n";
        }
    }
}