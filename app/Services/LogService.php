<?php
/**
 * User: heropoo
 * Datetime: 2023/5/25 11:13
 */

namespace App\Services;


class LogService
{
    public static function gc($log_file){
        //todo 日志滚动
        return;
        if(mt_rand(0, 10) != 10){
            //return false;
        }
        if (file_exists($log_file)) {
            $lines = file($log_file);
            $lines_count = count($lines);
            $cut_count = $lines_count - 10;
            if($cut_count > 0){

            }
        }
    }
}