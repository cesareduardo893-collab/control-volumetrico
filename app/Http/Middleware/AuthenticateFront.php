<?php

namespace App\Http\Middleware;

use App\Services\ApiService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFront
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado a través de tu ApiService
        if (!$this->apiService->isAuthenticated()) {
            // Redirigir al login si no está autenticado
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión.');
        }

        return $next($request);
    }
}