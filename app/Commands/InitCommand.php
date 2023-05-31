<?php
/**
 * User: nano
 * Datetime: 2023/5/31 18:23
 */

namespace App\Commands;


use App\Services\ConfigService;
use App\Services\StaticAssetsService;

class InitCommand
{
    public function run()
    {
        ConfigService::initConfig();

        // permission
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

        // Install static assets
        echo "Install static assets:\n";
        echo StaticAssetsService::install();

    }
}