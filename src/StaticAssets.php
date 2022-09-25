<?php

class StaticAssets
{
    public static $dist_path = 'public/assets';

    const install_path = [
        'jquery' => 'node_modules/jquery/dist',
        'bootstrap' => 'node_modules/bootstrap/dist',
//        'font-awesome' => 'node_modules/font-awesome',
//        'highlight.js' => 'node_modules/highlight.js/styles',
    ];

    public static function install()
    {
        echo "Install static assets:\n";
        foreach (self::install_path as $item => $src_path) {
            $dist_path = self::$dist_path . "/" . $item;
            echo "Copy '{$src_path}' to '{$dist_path}'\n";
            copy_dir($src_path, $dist_path);
        }
    }
}