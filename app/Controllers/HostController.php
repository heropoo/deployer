<?php
/**
 * User: nano
 * Datetime: 2023/5/31 19:08
 */

namespace App\Controllers;


use App\Models\Host;
use App\Services\HostService;
use Moon\Request\Request;

class HostController
{
    public function indexAction()
    {
        $deployerConfig = config('load');
        $hosts = $deployerConfig['hosts'];
        return view('hosts', [
            'hosts' => $hosts
        ], 'layouts/app')->setTitle($deployerConfig['title']);
    }

    public function createAction(Request $request)
    {
        $data = $request->all();
        $host_config = [];
        foreach (Host::FIELDS as $field) {
            $data[$field] = trim($data[$field]);
            if (empty($data[$field])) {
                return ["code" => 400, "message" => "Parameter '{$field}' is empty"];
            }
            $host_config[$field] = trim($data[$field]);
        }
        $res = HostService::create($host_config);
        if (!$res) {
            return ["code" => 500, "message" => "Failed"];
        }
        return ["code" => 0, 'message' => "Success"];
    }

    public function deleteAction(Request $request)
    {
        $id = trim($request->get('id'));
        if (strlen($id) == 0) {
            return ["code" => 400, "message" => "Parameter error"];
        }

        $res = HostService::delete($id);
        if (!$res) {
            return ["code" => 500, "message" => "Failed"];
        }
        return ["code" => 0, 'message' => "Success"];
    }
}