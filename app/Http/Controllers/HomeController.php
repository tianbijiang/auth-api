<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {

        $facilityUrl = Config::get('config.facilityUrl');
        $specAdminUrl = Config::get('config.specAdminUrl');
        $techUrl = Config::get('config.techUrl');

        $session_id = Session::getId();
        $roles = Session::get("roles");

        if (in_array("PHYSICIAN", $roles)) {
            return redirect($facilityUrl . $session_id);
        }

        if (in_array("SPECTOCOR_ADMIN", $roles)) {
            return (redirect($specAdminUrl));
        }

        if (in_array("TECHNICIAN", $roles)) {
            return (redirect($techUrl . $session_id));
        }


    }
}
