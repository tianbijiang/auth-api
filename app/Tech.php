<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Tech extends Model
{
    protected $connection = 'mysql_micor_admin';
    protected $table = 'micor_technician.technician_profile';
    protected $primaryKey = 'user_id';

    public function changeConnection($connectionName, $user, $pass)
    {
        $username_prefix = Config::get('config.username_prefix_for_user');

        $this->connection = $connectionName;

        Config::set('database.connections.' . $connectionName . '.username', $username_prefix . $user);
        Config::set('database.connections.' . $connectionName . '.password', $pass);
    }
}
