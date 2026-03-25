<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends BaseController
{
    /**
     * Muestra el dashboard principal con datos de la base de datos.
     */
    public function index(Request $request)
    {
        try {
            $resumen = [
                'contribuyentes_activos' => 0,
                'instalaciones_activas' => 0,
                'alarmas_activas' => 0,
                'volumen_total' => 0,
                'ultimos_movimientos' => [],
            ];

            // Obtener usuarios activos (usando la tabla users que sí existe)
            if (Schema::hasTable('users')) {
                $resumen['contribuyentes_activos'] = DB::table('users')
                    ->where('active', true)
                    ->count();
            }
            
            // Las demás tablas no existen aún, se mantienen en 0
            // Cuando se creen las migraciones, se pueden habilitar estas consultas
            
            return view('dashboard.index', [
                'resumen' => $resumen,
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