<?php
/**
 * User: heropoo
 * Datetime: 2022/7/1 1:45 上午
 */

namespace App\Controllers;


use App\Services\PublishService;
use Moon\Request\Request;

class IndexController
{
    public function index(Request $request)
    {
        //$session = $request->getSession();
        //$session->destroy();
        $deployer_config = config('load');
        $projects = $deployer_config['projects'];
        return view('index', [
            'projects' => $projects
        ], 'layouts/app')->setTitle($deployer_config['title']);
    }

    public function publish(Request $request)
    {
        $server = $request->server;
        $username = isset($server['PHP_AUTH_USER']) ? trim($server['PHP_AUTH_USER']) : '';
        $config = config('load');
        $projects = $config['projects'];

        $action = isset($_POST['action']) ? trim($_POST['action']) : '';
        $dst_project = isset($_POST['project']) ? trim($_POST['project']) : '';

        $service = new PublishService($config);
        $res = $service->publish($dst_project, $action);

        $cmd = $service->getExecutedCommand();

        if ($action == 'fast_publish') {
            $log_data = [
                'time' => date('Y-m-d H:i:s'),
                'project_id' => $dst_project,
                'project' => $projects[$dst_project]['name'],
                'user' => $username,
                'cmd' => $cmd,
                'data' => $res
            ];
            error_log(json_encode($log_data, JSON_UNESCAPED_UNICODE) . PHP_EOL,
                3, $config['deployer_log_file']);
        }

        return $res;
        //echo return_json($res);exit;
    }

    public function logs(Request $request)
    {
        $lines = [];
        $deployer_config = config('load');
        $log_file = $deployer_config['deployer_log_file'];
        if (file_exists($log_file)) {
            $lines = file($log_file);
            $lines = array_reverse($lines);
        }

        return view('logs', [
            'lines' => $lines,
        ], 'layouts/app')->setTitle($deployer_config['title']);
    }

    public function diff(Request $request){

    }
}