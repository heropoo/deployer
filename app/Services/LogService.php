<?php
/**
 * User: heropoo
 * Datetime: 2023/5/25 11:13
 */

namespace App\Services;


class LogService
{
    public static function gc($log_file)
    {
        if (mt_rand(0, 10) != 10) {
            return false;
        }
        if (file_exists($log_file)) {
            ftruncatestart($log_file, 1 * 1024 * 1024); // 1MB
        }
    }


}

/**
 * @param $filename
 * @param $maxfilesize
 * @see https://www.php.net/manual/zh/function.ftruncate.php#103591
 */
function ftruncatestart($filename, $maxfilesize)
{
    $size = filesize($filename);
    if ($size < $maxfilesize * 1.0) return;
    $maxfilesize = $maxfilesize * 0.5; //we don't want to do it too often...
    $fh = fopen($filename, "r+");
    $start = ftell($fh);
    fseek($fh, -$maxfilesize, SEEK_END);
    $drop = fgets($fh);
    $offset = ftell($fh);
    for ($x = 0; $x < $maxfilesize; $x++) {
        fseek($fh, $x + $offset);
        $c = fgetc($fh);
        fseek($fh, $x);
        fwrite($fh, $c);
    }
    ftruncate($fh, $maxfilesize - strlen($drop));
    fclose($fh);
}