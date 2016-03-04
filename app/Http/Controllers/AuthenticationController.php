<?php

namespace App\Http\Controllers;

use App\Role;
use App\UserRoles;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Config;
use Webpatser\Uuid\Uuid;

class AuthenticationController extends Controller
{

    public function getLogin()
    {
        $error = '';
        return view('login', compact('error'));
    }

    public function postLogin(Request $request)
    {

        $username = $request->input('username');
        $password = $request->input('password');

        try {

            Config::set('database.connections.mysql.username', "user.".$username);
            Config::set('database.connections.mysql.password', $password);
            DB::connection();

            Session()->regenerate();

            // Use App credential
            Session::put('sid', Session::getId());
            Session::put('username', $username);
            Session::put('password', $password);
            $rolesArr = [];

            $uuid = User::where('username', 'admin')->firstOrFail()->uuid;
            Session::put("uuid", bin2hex($uuid));
            $roles = UserRoles::where('uuid', $uuid)->get();
            foreach ($roles as $role) {
                array_push($rolesArr, $role->role);
            }
            Session::put("roles", $rolesArr);

            return redirect('home');

        } catch (Exception $e) {
            $error = 'Wrong username or password.';
            return view('login', compact('error'));
        }
    }

    public function getLogout()
    {
        Session::flush();
        return redirect('');
    }

}
