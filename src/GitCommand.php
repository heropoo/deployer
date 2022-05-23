<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 9:11 上午
 */

namespace Deployer;

class GitCommand
{
    protected $project_path;

    public function __construct($project_path)
    {
        $this->project_path = $project_path;
    }

    public function run($command)
    {
        //echo PHP_EOL . "-> " . $this->project_path . " >> " . $command . PHP_EOL;

        $descriptorspec = [
            0 => array("pipe", "r"),    // 标准输入，子进程从此管道中读取数据
            1 => array("pipe", "w"),    // 标准输出，子进程向此管道中写入数据
            2 => array("pipe", "w")     // 标准错误
        ];

        $cwd = $this->project_path;

        $process = proc_open($command, $descriptorspec, $pipes, $cwd);

        if (is_resource($process)) {
            $success_msg = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error_msg = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $return_value = proc_close($process);

            return [
                'return_value' => $return_value,
                'success_msg' => $success_msg,
                'error_msg' => $error_msg
            ];
        }
        return [];
    }
}