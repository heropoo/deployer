<?php
/**
 * User: nano
 * Datetime: 2022/9/25 12:39 下午
 */

namespace App\Commands;


class DeployerCommand
{
    public function init(){
        \StaticAssets::install();
    }
}