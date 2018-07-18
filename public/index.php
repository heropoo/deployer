<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/4/23
 * Time: 16:34
 */

$realm = 'heropoo-deployer';

$users = [
    'admin'=>'7e90433b5c0245d54e7632cbe90e1133',     //admin123
    'demo'=>'83a582b846c832d7d876194faf4c7593'   //demo123
];

$username = trim($_SERVER['PHP_AUTH_USER']);

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="' . md5($realm) . '"');
    header('HTTP/1.0 401 Unauthorized');
    die('401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>');
} else {
    $pwd = md5($realm.md5(trim($_SERVER['PHP_AUTH_PW'])));
    if(!key_exists($username, $users) || $pwd !== $users[$username]){
        header('WWW-Authenticate: Basic realm="' . md5($realm) . '"');
        header('HTTP/1.0 401 Unauthorized');
        die('401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>');
    }
}
echo <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title>heropoo Deployer</title>
</head>
<body>
HTML;
echo <<<HTML
<form action="" method="post" style="padding: 10px">
    <label for="">Tag：</label><input type="text" name="tag" value="" placeholder="Please input a git tag" required>
    <input type="hidden" name="username" value="$username">
    <button type="submit">Submit</button>
</form>
HTML;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
    if(empty($tag)){
        die("<script>alert('Tag is empty');window.location.reload();</script>");
    }

    //配置成你的项目目录
    $path = dirname(__DIR__);
    $log_file = $path.'/runtime/logs/deploy-'.date('Y-m-d').'.log';

    error_log("User $username deploy tag `$tag` at ".date('Y-m-d H:i:s').PHP_EOL, 3, $log_file);

    $descriptorspec = array(
        0 => array("pipe", "r"),  
        1 => array("pipe", "w"),  
        2 => array("pipe", "w") 
    );

    $cwd = $path;
    $env = array('PATH' => $_SERVER['PATH']);

    $process = proc_open("sudo git fetch && sudo git checkout $tag", $descriptorspec, $pipes, $cwd, $env);

    echo '<pre>';
    if (is_resource($process)) {
        echo stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        echo stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $return_value = proc_close($process);

        echo PHP_EOL."Command returned $return_value\n";

        error_log("Command returned $return_value".PHP_EOL, 3, $log_file);
    }
    echo '</pre>';
}
echo '</body></html>';
