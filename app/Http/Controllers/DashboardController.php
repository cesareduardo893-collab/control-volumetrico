<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
        $this->setApiToken(Session::get('api_token'));
    }

    /**
     * Muestra el dashboard principal.
     */
    public function index()
    {
        $resumen = $this->apiGet('/api/dashboard/resumen');
        $tiempoReal = $this->apiGet('/api/dashboard/tiempo-real');

        return view('dashboard.index', [
            'resumen' => $this->apiResponseData($resumen, []),
            'tiempoReal' => $this->apiResponseData($tiempoReal, []),
        ]);
    }

    /**
     * Obtiene datos en tiempo real via AJAX.
     */
    public function tiempoReal(Request $request)
    {
        $response = $this->apiGet('/api/dashboard/tiempo-real');
        
        if ($this->apiResponseSuccessful($response)) {
            return response()->json($this->apiResponseData($response));
        }

        return response()->json(['error' => 'Error al obtener datos'], 500);
    }

    /**
     * Obtiene gráfica de movimientos.
     */
    public function graficaMovimientos(Request $request)
    {
        $response = $this->apiGet('/api/dashboard/grafica-movimientos', $request->all());
        
        if ($this->apiResponseSuccessful($response)) {
            return response()->json($this->apiResponseData($response));
        }

        return response()->json(['error' => 'Error al obtener datos'], 500);
    }

    /**
     * Marcar notificación como leída
     */
    public function markNotificationAsRead($id)
    {
        $response = $this->apiPost("/api/notificaciones/{$id}/read");
        
        if ($this->apiResponseSuccessful($response)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 500);
    }
}