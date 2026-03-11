<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class BitacoraController extends BaseController
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(env('API_URL', 'http://backend.test/api'), '/');
    }

    protected function getToken()
    {
        return session('api_token');
    }

    public function apiGet($endpoint, $params = [])
    {
        $token = $this->getToken();
        return Http::withToken($token)
            ->get($this->apiUrl . '/' . ltrim($endpoint, '/'), $params);
    }

    public function index(Request $request)
    {
        $filtros = $request->only([
            'usuario_id', 'tipo_evento', 'subtipo_evento', 'modulo', 'tabla',
            'registro_id', 'fecha_inicio', 'fecha_fin', 'ip_address',
            'numero_registro', 'descripcion', 'per_page'
        ]);

        $response = $this->apiGet('bitacora', $filtros);

        if ($response->failed()) {
            return $this->jsonError(
                'Error al obtener eventos de bitácora',
                $response->status(),
                $response->json()
            );
        }

        return $this->jsonSuccess(
            $response->json('data'),
            $response->json('message') ?? 'Eventos obtenidos'
        );
    }

    public function show($id)
    {
        $response = $this->apiGet("bitacora/{$id}");

        if ($response->failed()) {
            return $this->jsonError('Evento no encontrado', $response->status(), $response->json());
        }

        return $this->jsonSuccess($response->json('data'), $response->json('message') ?? 'Evento obtenido');
    }

    public function resumenActividad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validación', 422, $validator->errors());
        }

        $response = $this->apiGet('bitacora/resumen-actividad', $request->only('fecha_inicio', 'fecha_fin'));

        if ($response->failed()) {
            return $this->jsonError('Error al obtener resumen', $response->status(), $response->json());
        }

        return $this->jsonSuccess($response->json('data'), $response->json('message') ?? 'Resumen obtenido');
    }

    public function actividadUsuario(Request $request, $usuarioId)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validación', 422, $validator->errors());
        }

        $response = $this->apiGet("bitacora/actividad-usuario/{$usuarioId}", $request->only('fecha_inicio', 'fecha_fin'));

        if ($response->failed()) {
            return $this->jsonError('Error al obtener actividad del usuario', $response->status(), $response->json());
        }

        return $this->jsonSuccess($response->json('data'), $response->json('message') ?? 'Actividad obtenida');
    }

    public function actividadModulo(Request $request, $modulo)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validación', 422, $validator->errors());
        }

        $response = $this->apiGet("bitacora/actividad-modulo/{$modulo}", $request->only('fecha_inicio', 'fecha_fin'));

        if ($response->failed()) {
            return $this->jsonError('Error al obtener actividad del módulo', $response->status(), $response->json());
        }

        return $this->jsonSuccess($response->json('data'), $response->json('message') ?? 'Actividad obtenida');
    }

    public function actividadTabla(Request $request, $tabla, $registroId = null)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validación', 422, $validator->errors());
        }

        $endpoint = $registroId
            ? "bitacora/actividad-tabla/{$tabla}/{$registroId}"
            : "bitacora/actividad-tabla/{$tabla}";

        $response = $this->apiGet($endpoint, $request->only('fecha_inicio', 'fecha_fin'));

        if ($response->failed()) {
            return $this->jsonError('Error al obtener actividad de la tabla', $response->status(), $response->json());
        }

        return $this->jsonSuccess($response->json('data'), $response->json('message') ?? 'Actividad obtenida');
    }

    public function exportar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formato'      => 'required|in:CSV,PDF,JSON',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validación', 422, $validator->errors());
        }

        $response = $this->apiGet('bitacora/exportar', $request->only('formato', 'fecha_inicio', 'fecha_fin'));

        if ($response->failed()) {
            return $this->jsonError('Error al exportar bitácora', $response->status(), $response->json());
        }

        if ($request->formato === 'JSON') {
            return $this->jsonSuccess($response->json('data'), $response->json('message') ?? 'Exportación generada');
        }

        return response($response->body(), $response->status(), $response->headers());
    }
}