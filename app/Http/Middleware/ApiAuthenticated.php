<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        // Verificar si el token es válido haciendo una petición a la API
        try {
            $apiUrl = config('services.api.url', env('API_URL', 'http://localhost:8000'));
            $token = Session::get('api_token');
            
            $response = Http::baseUrl($apiUrl)
                ->withToken($token)
                ->timeout(5)
                ->get('/api/user');

            if (!$response->successful()) {
                // Token inválido o expirado, limpiar sesión y redirigir al login
                Session::flush();
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado. Por favor inicia sesión nuevamente.');
            }

        } catch (\Exception $e) {
            Log::warning('Error al verificar token de API', [
                'error' => $e->getMessage(),
            ]);
            
            // Si hay error de conexión, limpiar sesión y redirigir al login
            // para evitar mostrar datos incorrectos
            Session::flush();
            return redirect()->route('login')
                ->with('error', 'No se pudo verificar tu sesión. Por favor inicia sesión nuevamente.');
        }

        return $next($request);
    }
}
