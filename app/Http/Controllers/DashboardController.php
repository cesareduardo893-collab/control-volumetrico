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

            // Obtener resumen desde la API
            $response = $this->apiGet('/api/dashboard/resumen');
            
            if ($this->apiResponseSuccessful($response)) {
                $resumen = $this->apiResponseData($response, []);
                
                // Si el endpoint de resumen no devuelve alarmas_activas, obtenerlo directamente
                if (!isset($resumen['alarmas_activas'])) {
                    $alarmasResponse = $this->apiGet('/api/alarmas/activas');
                    if ($this->apiResponseSuccessful($alarmasResponse)) {
                        $alarmas = $this->apiResponseData($alarmasResponse, []);
                        $resumen['alarmas_activas'] = is_array($alarmas) ? count($alarmas) : 0;
                    }
                }
            } else {
                $resumen = $this->getDefaultResumen();
                
                // Intentar obtener alarmas activas directamente como fallback
                $alarmasResponse = $this->apiGet('/api/alarmas/activas');
                if ($this->apiResponseSuccessful($alarmasResponse)) {
                    $alarmas = $this->apiResponseData($alarmasResponse, []);
                    $resumen['alarmas_activas'] = is_array($alarmas) ? count($alarmas) : 0;
                }
            }

            return view('dashboard.index', [
                'resumen' => $resumen,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar dashboard', [
                'error' => $e->getMessage()
            ]);

            $resumen = $this->getDefaultResumen();
            
            // Intentar obtener alarmas activas directamente como fallback
            try {
                $this->setApiToken(Session::get('api_token'));
                $alarmasResponse = $this->apiGet('/api/alarmas/activas');
                if ($this->apiResponseSuccessful($alarmasResponse)) {
                    $alarmas = $this->apiResponseData($alarmasResponse, []);
                    $resumen['alarmas_activas'] = is_array($alarmas) ? count($alarmas) : 0;
                }
            } catch (\Exception $ex) {
                // Ignorar error al obtener alarmas activas
            }

            return view('dashboard.index', [
                'resumen' => $resumen,
            ]);
        }
    }

    /**
     * Obtener datos por defecto del resumen
     */
    private function getDefaultResumen(): array
    {
        return [
            'contribuyentes_activos' => 0,
            'contribuyentes_total' => 0,
            'instalaciones_activas' => 0,
            'instalaciones_total' => 0,
            'alarmas_activas' => 0,
            'volumen_total' => 0,
            'tanques_total' => 0,
            'medidores_total' => 0,
            'dispensarios_total' => 0,
            'mangueras_total' => 0,
            'registros_hoy' => 0,
            'ultimos_movimientos' => [],
        ];
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

            $params = $request->only([
                'fecha_inicio', 'fecha_fin', 'tipo_reporte'
            ]);

            $modulo = 'dashboard';
            $response = $this->apiGetRaw('/api/exportar/' . $modulo, $params);

            if ($response && $response->successful()) {
                $contentType = $response->headers->get('Content-Type');
                $contentDisposition = $response->headers->get('Content-Disposition');

                return response($response->body(), $response->status())
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', $contentDisposition);
            }

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
