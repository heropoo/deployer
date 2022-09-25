<?php
/**
 * User: nano
 * Datetime: 2022/9/25 12:39 下午
 */

namespace App\Commands;


use App\Models\User;

class DeployerCommand
{
    public function init()
    {
        \StaticAssets::install();

        try {
            $this->up();
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function up()
    {
        $db = User::getDb();
        $content = file_get_contents(__DIR__ . '/../../docs/sqlite/up.sql');
        $sqls = explode(';', $content);
        echo "sql exe begin\n";
        foreach ($sqls as $sql) {
            //echo $sql;
            $res = $db->execute($sql);
            var_dump($res);
        }
        echo "sql exe end\n";
    }
}