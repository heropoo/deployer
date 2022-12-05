<?php
/**
 * User: heropoo
 * Datetime: 2022/12/5 11:39 下午
 */

namespace App\Services;


class UserService
{
    public static function changePassword($username, $password, $old_password)
    {
        $config = config('load');

        $secret_key = $config['secret_key'];

        $password_hash = password_hash($secret_key . $password, PASSWORD_DEFAULT);

        $users = $config['users'];

        if (empty($users) || !is_array($users)) {
            $users = [];
        }
        if (!isset($users[$username]) || !password_verify($secret_key . $old_password, $users[$username])) {
            return 'Old Password Error';
        }

        $users[$username] = $password_hash;

        $users_config_file = root_path() . '/config/users.local.php';

        return file_put_contents($users_config_file, "<?php\nreturn " . var_export($users, true) . ";");
    }
}