<?php

namespace App\Http\Controllers;

use App\Role;
use App\Tech;
use App\TechRolesHistory;
use App\User;
use App\UserRoles;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;

class AdminController extends Controller
{
    private function configConn($conn, $user, $pass)
    {
        $username_prefix = Config::get('config.username_prefix_for_user');
        Config::set('database.connections.' . $conn . '.username', $username_prefix . $user);
        Config::set('database.connections.' . $conn . '.password', $pass);
    }

    public function index()
    {
        $session_id = Session::getId();
        $uuid = Session::get("uuid");
        $username = Session::get("username");
        $roles = Session::get("roles");

        return view("adminHome", compact("session_id", "uuid", "username", "roles"));
    }

    public function techMgmt()
    {
        // TODO: change to global models
        $username = Session::get("username");
        $password = Session::get("password");
        $role_model = new Role();
        $user_model = new User();
        $tech_model = new Tech();
        $user_roles_model = new UserRoles();
        $tech_roles_history_model = new TechRolesHistory();
        $adminConn = Config::get("config.adminConn");
        $user_model->changeConnection($adminConn, $username, $password);
        $role_model->changeConnection($adminConn, $username, $password);
        $tech_model->changeConnection($adminConn, $username, $password);
        $user_roles_model->changeConnection($adminConn, $username, $password);
        $tech_roles_history_model->changeConnection($adminConn, $username, $password);

        // list of techs
        $techs = $user_roles_model->where("role", "TECHNICIAN")->get();
        $users_arr = [];
        $each = [];
        foreach ($techs as $tech) {
            $uuid = $tech->uuid;
            $tech = $tech_model->where('user_id', $uuid)->get();
            // tables might be empty
            if (!$tech->isEmpty()) {
                $tech = $tech->first();
                $each['fname'] = $tech->first_name;
                $each['lname'] = $tech->last_name;
            } else {
                $each['fname'] = "";
                $each['lname'] = "";
            }
            $each['username'] = $user_model->where('uuid', $uuid)->firstOrFail()->username;
            $each['uuid'] = bin2hex($uuid);
            $roles = [];
            $rs = $tech_roles_history_model->where('technician_id', $uuid)->where('role_ended_at', '>', Carbon::now())->get();
            if (!$rs->isEmpty()) {
                foreach ($rs as $role) {
                    array_push($roles, $role->technician_role);
                }
            } else {
                $roles = [Config::get('config.no_role_err_msg')];
            }
            $each['roles'] = $roles;
            array_push($users_arr, $each);
        }
        $users_arr = json_decode(json_encode($users_arr));

        $rs_arr = json_decode(json_encode($role_model->where('role', 'like', 'TECHNICIAN_%')->get()));
        $roles_arr = [];
        foreach ($rs_arr as $rs) {
            array_push($roles_arr, $rs->role);
        }
        return view("techMgmt", compact("username", "users_arr", "roles_arr"));
    }

    public function createUser()
    {
        $username_prefix = Config::get('config.username_prefix_for_user');
        $host = Config::get("config.clientHost");
        $e_username = Input::get('username');
        $e_password = Input::get('password');
        $e_fname = Input::get('fname');
        $e_lname = Input::get('lname');
        $e_roles = Input::get('roles');
        $e_uuid = hex2bin(str_replace('-', '', Uuid::generate(4)));

        $uuid = hex2bin(Session::get("uuid"));

        // TODO: change to global models
        $adminConn = Config::get("config.adminConn");
        $username = Session::get("username");
        $password = Session::get("password");
        $user_model = new User();
        $user_roles_model = new UserRoles();
        $tech_model = new Tech();
        $user_model->changeConnection($adminConn, $username, $password);
        $user_roles_model->changeConnection($adminConn, $username, $password);
        $tech_model->changeConnection($adminConn, $username, $password);

        foreach ($host as $h) {
            $create = "CREATE USER '" . $username_prefix . $e_username . "'@'" . $h . "' IDENTIFIED BY '" . $e_password . "'";
            DB::connection($adminConn)->statement($create);
        }

        $user_model->uuid = $e_uuid;
        $user_model->username = $e_username;
        $user_model->save();

        $user_roles_model->uuid = $e_uuid;
        $user_roles_model->role = "TECHNICIAN";
        $user_roles_model->save();

        $tech_model->user_id = $e_uuid;
        $tech_model->first_name = $e_fname;
        $tech_model->last_name = $e_lname;
        $tech_model->save();

        if ($e_roles != []) {
            foreach ($e_roles as $role) {
                $tech_roles_history_model = new TechRolesHistory();
                $tech_roles_history_model->changeConnection($adminConn, $username, $password);

                $tech_roles_history_model->technician_id = $e_uuid;
                $tech_roles_history_model->assigned_by_user = $uuid;
                $tech_roles_history_model->technician_role = $role;
                $tech_roles_history_model->save();
            }
        }
    }

