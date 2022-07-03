<?php
/**
 * Date: 2020-01-17
 * Time: 14:58
 */

namespace App\Middleware;


use Moon\Request\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuth
{
    public function handle(Request $request, \Closure $next)
    {
        $config = config('load');
        $users = $config['users'];
        $realm = md5($config['secret_key']);
        $server = $request->server;
        if (!isset($server['PHP_AUTH_USER'])) {
            return new Response(
                '401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>',
                401,
                [
                    'WWW-Authenticate' => 'Basic realm="' . $realm . '"',
                ]
            );
        } else {
            $username = isset($server['PHP_AUTH_USER']) ? trim($server['PHP_AUTH_USER']) : '';
            $pwd = $config['secret_key'] . trim($server['PHP_AUTH_PW']);
            if (!key_exists($username, $users) || !password_verify($pwd, $users[$username])) {
                return new Response(
                    '401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>',
                    401,
                    [
                        'WWW-Authenticate' => 'Basic realm="' . $realm . '"',
                    ]
                );
            }
        }

        return $next($request);
    }
}