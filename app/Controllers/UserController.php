<?php
/**
 * User: nano
 * Datetime: 2022/9/25 1:13 下午
 */

namespace App\Controllers;


class UserController
{
    public function logout(){
        $user = \App::get('user');
        $user->logout();
        return redirect('/');
    }
}