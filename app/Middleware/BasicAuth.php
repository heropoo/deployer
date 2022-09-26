<?php
/**
 * Date: 2020-01-17
 * Time: 14:58
 */

namespace App\Middleware;


use App\Models\User;
use Moon\Request\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuth
{
    public function handle(Request $request, \Closure $next)
    {
//        $session = $request->getSession();
//        $token_expiration_time = $session->get('token_expiration_time');

        $config = config('load');
        //$users = $config['users'];
        $realm = md5($config['secret_key']);
        $server = $request->server;
        if (!isset($server['PHP_AUTH_USER'])) {
            return new Response(
                '401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>',
                401, ['WWW-Authenticate' => 'Basic realm="' . $realm . '"']
            );
        } else {
            $username = isset($server['PHP_AUTH_USER']) ? trim($server['PHP_AUTH_USER']) : '';
            $pwd = $config['secret_key'] . trim($server['PHP_AUTH_PW']);

//            if (!key_exists($username, $users) || !password_verify($pwd, $users[$username])) {
//                return new Response(
//                    '401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>',
//                    401, ['WWW-Authenticate' => 'Basic realm="' . $realm . '"']
//                );
//            }
            /** @var User $user */
            $user = User::find()->where("username=?", [$username])->first();
            if (empty($user)
                || !password_verify($pwd, $user->password)
                || $user->token_expiration_time < date('Y-m-d H:i:s')
//                || ($token_expiration_time < date('Y-m-d H:i:s'))
            ) {
                if($user){
                    $user->token_expiration_time = date('Y-m-d H:i:s', time() + 5);
                    $user->save();
                }
                return new Response(
                    '401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>',
                    401, ['WWW-Authenticate' => 'Basic realm="' . $realm . '"']
                );
            }

            var_dump($user->token_expiration_time, date('Y-m-d H:i:s'));

        }

        \App::$container->instance('user', $user);

        return $next($request);
    }
}