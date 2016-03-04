<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Auth
{
    public function handle($request, Closure $next)
    {
        $roles = Session::get('roles');
        if ($roles == null) {
            return redirect('');
        }
        return $next($request);
    }
}