<?php
/**
 * User: nano
 * Datetime: 2022/9/25 1:17 下午
 */

namespace App\Traits;


use Moon\Request\Request;
use Moon\Session\Session;

trait UserAuth
{
    public function logout(){
        /** @var Request $request */
        $request = \App::get('request');
        $session = $request->getSession();
        $session->delete('user');
    }
}