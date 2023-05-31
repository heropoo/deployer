<?php
/**
 * User: nano
 * Datetime: 2023/5/31 18:18
 */

namespace App\Services;


class ConfigService
{
    public static function initConfig()
    {
        $config_path = root_path('config');

        if (!file_exists($config_path . '/deployer.local.php')) {
            echo "Create local config './deployer.local.php' ";
            $local_config_content = file_get_contents($config_path . '/deployer.php');
            $new_secret_key = generate_random_str(32);
            $local_config_content = str_replace(
                "'secret_key' => ''", "'secret_key' => '{$new_secret_key}'",
                $local_config_content
            );
            $res = file_put_contents($config_path . '/deployer.local.php', $local_config_content);
            if ($res) echo " Ok\n"; else die(" Failed");
        }

        if (!file_exists($config_path . '/projects.local.php')) {
            echo "Create local projects config './projects.local.php' ";
            $res = copy($config_path . '/projects.php', $config_path . '/projects.local.php');
            if ($res) echo " Ok\n"; else die(" Failed");
        }

        if (!file_exists($config_path . '/hosts.local.php')) {
            echo "Create local hosts config './hosts.local.php' ";
            $res = copy($config_path . '/hosts.php', $config_path . '/hosts.local.php');
            if ($res) echo " Ok\n"; else die(" Failed");
        }
    }
}