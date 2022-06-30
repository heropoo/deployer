<?php
/**
 * Date: 2018/1/29
 * Time: 14:14
 */

namespace App\Middleware;

use Moon\Config\Config;
use Moon\Request\Request;
use Closure;
use Moon\Session\Session;

class SessionStart
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Config $config */
        $config = \App::$container->get('config');
        $config = $config->get('session');
        //$config = config('session');

        if (isset($config['name'])) {
            $sessionName = $config['name'];
        } else {
            $sessionName = session_name();
        }

        if (isset($request->cookie[$sessionName])) {
            $sessionId = $request->cookie[$sessionName];
        } else {
            $sessionId = session_create_id();
        }

        $cookieParams = session_get_cookie_params();

        $session = new Session($sessionName, $sessionId, $cookieParams, $config);

        $session->start();

        $request->setSession($session);

        return $next($request);
    }
}