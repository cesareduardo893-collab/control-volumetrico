<?php

namespace App\Http\Middleware;

use App\Services\ApiService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFrontendAuth
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Usa URL directa en lugar de route()
        if ($request->is('login') || $request->routeIs('login')) {
            return $next($request);
        }

        if (!$this->apiService->isAuthenticated()) {
            return redirect('/login');  // ← Cambié de route('login') a '/login'
        }

        return $next($request);
    }
}