<?php
/**
 * User: nano
 * Datetime: 2023/5/31 18:18
 */

namespace App\Services;


use phpseclib3\Crypt\RSA;

class ConfigService
{
    public static function initConfig()
    {
        $config_path = root_path('config');

        if (!file_exists($config_path . '/deployer.local.php')) {
            echo "Create local config './deployer.local.php' ";
            $local_config_content = file_get_contents($config_path . '/deployer.php');
            $new_secret_key = generate_random_str(32);

            $key_res = self::createSSHKey();

            $local_config_content = str_replace(
                [
                    "'secret_key' => ''",
                    "'private_key' => ''",
                    "'public_key' => ''"
                ],
                [
                    "'secret_key' => '{$new_secret_key}'",
                    "'private_key' => '{$key_res['private']}'",
                    "'public_key' => '{$key_res['public']}'",
                ],
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

    protected static function createSSHKey()
    {
        $private = RSA::createKey();
        $public = $private->getPublicKey();
        $publicKeyStr = $public->__toString();
        $publicKeyStr = str_replace("-----BEGIN PUBLIC KEY-----", '', $publicKeyStr);
        $publicKeyStr = str_replace("-----END PUBLIC KEY-----", '', $publicKeyStr);
        $publicKeyStr = str_replace("\r\n", '', $publicKeyStr);
        $publicKeyStr = 'ssh-rsa '.$publicKeyStr;
        return [
            'private' => $private->__toString(),
            'public' => $publicKeyStr,
        ];
    }
}