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
        $deployerConfig = config('load');
        $projects = $deployerConfig['projects'];
        return view('index', [
            'projects' => $projects
        ], 'layouts/app')->setTitle($deployerConfig['title']);
    }

    public function publish(Request $request)
    {
        $server = $request->server;
        $username = isset($server['PHP_AUTH_USER']) ? trim($server['PHP_AUTH_USER']) : '';
        $deployerConfig = config('load');
        $projects = $deployerConfig['projects'];

        $action = isset($_POST['action']) ? trim($_POST['action']) : '';
        $dst_project = isset($_POST['project']) ? trim($_POST['project']) : '';

        $service = new PublishService($deployerConfig);
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
                3, $deployerConfig['deployer_log_file']);
        }

        return $res;
        //echo return_json($res);exit;
    }

    public function logs(Request $request)
    {
        $project = $request->get('project');
        $user = $request->get('user');

        $lines = [];
        $deployerConfig = config('load');
        $log_file = $deployerConfig['deployer_log_file'];
        if (file_exists($log_file)) {
            $lines = file($log_file);
            $lines = array_reverse($lines);
        }

        $dstLines = [];
        foreach ($lines as $line) {
            $line = json_decode($line, 1);
            if ($project && $project !== $line['project']) {
                continue;
            }
            if ($user && $user !== $line['user']) {
                continue;
            }
            $dstLines[] = $line;
        }

        return view('logs', ['lines' => $dstLines], 'layouts/app')->setTitle($deployerConfig['title']);
    }

    public function diff(Request $request)
    {
        $beforeCommitId = $request->get('before');
        $afterCommitId = $request->get('after');;
        $hostId = $request->get('host');;
        $projectId = $request->get('project');;
        //var_dump($beforeCommitId, $afterCommitId, $hostId);
        $deployerConfig = config('load');

        $service = new PublishService($deployerConfig);
        $res = $service->showDiff($projectId, $hostId, $beforeCommitId, $afterCommitId);
        //return $res['stdout'];
        return view('diff', [
            'res' => $res,
        ], 'layouts/app')->setTitle($deployerConfig['title']);
    }
}