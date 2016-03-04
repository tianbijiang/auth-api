<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Admin
{
    public function handle($request, Closure $next)
    {
        $roles = Session::get('roles');
        if ($roles == null || !in_array("SPECTOCOR_ADMIN", $roles)) {
            return redirect('');
        }
        return $next($request);
    }
}