<?php
/**
 * User: heropoo
 * Datetime: 2022/12/6 10:51 下午
 */

namespace App\Services;


use App\Models\Host;

class HostService
{
    public static function create(array $data)
    {
        $config_path = root_path('config');
        $hosts_config_file = $config_path . '/hosts.local.php';
        $all_hosts_config = [];
        if (file_exists($hosts_config_file)) {
            $all_hosts_config = require $hosts_config_file;
        }
        $all_hosts_config[$data['id']] = $data;
        $res = file_put_contents($hosts_config_file, "<?php\nreturn " . var_export($all_hosts_config, true) . ";");
        if ($res) {
            return true;
        }
        return false;
    }

    public static function delete($id)
    {
        $config_path = root_path('config');
        $hosts_config_file = $config_path . '/hosts.local.php';
        $all_hosts_config = [];
        if (file_exists($hosts_config_file)) {
            $all_hosts_config = require $hosts_config_file;
        }
        unset($all_hosts_config[$id]);
        $res = file_put_contents($hosts_config_file, "<?php\nreturn " . var_export($all_hosts_config, true) . ";");
        if ($res) {
            return true;
        }
        return false;
    }
}