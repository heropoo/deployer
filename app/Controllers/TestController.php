<?php
/**
 * User: nano
 * Datetime: 2022/9/27 1:54 上午
 */

namespace App\Controllers;


use App\Models\User;

class TestController
{
    public function initAction(){
        try {
            $this->up();
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function up()
    {
        $db = User::getDb();
        var_dump($db);
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