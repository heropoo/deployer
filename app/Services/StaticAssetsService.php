<?php
/**
 * User: nano
 * Datetime: 2023/4/26 20:42
 */

namespace App\Services;


class StaticAssetsService
{
    public static $dist_path = 'public/assets';

    const install_path = [
        'jquery' => 'node_modules/jquery/dist',
        'bootstrap' => 'node_modules/bootstrap/dist',
        'font-awesome' => 'node_modules/font-awesome',
    ];

    public static function install()
    {
        $msg = '';
        foreach (self::install_path as $item => $src_path) {
            $dist_path = self::$dist_path . "/" . $item;
            $msg .= "Copy {$src_path} to {$dist_path}\n";
            copy_dir($src_path, $dist_path);
        }
        return $msg;
    }
}