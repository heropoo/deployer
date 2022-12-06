<?php


namespace App\Controllers;


use App\Models\Project;
use App\Services\ProjectService;
use Moon\Request\Request;

class ProjectController
{
    public function createAction(Request $request)
    {
        $data = $request->all();
        $project_config = [];
        foreach (Project::FIELDS as $field) {
            $data[$field] = trim($data[$field]);
            if (empty($data[$field])) {
                return ["code" => 400, "message" => "Parameter '{$field}' is empty"];
            }
            $project_config[$field] = trim($data[$field]);
            if ($field == 'hosts') {
                $project_config[$field] = explode(',', $data[$field]);
            }
        }
        $res = ProjectService::create($project_config);
        if (!$res) {
            return ["code" => 500, "message" => "Failed"];
        }
        return ["code" => 0, 'message' => "Success"];
    }

    public function deleteAction(Request $request){
        $id = trim($request->get('id'));
        if (strlen($id) == 0) {
            return ["code" => 400, "message" => "Parameter error"];
        }

        $res = ProjectService::delete($id);
        if (!$res) {
            return ["code" => 500, "message" => "Failed"];
        }
        return ["code" => 0, 'message' => "Success"];
    }
}