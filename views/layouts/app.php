<?php
/**
 * @var \Moon\View $this
 */

$path_info = App::get('request')->getPathInfo();

?><!Doctype html>
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
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title><?= $this->title ?></title>
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
    </style>
</head>
<body>
<div class="header">
    <div class="nav-bar">
        <ul class="nav nav-pills">
            <li role="presentation" <?= $path_info == '/' ? 'class="active"' : ''?> ><a href="/">工作台</a></li>
            <li role="presentation" <?= $path_info == '/projects' ? 'class="active"' : ''?> ><a href="/projects">项目列表</a></li>
            <li role="presentation" class="pull-left<?= strpos($path_info, '/logs') === 0 ? ' active' : ''?>" ><a href="/logs">查看日志</a></li>

            <li role="presentation" class="dropdown pull-right">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <?= App::get('user')->username ?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/user/cpwd">修改密码</a></li>
                    <li><a href="/user/logout">退出</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="main">
    <?= $content?>
</div>
<p style="text-align:center;position:fixed;bottom:1rem;left:1rem;">&copy; <?= date('Y')?> <a href="https://github.com/heropoo/deployer">Deployer</a> <?= App::version()?></p>
</body>
</html>