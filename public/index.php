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

$dst_project = isset($_POST['project']) ? trim($_POST['project']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';

//    error_log("[" . date('Y-m-d H:i:s') . "][$dst_project][" . (empty($dst_host) ? 'all' : $dst_host) . "] User '$username' exec action `$action" . (empty($tag) ? '' : ' ' . $tag) . "`" . PHP_EOL, 3, $config['deployer_log_file']);

    $service = new \Deployer\PublishService($config);
    $res = $service->publish($dst_project, $action);

    $cmd = $service->getExecutedCommand();

    if($action == 'fast_publish'){
        error_log("[" . date('Y-m-d H:i:s') . "][$dst_project][$username] User '$username'"
            ." publish project '{$projects[$dst_project]['name']}',"
            ." executed command: `$cmd`, result: ".json_encode($res, JSON_UNESCAPED_UNICODE)
            . PHP_EOL
            , 3, $config['deployer_log_file']
        );
    }

    echo return_json($res);exit;
}

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
        .result-message{font-size: 2rem}
        .project{
            min-width: 20rem
        }
    </style>
</head>
<body>
<div class="main container-fluid">
    <div class="row">
        <h2>服务器查询状态</h2>
        <form action="" method="post" class="form form-inline" id="queryForm">
            <input type="hidden" name="action" value="status">
            <div class="form-group">
                <label for="">项目:</label>
                <select name="project" class="form-control project" required>
                    <option value="">请选择</option>
                    <?php foreach ($projects as $project_id => $project): ?>
                        <option value="<?= $project_id ?>"
                                <?php if ($dst_project === $project_id): ?>selected<?php endif; ?>><?= $project['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for=""></label>
                <button type="submit" class="btn btn-info">查询</button>
            </div>
        </form>
        <hr>
    </div>

    <div class="row">
        <h2>快速代码发布</h2>
        <span class="help-block">快速使用主分支代码发布</span>
        <form action="" method="post" class="form form-inline" id="fastPublishForm">
<!--            <input type="hidden" name="tag" value="fast_publish">-->
            <div class="form-group">
                <label for="">项目:</label>
                <select name="project" class="form-control project" required>
                    <option value="">请选择</option>
                    <?php foreach ($projects as $project_id => $project): ?>
                        <option value="<?= $project_id ?>"
                                <?php if ($dst_project === $project_id): ?>selected<?php endif; ?>><?= $project['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for=""></label>
                <button type="submit" class="btn btn-primary">发布</button>
            </div>

            <input type="hidden" name="action" value="fast_publish">

        </form>
        <hr>
    </div>

    <div class="row" id="result" style="margin-bottom: 4rem;"></div>

</div>
<p style="text-align:center;position:fixed;bottom:1rem;left:1rem;">&copy; 2018 - <?= date('Y')?> <a href="https://github.com/heropoo/deployer">Deployer</a> v<?= \Deployer\App::VERSION?></p>
<script src="https://code.jquery.com/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    $("#queryForm").submit(function(){
        var data = $(this).serialize();
        $("#result").html("Loading");
        $.post("", data, function(res){
            show_result(res)
        }, 'json');
        return false;
    });
    $("#fastPublishForm").submit(function(){
        var data = $(this).serialize();
        $("#result").html("Loading");
        $.post("", data, function(res){
            show_result(res)
        }, 'json');
        return false;
    })

    function show_result(res){
        let tpl = '';
        for (var i=0; i < res.length; i++){
            //console.log(i);
            tpl += '<div>';
            var item = res[i];
            if (item.code === 0) {
                tpl += "<div class=\"result-message\">"+item.msg+"  ✔️ Success </div>";
            }else{
                tpl += "<div class=\"result-message\">"+item.msg+"  ❌ Failed </div>";
            }
            tpl += "<div>output: <pre>" + item.stdout + "</pre>"
                + "<div>error: <pre>" + item.stderr + "</pre>"
            tpl += '</div>';
        }
        //console.log(tpl);

        $("#result").html(tpl);
    }
</script>
</body>
</html>