<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 1:12 上午
 */

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

if (!function_exists('return_json')) {
    function return_json($code, $msg, $data = [])
    {
        header('Content-type: application/json;charset=utf-8');
        echo json_encode(['code' => $code, 'msg' => $msg, 'data' => (object)$data]);
        die();
    }
}

if (!function_exists('curl_post')) {
    function curl_post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

