<?php
/**
 * Date: 2018/1/29
 * Time: 14:14
 */

namespace App\Middleware;

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
        $config = config('session');

        if (isset($config['name'])) {
            $sessionName = $config['name'];
        } else {
            $sessionName = session_name();
        }

        if (isset($request->cookie[$sessionName])) {
            $sessionId = $request->cookie[$sessionName];
        } else {
            $sessionId = function_exists('session_create_id')
                ? call_user_func('session_create_id') : generate_random_str(32);
        }

        $cookieParams = session_get_cookie_params();

        $session = new Session($sessionName, $sessionId, $cookieParams, $config);

        $session->start();

        $request->setSession($session);

        return $next($request);
    }
}