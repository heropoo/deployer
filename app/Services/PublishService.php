<?php
/**
 * User: heropoo
 * Datetime: 2022/6/12 10:39 下午
 */

namespace App\Services;


use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class PublishService
{
    protected $config;
    protected $cmd;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public static function connect($host, $port, $username, $private_key, $key_password = false)
    {
        $key = PublicKeyLoader::load($private_key, $key_password);
        $ssh = new SSH2($host, $port);
        if (!$ssh->login($username, $key)) {
            throw new \Exception("Login {$username}@{$host}:{$port} failed");
        }
        return $ssh;
    }

    public function publish($project, $action)
    {
        $private_key = $this->config['private_key'];
        $project_config = $this->config['projects'][$project];
        if ($action == 'fast_publish') {
            $cmd = "cd {$project_config['path']}"
                ." && echo 'before-commit-id: ' &&  git log --pretty=%H"
                ." && git reset --hard FETCH_HEAD"
                ." && git fetch && git checkout {$project_config['branch']}"
                ." && git pull --recurse-submodules"
                ." && echo 'before-commit-id: ' && git log --pretty=%H -1 > .after-commit-id";
            $msg_prefix = "git checkout files of project '{$project_config['name']}' on host ";
        } else if ($action == 'status') {
            $cmd = "cd {$project_config['path']} && git show --stat && git status";
            $msg_prefix = "git status of project '{$project_config['name']}' on host ";
        } else {
            //throw new \Exception("Unknown action '{$action}'");
            return [
                [
                    "msg" => "Unknown action '{$action}'",
                    "code" => -1,
                    "stdout" => '',
                    "stderr" => '',
                ]
            ];
        }
        $this->cmd = $cmd;
        $data = [];
        foreach ($project_config['hosts'] as $host_id) {
            $host_config = $this->config['hosts'][$host_id];

            $exist_status = -1;
            $stdout = $stderr = '';

            $msg = $msg_prefix . "'{$host_config['name']}'";

            try {
                $ssh = static::connect($host_config['host'], $host_config['port'], $host_config['user'], $private_key);
                $ssh->enableQuietMode();
                $stdout = $ssh->exec($cmd);
                $stderr = $ssh->getStdError();
                $exist_status = $ssh->getExitStatus();
                $ssh->disconnect();
            } catch (\Exception $e) {
                $msg .= " error: " . $e->getMessage();
            }

            $data[] = [
                "msg" => $msg,
                "code" => $exist_status,
                "stdout" => "> " . $cmd . PHP_EOL . $stdout,
                "stderr" => $stderr,
            ];
        }
        return $data;
    }

    public function getExecutedCommand()
    {
        return $this->cmd;
    }
}