<?php
/**
 * User: heropoo
 * Datetime: 2022/10/14 12:51 am
 */

namespace App\Commands;


class ProjectCommand
{
    /**
     * Organizing projects
     */
    public function tidy()
    {
        $project_config_path = \App::$instance->getConfigPath() . '/projects';
        $projects = require \App::$instance->getConfigPath() . '/projects.local.php';
        $list = [];

        foreach ($projects as $project => $project_config) {
            if (!empty($project_config['group'])) {
                $group = $project_config['group'];
            } else {
                $group = $project;
            }
            $project_config['group'] = $group;
            $list[$group][$project] = $project_config;
        }

        foreach ($list as $group => $group_projects) {
            $project_group_file = $project_config_path . '/' . $group . '.local.php';
            if (file_exists($project_group_file)) {
                $all_group_projects = require $project_group_file;
                $all_group_projects = array_merge($all_group_projects, $group_projects);
            } else {
                $all_group_projects = $group_projects;
            }
            $res = file_put_contents($project_group_file, "<?php\nreturn " . varexport($all_group_projects, true) . ';');
            echo "Tidy {$project_group_file} " . ($res ? 'OK' : 'Failed') . PHP_EOL;
        }
    }

    public function initProject()
    {
        // permission
        $rootPath = root_path();
        $paths = [
            $rootPath . '/runtime',
            $rootPath . '/config/projects',
            $rootPath . '/config/users.local.php',
            $rootPath . '/config/hosts.local.php',
        ];

        foreach ($paths as $path) {
            //$res = chmod($path, 0777);
            exec("chmod -R 777 $path", $output, $res);
            echo "chmod -R 777 $path    " . ($res === 0 ? 'OK' : 'Failed') . PHP_EOL;
        }

        //todo other
    }
}