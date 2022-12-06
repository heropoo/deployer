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

    public static function delete($id)
    {
        $deployer_config = config('load');
        $projects_config = $deployer_config['projects'];
        $current_project_config = $projects_config[$id];

        $project_config_path = \App::$instance->getConfigPath() . '/projects';
        if (!empty($current_project_config['group'])) {
            $project_group_file = $project_config_path . '/' . $current_project_config['group'] . '.local.php';
        } else {
            $project_group_file = \App::$instance->getConfigPath() . '/projects.local.php';
        }
        $all_group_projects = [];
        if (file_exists($project_group_file)) {
            $all_group_projects = require $project_group_file;
        }
        unset($all_group_projects[$id]);
        if ($all_group_projects) {
            return file_put_contents($project_group_file, "<?php\nreturn " . varexport($all_group_projects, true) . ';');
        }
        if ($project_group_file != \App::$instance->getConfigPath() . '/projects.local.php') {
            @unlink($project_group_file);
        }
        return true;
    }
}