    public function editUser()
    {
        $e_uuid = hex2bin(Input::get('uuid'));
        $e_fname = Input::get('fname');
        $e_lname = Input::get('lname');
        $e_username = Input::get('username');
        $e_roles = Input::get('roles');

        $uuid = hex2bin(Session::get("uuid"));

        $username = Session::get("username");
        $password = Session::get("password");
        $adminConn = Config::get("config.adminConn");
        $this->configConn($adminConn, $username, $password);

        $user_model = new User();
        $tech_model = new Tech();
        $tech_roles_history_model = new TechRolesHistory();
        $user_model->changeConnection($adminConn, $username, $password);
        $tech_model->changeConnection($adminConn, $username, $password);
        $tech_roles_history_model->changeConnection($adminConn, $username, $password);

        // edit names
        $tech = $tech_model->where("user_id", $e_uuid)->get();
        if (!$tech->isEmpty()) {
            $tech = $tech->first();
            $tech->first_name = $e_fname;
            $tech->last_name = $e_lname;
            $tech->save();
        } else {
            $tech_model->user_id = $e_uuid;
            $tech_model->first_name = $e_fname;
            $tech_model->last_name = $e_lname;
            $tech_model->save();
        }

        // edit username
        $user = $user_model->where("uuid", $e_uuid)->firstOrFail();
        $old_username = $user->username;
        $user->username = $e_username;
        $user->save();

        //edit mysql account
        $host = Config::get("config.clientHost");
        $username_prefix = Config::get('config.username_prefix_for_user');
        if ($old_username != $e_username) {
            foreach ($host as $h) {
                $rename = "RENAME USER '" . $username_prefix . $old_username . "'@'" . $h . "' TO '" . $username_prefix . $e_username . "'@'" . $h . "'";
                DB::connection($adminConn)->statement($rename);
            }
        }

        $tb_techs_roles = Config::get('config.tb_techs_roles');
        // end all roles
        $now = Carbon::now();
        $tech_history = $tech_roles_history_model->where("technician_id", $e_uuid)->where('role_ended_at', '>', $now)->get();
        if (!$tech_history->isEmpty()) {
            DB::connection($adminConn)
                ->update('update ' . $tb_techs_roles . '
                set ended_by_user = ?,
                role_ended_at = ?
                where role_ended_at > ? and
                    technician_id = ?',
                    [$uuid,
                        $now->addSeconds(-1),
                        $now,
                        $e_uuid
                    ]);
        }

        // create new roles
        if ($e_roles != []) {
            foreach ($e_roles as $e_role) {
                DB::connection($adminConn)
                    ->insert('insert into ' . $tb_techs_roles . ' (technician_id,technician_role,assigned_by_user)
                values (?,?,?)',
                        [$e_uuid, $e_role, $uuid]);
            }
        }
    }

