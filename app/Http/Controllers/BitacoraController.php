<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class BitacoraController extends BaseController
{
    /**
     * Listar eventos de bitácora
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'usuario_id', 'tipo_evento', 'subtipo_evento', 'modulo',
                'tabla', 'registro_id', 'fecha_inicio', 'fecha_fin',
                'ip_address', 'numero_registro', 'descripcion', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/bitacora', $params);

            return $this->renderView('bitacora.index', $response, ['key' => 'eventos'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar bitácora', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar bitácora');
        }
    }

    /**
     * Mostrar evento específico
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/bitacora/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('bitacora.index')
                    ->with('error', $this->apiResponseMessage($response, 'Evento no encontrado'));
            }

            $evento = $this->apiResponseData($response, []);

            return view('bitacora.show', [
                'evento' => $evento
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar evento de bitácora', [
                'error' => $e->getMessage(),
                'evento_id' => $id
            ]);

            return redirect()->route('bitacora.index')
                ->with('error', 'Error al cargar evento');
        }
    }

    /**
     * Obtener resumen de actividad
     */
    public function resumenActividad(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/bitacora/resumen-actividad', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al generar resumen'));
            }

            $resumen = $this->apiResponseData($response, []);

            return view('bitacora.resumen', [
                'resumen' => $resumen,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener resumen de actividad', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al generar resumen');
        }
    }

    /**
     * Obtener actividad por usuario
     */
    public function actividadUsuario(Request $request, $usuarioId)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/bitacora/usuario/{$usuarioId}", $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar actividad'));
            }

            $actividad = $this->apiResponseData($response, []);

            return view('bitacora.usuario', [
                'actividad' => $actividad,
                'usuarioId' => $usuarioId,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener actividad por usuario', [
                'error' => $e->getMessage(),
                'usuario_id' => $usuarioId
            ]);

            return redirect()->back()->with('error', 'Error al cargar actividad');
        }
    }

    /**
     * Obtener actividad por módulo
     */
    public function actividadModulo(Request $request, $modulo)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/bitacora/modulo/{$modulo}", $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar actividad'));
            }

            $actividad = $this->apiResponseData($response, []);

            return view('bitacora.modulo', [
                'actividad' => $actividad,
                'modulo' => $modulo,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener actividad por módulo', [
                'error' => $e->getMessage(),
                'modulo' => $modulo
            ]);

            return redirect()->back()->with('error', 'Error al cargar actividad');
        }
    }

    /**
     * Obtener actividad por tabla
     */
    public function actividadTabla(Request $request, $tabla, $registroId = null)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $url = $registroId 
                ? "/api/bitacora/tabla/{$tabla}/{$registroId}"
                : "/api/bitacora/tabla/{$tabla}";

            $response = $this->apiGet($url, $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar actividad'));
            }

            $actividad = $this->apiResponseData($response, []);

            return view('bitacora.tabla', [
                'actividad' => $actividad,
                'tabla' => $tabla,
                'registroId' => $registroId,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener actividad por tabla', [
                'error' => $e->getMessage(),
                'tabla' => $tabla,
                'registro_id' => $registroId
            ]);

            return redirect()->back()->with('error', 'Error al cargar actividad');
        }
    }

    /**
     * Exportar bitácora
     */
    public function exportar(Request $request)
    {
        $request->validate([
            'formato' => 'required|in:CSV,PDF,JSON',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/bitacora/exportar', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al exportar'));
            }

            $this->logActivity(
                Session::get('user_id'),
                'reportes',
                'BITACORA_EXPORTADA',
                'Bitácora',
                "Bitácora exportada en formato {$request->formato}"
            );

            // Redirigir a la descarga o mostrar el resultado según el formato
            if ($request->formato === 'JSON') {
                return response()->json($this->apiResponseData($response, []))
                    ->header('Content-Disposition', 'attachment; filename="bitacora.json"');
            }

            return redirect()->back()->with('success', 'Exportación generada exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al exportar bitácora', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al exportar bitácora');
        }
    }
}