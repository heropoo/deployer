<?php
/**
 * User: heropoo
 * Date: 2018/4/23
 * Time: 16:34
 */

$config = require dirname(__DIR__) . '/src/bootstrap.php';

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
    $pwd = $config['secret_key'] . trim($_SERVER['PHP_AUTH_PW']);
    if (!key_exists($username, $users) || !password_verify($pwd, $users[$username])) {
        header('WWW-Authenticate: Basic realm="' . $realm . '"');
        header('HTTP/1.0 401 Unauthorized');
        die('401 Unauthorized' . '<br> <button onclick="window.location.reload();">Login Again</button>');
    }
}

$tag = isset($_POST['tag']) ? trim($_POST['tag']) : ''; //master && sudo git pull --recurse-submodules
$dst_host = isset($_POST['host']) ? trim($_POST['host']) : '';
$dst_project = isset($_POST['project']) ? trim($_POST['project']) : '';

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 引入 Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shiv 和 Respond.js 用于让 IE8 支持 HTML5元素和媒体查询 -->
    <!-- 注意： 如果通过 file://  引入 Respond.js 文件，则该文件无法起效果 -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <title><?= $config['title'] ?></title>
    <style>
        .main{
            padding: 2rem 4rem;
        }
        .form-control{margin-right: 10px}
        /*pre {*/
        /*    overflow-x: auto;*/
        /*    width: 600px;*/
        /*    background-color: #efefef;*/
        /*    padding: 5px 10px;*/
        /*}*/
    </style>
</head>
<body>
<div class="main container-fluid">
    <div class="row">
        <h2>服务器查询状态</h2>
        <form action="" method="post" class="form form-inline">
            <input type="hidden" name="action" value="status">
            <div class="form-group">
                <label for="">项目:</label>
                <select name="project" class="form-control" style="width: 20rem" required>
                    <option value="">请选择</option>
                    <?php foreach ($projects as $project => $project_name): ?>
                        <option value="<?= $project ?>"
                                <?php if ($dst_project === $project): ?>selected<?php endif; ?>><?= $project_name ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-info">查询</button>
            </div>
        </form>
        <hr>
    </div>

    <div class="row">
        <h2>代码发布</h2>
        <form action="" method="post" class="form form-inline" id="publishForm">
            <div class="form-group">
                <label for="">版本:</label>
                <input class="form-control" type="text" name="tag" value="<?= $tag ?>" style="width: 32rem;" placeholder="请输入tag、commit_id 或者 分支" required>
            </div>
            <div class="form-group">
                <label for="">项目:</label>
                <select name="project" class="form-control" required>
                    <option value="">请选择</option>
                    <?php foreach ($projects as $project => $project_name): ?>
                        <option value="<?= $project ?>"
                                <?php if ($dst_project === $project): ?>selected<?php endif; ?>><?= $project_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">机器:</label>
                <select name="host" class="form-control">
                    <option value="">全部</option>
                    <?php foreach ($hosts as $host => $url): ?>
                        <option value="<?= $host ?>" <?php if ($dst_host === $host): ?>selected<?php endif; ?>><?= $host ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for=""></label>
                <button type="submit" class="btn btn-primary">发布</button>
            </div>

            <input type="hidden" name="action" value="checkout">

        </form>
        <hr>
    </div>

    <div class="row">
        <h2>快速代码发布</h2>
        <span class="help-block">快速使用主分支代码发布</span>
        <form action="" method="post" class="form form-inline" id="fastPublishForm">
            <input type="hidden" name="tag" value="master && sudo git pull --recurse-submodules">
            <div class="form-group">
                <label for="">项目:</label>
                <select name="project" class="form-control" required>
                    <option value="">请选择</option>
                    <?php foreach ($projects as $project => $project_name): ?>
                        <option value="<?= $project ?>"
                                <?php if ($dst_project === $project): ?>selected<?php endif; ?>><?= $project_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">机器:</label>
                <select name="host" class="form-control">
                    <option value="">全部</option>
                    <?php foreach ($hosts as $host => $url): ?>
                        <option value="<?= $host ?>" <?php if ($dst_host === $host): ?>selected<?php endif; ?>><?= $host ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for=""></label>
                <button type="submit" class="btn btn-primary">发布</button>
            </div>

            <input type="hidden" name="action" value="checkout">

        </form>
        <hr>
    </div>

    <div class="row">
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';

    error_log("[" . date('Y-m-d H:i:s') . "][$dst_project][" . (empty($dst_host) ? 'all' : $dst_host) . "] User '$username' exec action `$action" . (empty($tag) ? '' : ' ' . $tag) . "`" . PHP_EOL, 3, $config['deployer_log_file']);

    foreach ($hosts as $host => $url) {
        if (!empty($dst_host)) {
            if ($host !== $dst_host) {
                continue;
            }
        }

        $data = [
            'token' => md5($config['secret_key'] . date('Y-m-d H')),
            'action' => $action,
            'tag' => $tag,
            'project' => $dst_project
        ];
        $res = sub_curl($url, $data);

        error_log("{$projects[$dst_project]}' => '$host': ' res:". $res . PHP_EOL, 3, $config['deployer_log_file']);

        $res = json_decode($res, 1);

        echo $projects[$dst_project] . ' => ' . $host . ': ';
        echo $res['data']['return_value'] === 0 ? '✔ Success' : '❌ Failed '.$res['msg'];
        echo '<br /><br />';
        if (strlen($res['data']['success_msg']) > 0) {
            echo '<div>output: <pre>' . PHP_EOL . $res['data']['success_msg'] . '</pre></div>';
        }
        if (strlen($res['data']['error_msg']) > 0) {
            echo '<div>error message: <pre>' . PHP_EOL . $res['data']['error_msg'] . '</pre></div>';
        }
        echo '<p>--------------------' . date('Y-m-d H:i:s') . '--------------------</p>';
    }
}

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
?>
    </div>
</div>
<p style="text-align:center;position:fixed;bottom:1rem;left:1rem;">&copy; 2018 - <?= date('Y')?> <a href="https://github.com/heropoo/deployer">Deployer</a></p>
<!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
<script src="https://code.jquery.com/jquery.js"></script>
<!-- 包括所有已编译的插件 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
