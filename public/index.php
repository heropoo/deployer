<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <title>>Heropoo Deployer</title>
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container"><?php
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

$username = isset($_SERVER['PHP_AUTH_USER']) ? trim($_SERVER['PHP_AUTH_USER']) : '';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="' . md5($realm) . '"');
    header('HTTP/1.0 401 Unauthorized');
    echo '<p class="bg-warning" style="padding: 20px;margin-top: 30px;">401 Unauthorized</p><button class="btn btn-primary" onclick="window.location.reload();">Login Again</button>';
    goto end;
} else {
    $pwd = md5($realm.md5(trim($_SERVER['PHP_AUTH_PW'])));
    if(!key_exists($username, $users) || $pwd !== $users[$username]){
        header('WWW-Authenticate: Basic realm="' . md5($realm) . '"');
        header('HTTP/1.0 401 Unauthorized');
        echo '<p class="bg-warning" style="padding: 20px;margin-top: 30px;">401 Unauthorized</p><button class="btn btn-primary" onclick="window.location.reload();">Login Again</button>';
        goto end;
    }
}

echo <<<HTML
<form action="" method="post" style="padding: 10px" class="form form-inline">
    <div class="form-group">
        <label for="">Tag：</label>
        <input type="text" class="form-control" name="tag" value="" placeholder="Please input a git tag" required>
        <input type="hidden" name="username" value="$username" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
HTML;


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
    if(empty($tag)){
        echo"<script>alert('Tag is empty');window.location.reload();</script>";
        goto end;
    }

    //配置成你的项目目录
    $path = dirname(__DIR__);
    $log_file = $path.'/runtime/logs/deploy-'.date('Y-m-d').'.log';

    error_log("User `$username` deploy tag `$tag` at ".date('Y-m-d H:i:s'), 3, $log_file);

    $descriptorspec = array(
        0 => array("pipe", "r"),  
        1 => array("pipe", "w"),  
        2 => array("pipe", "w") 
    );

    $cwd = $path;
    $env = array('PATH' => $_SERVER['PATH']);

    $process = proc_open("sudo git fetch && sudo git checkout $tag", $descriptorspec, $pipes, $cwd, $env);

     echo '<pre class="git-output">';
    if (is_resource($process)) {
        echo stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        echo $error_msg = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $return_value = proc_close($process);

        if($return_value === 0){
            echo PHP_EOL."Command returned success.";
            error_log(" Command returned success.".PHP_EOL, 3, $log_file);
        }else{
            echo PHP_EOL."Command returned failed ".$return_value;
            error_log(" Command returned failed ".$return_value. ' '.$error_msg.PHP_EOL, 3, $log_file);
        }
    }
    echo '</pre>';
}
end:
?></div>
</body></html>
