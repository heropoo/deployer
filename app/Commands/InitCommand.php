<?php
/**
 * User: nano
 * Datetime: 2023/5/31 18:23
 */

namespace App\Commands;


use App\Services\ConfigService;
use App\Services\StaticAssetsService;
use App\Services\UserService;

class InitCommand
{
    public function run()
    {
        ConfigService::initConfig();

        self::checkOrAddUser();

        echo "\n";

        // permission
        echo "Granted permissions:\n";
        $root_path = root_path();
        $paths = [
            $root_path . '/runtime',
            $root_path . '/config/projects',
            $root_path . '/config/users.local.php',
            $root_path . '/config/hosts.local.php',
        ];
        foreach ($paths as $path) {
            //$res = chmod($path, 0777);
            exec("chmod -R 777 $path", $output, $res);
            echo "chmod -R 777 $path    " . ($res === 0 ? 'OK' : 'Failed') . PHP_EOL;
        }

        echo "\n";

        // Install static assets
        echo "Install static assets:\n";
        echo StaticAssetsService::install();

    }

    protected static function checkOrAddUser()
    {
        $config_path = root_path('config');
        if (file_exists($config_path . '/users.local.php')) {
            return true;
        }

        echo "No administrator has been created yet. Now create one:\n";

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