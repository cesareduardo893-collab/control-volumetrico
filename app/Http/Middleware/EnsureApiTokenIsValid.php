<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        // Permitir peticiones OPTIONS para CORS
        if ($request->isMethod('OPTIONS')) {
            return response()->json(['status' => 'ok'], 200)
                ->header('Access-Control-Allow-Origin', $request->header('Origin', '*'))
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        // Si viene un token en el header Authorization
        if ($request->bearerToken()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($request->bearerToken());
            
            if ($accessToken) {
                auth()->setUser($accessToken->tokenable);
                return $next($request);
            }
        }

        // Si no hay token, continuar (las rutas públicas pasarán)
        return $next($request);
    }
}