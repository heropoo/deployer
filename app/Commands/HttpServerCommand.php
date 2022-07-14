<?php


namespace App\Commands;


class HttpServerCommand
{
    public function run($addr = null, $documentRoot = null)
    {
        $addr = !is_null($addr) ?: '127.0.0.1:8000';
        $documentRoot = !is_null($documentRoot) ?: public_path();
        echo "Start a http server at http://{$addr}\n";
        exec("php -S $addr -t $documentRoot");
    }
}