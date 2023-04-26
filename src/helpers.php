<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 1:12 上午
 */


if (!function_exists('format_json')) {
    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return string
     */
    function format_json($code = 0, $msg = "success", $data = [])
    {
        header('Content-type:application/json;charset=utf-8');
        return json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => (object)$data
        ]);
    }
}

if (!function_exists('return_json')) {
    /**
     * @param array $data
     * @return string
     */
    function return_json($data)
    {
        header('Content-type: application/json;charset=utf-8');
        return json_encode($data);
    }
}

if (!function_exists('generate_random_str')) {
    /**
     * @param int $length
     * @return string
     */
    function generate_random_str($length)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $random_str = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $random_str .= $str[$num];
        }
        return $random_str;
    }
}

if (!function_exists('varexport')) {
    /**
     * var_export() with square brackets and indented 4 spaces.
     * @see https://www.php.net/manual/zh/function.var-export.php#122853
     * @param $expression
     * @param false $return
     * @return string
     */
    function varexport($expression, $return = FALSE)
    {
        $export = var_export($expression, TRUE);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
        $export = join(PHP_EOL, array_filter(["["] + $array));
        if ((bool)$return) return $export; else echo $export;
    }
}

if(!function_exists('copy_dir')){
    /**
     * @param string $src
     * @param string $dst
     */
    function copy_dir(string $src, string $dst)
    {
        if (!is_dir($dst)) {
            @mkdir($dst, 0755, true);
        }
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    copy_dir($src . '/' . $file, $dst . '/' . $file);
                    continue;
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}