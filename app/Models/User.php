<?php


namespace App\Models;


use Moon\Db\Table;

/**
 * Class User
 * @property string $username
 * @property string $password
 * @property string $create_time
 * @property string $update_time
 * @package App\Models
 */
class User extends Table
{
    protected $primaryKey = 'id';

    public static function getDb()
    {
        return \App::get('db');
    }

    public static function tableName()
    {
        return 'user';
    }
}