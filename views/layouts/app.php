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
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="/assets/jquery/jquery.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
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
            <li role="presentation" class="pull-left<?= $path_info == '/' ? ' active' : ''?>" ><a href="/">工作台</a></li>
            <li role="presentation" class="pull-left<?= strpos($path_info, '/logs') === 0 ? ' active' : ''?>" ><a href="/logs">查看日志</a></li>
            <li role="presentation" class="dropdown pull-right">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <?= App::get('user')->username?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="">修改密码</a></li>
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