<?php


namespace App\Commands;


use App\Models\User;

class TestCommand
{
    public function run()
    {
        $user = new User();
        $user->username = 'test-.' . time();
        $user->password = md5(time());
        $user->create_time = date('Y-m-d H:i:s');
        $user->update_time = date('Y-m-d H:i:s');
        $res = $user->save();
        var_dump($res);
    }
}