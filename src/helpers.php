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