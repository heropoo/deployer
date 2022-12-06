<?php
/**
 * User: heropoo
 * Datetime: 2022/12/6 10:51 下午
 */

namespace App\Services;


use App\Models\Project;

class ProjectService
{
    public static function create(array $data)
    {
        $group_project[$data['id']] = $data;
        $project_config_path = \App::$instance->getConfigPath() . '/projects';
        $project_group_file = $project_config_path . '/' . $data['group'] . '.local.php';
        $all_group_projects = [];
        if (file_exists($project_group_file)) {
            $all_group_projects = require $project_group_file;
        }
        $all_group_projects = array_merge($all_group_projects, $group_project);
        return file_put_contents($project_group_file, "<?php\nreturn " . varexport($all_group_projects, true) . ';');
    }
}