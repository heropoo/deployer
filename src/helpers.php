<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 1:12 上午
 */

if(!function_exists('generate_random_str')){
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