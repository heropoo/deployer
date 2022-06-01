<?php
/**
 * 用户密码生成工具
 * User: heropoo
 * Date: 2018/12/21
 * Time: 12:21
 */

$config = require __DIR__ . '/src/bootstrap.php';

$config_path = __DIR__ . '/config';

if (!file_exists($config_path . '/app.local.php')) {
    echo "Create local config './app.local.php' ";
    $local_config_content = file_get_contents($config_path . '/app.php');
    $new_secret_key = generate_random_str(32);
    $local_config_content = str_replace(
        "'secret_key' => ''", "'secret_key' => '{$new_secret_key}'",
        $local_config_content
    );
    $res = file_put_contents($config_path . '/app.local.php', $local_config_content);
    if ($res) echo " Ok\n"; else die(" Failed");
}

$config = require __DIR__ . '/src/bootstrap.php';

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

$users_config_file = __DIR__ . '/config/user.local.php';
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