    public function deleteUser()
    {
        $uuid = hex2bin(Session::get("uuid"));

        $username = Session::get("username");
        $password = Session::get("password");
        $adminConn = Config::get("config.adminConn");
        $this->configConn($adminConn, $username, $password);

        $host = Config::get("config.clientHost");
        $tb_users = Config::get("config.tb_users");
        $tb_users_roles = Config::get("config.tb_users_roles");
        $tb_techs = Config::get("config.tb_techs");
        $tb_techs_roles = Config::get("config.tb_techs_roles");

        $e_uuid = hex2bin(Input::get('uuid'));
        $e_username = json_decode(json_encode(DB::connection($adminConn)
            ->select('select username from ' . $tb_users . ' where uuid = ?', [$e_uuid])));
        $e_username = (array)$e_username[0];

        $username_prefix = Config::get('config.username_prefix_for_user');
        foreach ($host as $h) {
            $drop = "DROP USER '" . $username_prefix . $e_username['username'] . "'@'" . $h . "'";
            DB::connection($adminConn)->statement($drop);
        }

        DB::connection($adminConn)
            ->delete('delete from ' . $tb_users . ' where uuid = ?', [$e_uuid]);

        DB::connection($adminConn)
            ->delete('delete from ' . $tb_users_roles . ' where uuid = ?', [$e_uuid]);

        DB::connection($adminConn)
            ->delete('delete from ' . $tb_techs . ' where user_id = ?', [$e_uuid]);

        // end all roles
        $now = Carbon::now();
        DB::connection($adminConn)
            ->update('update ' . $tb_techs_roles . '
                set ended_by_user = ?,
                role_ended_at = ?
                where role_ended_at > ? and
                    technician_id = ?',
                [$uuid,
                    $now->addSeconds(-1),
                    $now,
                    $e_uuid
                ]);
    }

    public function roleMgmt()
    {
        $username = Session::get("username");
        $password = Session::get("password");
        $role_model = new Role();
        $adminConn = Config::get("config.adminConn");
        $role_model->changeConnection($adminConn, $username, $password);

        $rs_arr = json_decode(json_encode($role_model->where('role', 'like', 'TECHNICIAN%')->get()));
        $roles_arr = [];
        $each = [];
        foreach ($rs_arr as $rs) {
            $each['role'] = $rs->role;
            $each['desc'] = $rs->description;
            array_push($roles_arr, $each);
        }
        return view("roleMgmt", compact("username", "roles_arr"));
    }

    public function createRole()
    {
        $e_role = Input::get("role");
        $e_desc = Input::get("desc");

        $username = Session::get("username");
        $password = Session::get("password");
        $role_model = new Role();
        $adminConn = Config::get("config.adminConn");
        $role_model->changeConnection($adminConn, $username, $password);

        $role_model->role = $e_role;
        $role_model->description = $e_desc;
        $role_model->save();
    }

    public function editRole()
    {
        $e_role = Input::get("role");
        $e_newRole = Input::get("newRole");
        $e_desc = Input::get("desc");

        $username = Session::get("username");
        $password = Session::get("password");
        $adminConn = Config::get("config.adminConn");
        $this->configConn($adminConn, $username, $password);

        $tb_roles = Config::get('config.tb_roles');
        // update roles table
        DB::connection($adminConn)
            ->update('update ' . $tb_roles . '
                set role = ?,
                description = ?
                where role = ?',
                [$e_newRole,
                    $e_desc,
                    $e_role
                ]);

        // update current role history
        $tb_techs_roles = Config::get('config.tb_techs_roles');
        $now = Carbon::now();
        DB::connection($adminConn)
            ->update('update ' . $tb_techs_roles . '
                set technician_role = ?
                where role_ended_at > ? and
                    technician_role = ?',
                [$e_newRole,
                    $now,
                    $e_role
                ]);
    }

    public function deleteRole()
    {
        $e_role = Input::get("role");
        $uuid = hex2bin(Session::get("uuid"));

        $username = Session::get("username");
        $password = Session::get("password");
        $adminConn = Config::get("config.adminConn");
        $this->configConn($adminConn, $username, $password);

        $tb_roles = Config::get('config.tb_roles');
        $tb_techs_roles = Config::get("config.tb_techs_roles");

        DB::connection($adminConn)
            ->delete('delete from ' . $tb_roles . ' where role = ?', [$e_role]);

        // end all related roles
        $now = Carbon::now();
        DB::connection($adminConn)
            ->update('update ' . $tb_techs_roles . '
                set ended_by_user = ?,
                role_ended_at = ?
                where role_ended_at > ? and
                    technician_role = ?',
                [$uuid,
                    $now->addSeconds(-1),
                    $now,
                    $e_role
                ]);
    }
}
