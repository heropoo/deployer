<?php
/**
 * User: heropoo
 * Datetime: 2022/7/1 1:45 上午
 */

namespace App\Controllers;


use Moon\Request\Request;

class IndexController
{
    public function index(Request $request){
        $session = $request->getSession();
        $session->destroy();
        return 'index';
    }
}