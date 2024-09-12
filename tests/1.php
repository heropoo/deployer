<?php
/**
 * User: heropoo
 * Datetime: 2024/9/12 11:28
 */


$cmd = '/bin/sh 1.sh'; // 替换为你的脚本路径

// 定义管道描述符
$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin
    1 => array("pipe", "w"),  // stdout
    2 => array("pipe", "w")   // stderr
);

// 启动进程
$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    // 关闭 stdin，因为我们不需要写入数据
    fclose($pipes[0]);

    // 读取并显示 stdout 实时输出
    while (($line = fgets($pipes[1])) !== false) {
        echo $line;
        flush(); // 确保输出实时显示
    }
    fclose($pipes[1]);

    // 读取并显示 stderr (如果有)
    while (($line = fgets($pipes[2])) !== false) {
        echo "ERROR: $line";
        flush(); // 确保输出实时显示
    }
    fclose($pipes[2]);

    // 关闭进程
    $return_value = proc_close($process);
}


