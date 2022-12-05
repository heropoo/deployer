<?php
/**
 * User: heropoo
 * Datetime: 2022/12/5 11:24 下午
 */

namespace App\Controllers;


use App\Services\UserService;
use Moon\Request\Request;

class UserController
{
    public function cpwdAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $password = trim($request->get('password'));
            $old_password = trim($request->get('old_password'));
            $username = \App::get('user')->username;
            $res = UserService::changePassword($username, $password, $old_password);
            if (is_string($res)) {
                return [
                    'code' => 501,
                    'message' => $res,
                ];
            } else if ($res === false) {
                return [
                    'code' => 501,
                    'message' => 'Failed',
                ];
            }
            return [
                'code' => 0,
                'message' => 'Success',
            ];
        }
        return view('user/cpwd', [], 'layouts/app')->setTitle('修改密码');
    }

    public function logoutAction()
    {
        return '//todo logout';
    }
}