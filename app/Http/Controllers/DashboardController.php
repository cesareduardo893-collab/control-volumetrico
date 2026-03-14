<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DashboardController extends BaseController
{
    /**
     * Muestra el dashboard principal con datos de la API.
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener resumen del dashboard
            $resumenResponse = $this->apiGet('/api/dashboard/resumen');
            
            $resumen = [
                'contribuyentes_activos' => 0,
                'instalaciones_activas' => 0,
                'alarmas_activas' => 0,
                'volumen_total' => 0,
                'ultimos_movimientos' => [],
            ];

            if ($this->apiResponseSuccessful($resumenResponse)) {
                $resumen = $this->apiResponseData($resumenResponse, $resumen);
            }

            // Obtener datos en tiempo real
            $tiempoRealResponse = $this->apiGet('/api/dashboard/tiempo-real');
            
            $tiempoReal = [
                'volumen_actual' => 0,
                'flujo' => 0,
                'temperatura' => 0,
                'presion' => 0,
            ];

            if ($this->apiResponseSuccessful($tiempoRealResponse)) {
                $tiempoReal = $this->apiResponseData($tiempoRealResponse, $tiempoReal);
            }

            return view('dashboard.index', [
                'resumen' => $resumen,
                'tiempoReal' => $tiempoReal,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar dashboard', [
                'error' => $e->getMessage()
            ]);

            return view('dashboard.index', [
                'resumen' => [
                    'contribuyentes_activos' => 0,
                    'instalaciones_activas' => 0,
                    'alarmas_activas' => 0,
                    'volumen_total' => 0,
                    'ultimos_movimientos' => [],
                ],
                'tiempoReal' => [
                    'volumen_actual' => 0,
                    'flujo' => 0,
                    'temperatura' => 0,
                    'presion' => 0,
                ],
            ]);
        }
    }

    /**
     * Obtener gráfica de movimientos por día (API)
     */
    public function graficaMovimientos(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            $dias = $request->get('dias', 7);
            
            $response = $this->apiGet('/api/dashboard/grafica-movimientos', ['dias' => $dias]);
            
            if ($this->apiResponseSuccessful($response)) {
                return response()->json($this->apiResponseData($response, [
                    'labels' => [],
                    'entradas' => [],
                    'salidas' => [],
                ]));
            }
            
            return response()->json([
                'labels' => [],
                'entradas' => [],
                'salidas' => [],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener gráfica de movimientos', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'labels' => [],
                'entradas' => [],
                'salidas' => [],
            ]);
        }
    }

    /**
     * Obtener gráfica de distribución por producto (API)
     */
    public function graficaProductos()
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            
            $response = $this->apiGet('/api/dashboard/grafica-productos');
            
            if ($this->apiResponseSuccessful($response)) {
                $data = $this->apiResponseData($response, [
                    'labels' => [],
                    'valores' => [],
                ]);
                
                return response()->json([
                    'labels' => $data['labels'] ?? [],
                    'valores' => $data['valores'] ?? [],
                ]);
            }
            
            return response()->json([
                'labels' => [],
                'valores' => [],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener gráfica de productos', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'labels' => [],
                'valores' => [],
            ]);
        }
    }

    /**
     * Exportar datos del dashboard
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener parámetros de filtro opcionales
            $params = $request->only([
                'fecha_inicio', 'fecha_fin', 'tipo_reporte'
            ]);

            $modulo = 'dashboard';
            $response = $this->apiGetRaw('/api/exportar/' . $modulo, $params);

            if ($response && $response->successful()) {
                // Si la API devuelve un archivo, lo enviamos directamente
                $contentType = $response->headers->get('Content-Type');
                $contentDisposition = $response->headers->get('Content-Disposition');

                return response($response->body(), $response->status())
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', $contentDisposition);
            }

            // Si no es exitoso, manejamos el error
            if ($response) {
                $json = $response->json();
                return $this->jsonError(
                    $json['message'] ?? 'Error al exportar dashboard',
                    $response->status(),
                    $json['errors'] ?? null
                );
            }

            return $this->jsonError('Error al exportar dashboard', 500);
        } catch (\Exception $e) {
            Log::error('Error al exportar dashboard', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al exportar dashboard');
        }
    }

    /**
     * Obtener notificaciones (API)
     */
    public function notificaciones(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            
            $response = $this->apiGet('/api/notificaciones');
            
            if ($this->apiResponseSuccessful($response)) {
                return response()->json($this->apiResponseData($response, []));
            }
            
            return response()->json([]);
        } catch (\Exception $e) {
            Log::error('Error al obtener notificaciones', [
                'error' => $e->getMessage()
            ]);
            return response()->json([]);
        }
    }
}