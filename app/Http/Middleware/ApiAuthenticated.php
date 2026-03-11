<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        return $next($request);
    }
}