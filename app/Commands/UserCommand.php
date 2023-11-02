<?php


namespace App\Commands;


use App\Services\ConfigService;
use App\Services\UserService;

class UserCommand
{
    public function run()
    {
        ConfigService::initConfig();

        $stdin = fopen("php://stdin", "r");
        $s = "Input username: ";
        fwrite($stdin, $s, strlen($s));

        $username = trim(fgets($stdin));
        //echo $username."\n";

        $s = "Input password: ";
        fwrite($stdin, $s, strlen($s));

        $password = trim(fgets($stdin));
        //echo $password."\n";

        $res = UserService::createUser($username, $password);

        if ($res) {
            echo "OK\n";
        } else {
            echo "Failed\n";
        }

    }
}