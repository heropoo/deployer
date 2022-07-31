<?php


namespace App\Commands;


class UserCommand
{
    public function run()
    {
        $rootPath = root_path();
//        $config = require $rootPath. '/config/load.php';

        $config_path = $rootPath . '/config';

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

        $config = require $rootPath . '/config/load.php';

        $stdin = fopen("php://stdin", "r");
        $s = "Input username: ";
        fwrite($stdin, $s, strlen($s));

        $username = trim(fgets($stdin));
        //echo $username."\n";

        $s = "Input password: ";
        fwrite($stdin, $s, strlen($s));

        $password = trim(fgets($stdin));
        //echo $password."\n";

        $pwd = password_hash($config['secret_key'] . $password, PASSWORD_DEFAULT);

        $users_config_file = $rootPath . '/config/users.local.php';
        if (file_exists($users_config_file)) {
            $users = require $users_config_file;
        }

        if (empty($users) || !is_array($users)) {
            $users = [];
        }

        $users[$username] = $pwd;

        $res = file_put_contents($users_config_file, "<?php\nreturn " . var_export($users, true) . ";");
        if ($res) {
            echo "success\n";
        } else {
            echo "failed\n";
        }

    }
}