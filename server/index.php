<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/7/23
 * Time: 10:58
 */

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

$config = require dirname(__DIR__).'/config/app.php';

date_default_timezone_set('Asia/Shanghai');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $token = isset($_POST['token']) ? trim($_POST['token']) : '';
    if ($token !== md5($config['secret_key'].date('Y-m-d H'))) {
        return_json(1, 'error token');
    }

    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    if (empty($action)) {
        return_json(1, 'empty action');
    }

    $project = isset($_POST['project']) ? trim($_POST['project']) : '';
    if (empty($project)) {
        return_json(1, 'empty project');
    }

    if ($action == 'status') {    // git status
        $res = git_status($project);
        return_json(0, 'git status', $res);
    } else if ($action == 'checkout') {    //git checkout tag_name
        $tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
        if (empty($tag)) {
            return_json(1, 'empty tag');
        }
        $res = git_checkout($tag, $project);
        return_json(0, 'git checkout ' . $tag, $res);
    }
}

function git_status($project)
{
    return git_execute(["git status"], $project);
}

function git_checkout($tag, $project)
{
    return git_execute(["git fetch", "git checkout $tag"], $project);
}

function git_execute(array $cmds, $project)
{
    global $config;

    $current_server = $config['current_server'];
    $current_server_user = $config['server_users'][$current_server];
    $cmd = '';
    foreach ($cmds as $cmd_item){
        $cmd .= "&& sudo -u $current_server_user ".$cmd_item;
    }
    $cmd = substr($cmd, 3);

    $path = $config['project_paths'][$project];
    $log_file = $config['server_log_file'];

    error_log("[".date('Y-m-d H:i:s')."] Deploy execute `$cmd` in `$path`", 3, $log_file);

    $descriptorspec = array(
        0 => array("pipe", "r"),  // 标准输入，子进程从此管道中读取数据
        1 => array("pipe", "w"),  // 标准输出，子进程向此管道中写入数据
        2 => array("pipe", "w") // 标准错误
    );

    $cwd = $path;

    $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);

    if (is_resource($process)) {
        $success_msg = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error_msg = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $return_value = proc_close($process);

        //echo PHP_EOL."Command returned $return_value\n";

        if ($return_value === 0) {
            //echo PHP_EOL."Command returned success.";
            error_log("Command returned success." . PHP_EOL, 3, $log_file);
        } else {
            //echo PHP_EOL."Command returned failed ".$return_value;
            error_log("Command returned failed " . $return_value . ' ' . $error_msg . PHP_EOL, 3, $log_file);
        }
        return [
            'return_value' => $return_value,
            'success_msg' => $success_msg,
            'error_msg' => $error_msg
        ];
    }
    return [];
}

function return_json($code, $msg, $data = [])
{
    header('Content-type: application/json;charset=utf-8');
    echo json_encode(['code' => $code, 'msg' => $msg, 'data' => $data]);
    die();
}
