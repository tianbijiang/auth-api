<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class UserRoles extends Model
{
    protected $connection = 'mysql_code_access';
    protected $table = 'micor_authapi.users_roles';
    //protected $primaryKey = 'role';

    public function changeConnection($connectionName, $user, $pass)
    {
        $username_prefix = Config::get('config.username_prefix_for_user');

        $this->connection = $connectionName;

        Config::set('database.connections.' . $connectionName . '.username', $username_prefix . $user);
        Config::set('database.connections.' . $connectionName . '.password', $pass);
    }
}
