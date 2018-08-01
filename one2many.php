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

$hosts = [
    'prod-1'=> 'http://127.0.0.1:8001',
    'prod-2'=> 'http://127.0.0.1:8002',
];

$username = isset($_SERVER['PHP_AUTH_USER']) ? trim($_SERVER['PHP_AUTH_USER']) : '';

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

$tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
$dst_host = isset($_POST['host']) ? trim($_POST['host']) : '';

?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title>Heropoo Deployer</title>
</head>
<body>
Query Status:
<form action="" method="post" style="padding: 10px">
    <input type="hidden" name="action" value="status">
    <button type="submit">Query</button>
</form>
<hr>
Deploy Code:
<form action="" method="post" style="padding: 10px">
    <input type="text" name="tag" value="<?= $tag?>" placeholder="Please input a git tag" required>
    <select name="host">
        <option value="">All</option>
    <?php foreach ($hosts as $host =>$url):?>
        <option value="<?= $host?>" <?php if($dst_host === $host):?>selected<?php endif;?>><?=$host?></option>
    <?php endforeach;?>
    </select>
    <input type="hidden" name="username" value="$username">
    <input type="hidden" name="action" value="checkout">
    <button type="submit">Submit</button>
</form>
<hr>
<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    $path = __DIR__;    // your path
    $log_file = $path.'/runtime/deploy-error-output.log';

    error_log("User $username exec action `$action` at ".date('Y-m-d H:i:s').PHP_EOL, 3, $log_file);

    foreach($hosts as $host => $url){
        if(!empty($dst_host)){
            if($host !== $dst_host){
                continue;
            }
        }

        $data = [
            'token'=>md5(date('Y-m-d H')),
            'action'=>$action,
            'tag'=>$tag
        ];
        $res = sub_curl($url, $data);

        $res = json_decode($res, 1);

        echo $host.':<br>';
        echo '<pre>';
        var_dump($res);
        echo '</pre>';
    }
}

echo '</body></html>';

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
