<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckFrontendAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Permitir acceso a rutas específicas sin autenticación
        if ($request->is('login') || $request->is('register') || $request->is('/') || $request->is('api/*')) {
            return $next($request);
        }
        
        // Verificar si el usuario está autenticado a través de la sesión
        if (!Session::has('api_token') || !Session::get('api_token')) {
            // Redirigir al login si no está autenticado
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión.');
        }

        return $next($request);
    }
}
