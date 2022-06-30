<?php
$lines = [];
$log_file = $config['deployer_log_file'];
if(file_exists($log_file)){
    $lines = file($log_file);
    $lines = array_reverse($lines);
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
        .nav-bar{
            border-bottom: 1px solid #ccc;
            padding: 1rem 2rem;
        }
        .main{
            padding: 2rem 4rem;
        }
        .form-control{margin-right: 10px}
        .result-message{font-size: 2rem}
        .project{
            min-width: 20rem
        }
        .logs-list li{
            padding: 1rem 0;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="nav-bar">
        <ul class="nav nav-pills">
            <li role="presentation"><a href="/">工作台</a></li>
            <li role="presentation"class="active"><a href="/logs">查看日志</a></li>
        </ul>
    </div>
</div>
<div class="main container-fluid">
    <div class="row">
        <ul class="logs-list">
    <?php foreach ($lines as $line):?>
        <li><?= $line?></li>
    <?php endforeach;?>
        </ul>
    </div>
</div>
<p style="text-align:center;position:fixed;bottom:1rem;left:1rem;">&copy; 2018 - <?= date('Y')?> <a href="https://github.com/heropoo/deployer">Deployer</a> v<?= App::VERSION?></p>
<script src="https://code.jquery.com/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>

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
