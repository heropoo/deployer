<?php
/**
 * User: heropoo
 * Date: 2018/4/23
 * Time: 16:34
 */

$config = require dirname(__DIR__).'/config/app.php';

$realm = md5($config['secret_key']);

$users = $config['users'];

$projects = $config['projects'];

$hosts = $config['servers'];

$username = isset($_SERVER['PHP_AUTH_USER']) ? trim($_SERVER['PHP_AUTH_USER']) : '';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="' . $realm . '"');
    header('HTTP/1.0 401 Unauthorized');
    die('401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login</button>');
} else {
    $pwd = $config['secret_key'].trim($_SERVER['PHP_AUTH_PW']);
    if(!key_exists($username, $users) || !password_verify($pwd,  $users[$username])){
        header('WWW-Authenticate: Basic realm="' . $realm . '"');
        header('HTTP/1.0 401 Unauthorized');
        die('401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>');
    }
}

$tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
$dst_host = isset($_POST['host']) ? trim($_POST['host']) : '';
$dst_project = isset($_POST['project']) ? trim($_POST['project']) : '';

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $config['title']?></title>
    <style>
        pre {
            overflow-x: auto;
            width: 600px;
            background-color: #efefef;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<div>
    服务器查询状态
    <form action="" method="post" style="padding: 10px">
        <input type="hidden" name="action" value="status">
        项目：<select name="project">
            <?php foreach ($projects as $project =>$project_name):?>
                <option value="<?= $project?>" <?php if($dst_project === $project):?>selected<?php endif;?>><?=$project_name?></option>
            <?php endforeach;?>
        </select>
        <button type="submit">查询</button>
    </form>
</div>
<hr>
代码发布
<form action="" method="post" style="padding: 10px">
    版本:<input type="text" name="tag" value="<?= $tag?>" placeholder="请输入tag" required>
    项目:<select name="project">
        <?php foreach ($projects as $project =>$project_name):?>
            <option value="<?= $project?>" <?php if($dst_project === $project):?>selected<?php endif;?>><?=$project_name?></option>
        <?php endforeach;?>
    </select>

    机器:<select name="host">
        <option value="">全部</option>
        <?php foreach ($hosts as $host =>$url):?>
            <option value="<?= $host?>" <?php if($dst_host === $host):?>selected<?php endif;?>><?=$host?></option>
        <?php endforeach;?>
    </select>
    <input type="hidden" name="username" value="$username">
    <input type="hidden" name="action" value="checkout">
    <button type="submit">发布</button>
</form>
<hr>
<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';

    error_log("[".date('Y-m-d H:i:s')."][$dst_project][".(empty($dst_host) ? 'all' : $dst_host)."] User '$username' exec action `$action".(empty($tag) ? '' : ' '.$tag)."`".PHP_EOL, 3, $config['deployer_log_file']);

    foreach($hosts as $host => $url){
        if(!empty($dst_host)){
            if($host !== $dst_host){
                continue;
            }
        }

        $data = [
            'token'=>md5($config['secret_key'].date('Y-m-d H')),
            'action'=>$action,
            'tag'=>$tag,
            'project'=>$dst_project
        ];
        $res = sub_curl($url, $data);

        $res = json_decode($res, 1);

        echo $projects[$dst_project].' '.$host.': ';
        echo $res['data']['return_value'] === 0 ? '✔ Success' : '❌ Failed';
        echo '<pre>';
        //var_dump($res);
        echo 'success_msg：'.PHP_EOL.$res['data']['success_msg'].PHP_EOL;
        echo 'error_msg：'.PHP_EOL.$res['data']['error_msg'].PHP_EOL;
        echo '</pre>';
        echo '<p>--------------------'.date('Y-m-d H:i:s').'--------------------</p><br>';
    }
}

echo '
<p style="text-align:center;position:fixed;bottom:1rem;left:1rem;">&copy; 2018 by <a href="https://github.com/heropoo/deployer">Heropoo Deployer</a></p>
</body></html>';

function sub_curl($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
