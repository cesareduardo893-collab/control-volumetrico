<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BitacoraController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'usuario_id', 'tipo_evento', 'subtipo_evento', 'modulo', 'tabla',
                'registro_id', 'fecha_inicio', 'fecha_fin', 'ip_address',
                'numero_registro', 'descripcion', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/bitacora', $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar bitacora'));
            }

            $data = $this->apiResponseData($response);
            $eventos = $data['data'] ?? [];
            $meta = [
                'current_page' => $data['current_page'] ?? 0,
                'from' => $data['from'] ?? 0,
                'to' => $data['to'] ?? 0,
                'per_page' => $data['per_page'] ?? 0,
                'last_page' => $data['last_page'] ?? 0,
                'total' => $data['total'] ?? 0,
            ];
            $links = $data['links'] ?? $data['link'] ?? [];

            return view('bitacora.index', [
                'eventos' => $eventos,
                'meta' => $meta,
                'links' => $links,
                'filters' => $params
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cargar bitacora', [
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error al cargar bitacora');
        }
    }

    public function show($id)
    {
        $this->setApiToken(Session::get('api_token'));

        $response = $this->apiGet("/api/bitacora/{$id}");

        if (!$this->apiResponseSuccessful($response)) {
            return $this->jsonError(
                'Evento no encontrado',
                $response['status'] ?? 404,
                $response['errors'] ?? []
            );
        }

        return $this->jsonSuccess(
            $this->apiResponseData($response),
            $this->apiResponseMessage($response, 'Evento obtenido')
        );
    }

    public function resumenActividad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validacion', 422, $validator->errors());
        }

        $this->setApiToken(Session::get('api_token'));

        $response = $this->apiGet('/api/bitacora/resumen-actividad', $request->only('fecha_inicio', 'fecha_fin'));

        if (!$this->apiResponseSuccessful($response)) {
            return $this->jsonError(
                'Error al obtener resumen',
                $response['status'] ?? 400,
                $response['errors'] ?? []
            );
        }

        return $this->jsonSuccess(
            $this->apiResponseData($response),
            $this->apiResponseMessage($response, 'Resumen obtenido')
        );
    }

    public function actividadUsuario(Request $request, $usuarioId)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validacion', 422, $validator->errors());
        }

        $this->setApiToken(Session::get('api_token'));

        $response = $this->apiGet("/api/bitacora/actividad-usuario/{$usuarioId}", $request->only('fecha_inicio', 'fecha_fin'));

        if (!$this->apiResponseSuccessful($response)) {
            return $this->jsonError(
                'Error al obtener actividad del usuario',
                $response['status'] ?? 400,
                $response['errors'] ?? []
            );
        }

        return $this->jsonSuccess(
            $this->apiResponseData($response),
            $this->apiResponseMessage($response, 'Actividad obtenida')
        );
    }

    public function actividadModulo(Request $request, $modulo)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validacion', 422, $validator->errors());
        }

        $this->setApiToken(Session::get('api_token'));

        $response = $this->apiGet("/api/bitacora/actividad-modulo/{$modulo}", $request->only('fecha_inicio', 'fecha_fin'));

        if (!$this->apiResponseSuccessful($response)) {
            return $this->jsonError(
                'Error al obtener actividad del modulo',
                $response['status'] ?? 400,
                $response['errors'] ?? []
            );
        }

        return $this->jsonSuccess(
            $this->apiResponseData($response),
            $this->apiResponseMessage($response, 'Actividad obtenida')
        );
    }

    public function actividadTabla(Request $request, $tabla, $registroId = null)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validacion', 422, $validator->errors());
        }

        $this->setApiToken(Session::get('api_token'));

        $endpoint = $registroId
            ? "/api/bitacora/actividad-tabla/{$tabla}/{$registroId}"
            : "/api/bitacora/actividad-tabla/{$tabla}";

        $response = $this->apiGet($endpoint, $request->only('fecha_inicio', 'fecha_fin'));

        if (!$this->apiResponseSuccessful($response)) {
            return $this->jsonError(
                'Error al obtener actividad de la tabla',
                $response['status'] ?? 400,
                $response['errors'] ?? []
            );
        }

        return $this->jsonSuccess(
            $this->apiResponseData($response),
            $this->apiResponseMessage($response, 'Actividad obtenida')
        );
    }

    public function exportar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formato'      => 'required|in:CSV,PDF,JSON',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Error de validacion', 422, $validator->errors());
        }

        $this->setApiToken(Session::get('api_token'));

        $params = $request->only('formato', 'fecha_inicio', 'fecha_fin');

        $response = Http::baseUrl(config('services.api.url', env('API_URL', 'http://localhost:8000')))
            ->withToken(Session::get('api_token'))
            ->timeout(60)
            ->get('/api/bitacora/exportar', $params);

        if (!$response->successful()) {
            $json = $response->json();
            return $this->jsonError(
                $json['message'] ?? 'Error al exportar bitacora',
                $response->status(),
                $json['errors'] ?? []
            );
        }

        if ($request->formato === 'JSON') {
            return $this->jsonSuccess(
                $response->json('data'),
                $response->json('message') ?? 'Exportacion generada'
            );
        }

        return response($response->body(), $response->status(), $response->headers());
    }
}
