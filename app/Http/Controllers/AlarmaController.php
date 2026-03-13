<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AlarmaController extends BaseController
{
    /**
     * Listar alarmas
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'componente_tipo', 'componente_id', 'tipo_alarma_id', 'gravedad',
                'atendida', 'estado_atencion', 'requiere_atencion_inmediata',
                'fecha_inicio', 'fecha_fin', 'numero_registro', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/alarmas', $params);

            return $this->renderView('alarmas.index', $response, ['key' => 'alarmas'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar alarmas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar alarmas');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener tipos de alarma del catálogo
            $tiposAlarma = $this->getCatalog('/api/catalogos', ['tipo' => 'tipo_alarma']);

            return view('alarmas.create', [
                'tiposAlarma' => $tiposAlarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear alarma
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_registro' => 'required|string|max:255',
            'fecha_hora' => 'required|date',
            'componente_tipo' => 'required|string|max:255',
            'componente_identificador' => 'required|string|max:255',
            'tipo_alarma_id' => 'required|integer',
            'gravedad' => 'required|in:BAJA,MEDIA,ALTA,CRITICA',
            'descripcion' => 'required|string',
            'estado_atencion' => 'required|in:PENDIENTE,EN_PROCESO,RESUELTA,IGNORADA',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/alarmas', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $alarmaData = $this->apiResponseData($response, []);
                $alarmaId = $alarmaData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    'seguridad',
                    'ALARMA_CREADA',
                    'Alarmas',
                    "Alarma creada: {$request->numero_registro}",
                    'alarmas',
                    $alarmaId
                );

                return redirect()->route('alarmas.show', $alarmaId)
                    ->with('success', 'Alarma creada exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear alarma'));

        } catch (\Exception $e) {
            Log::error('Error al crear alarma', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear alarma');
        }
    }

    /**
     * Mostrar alarma
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            return view('alarmas.show', [
                'alarma' => $alarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar alarma');
        }
    }

    /**
     * Atender alarma
     */
    public function atender(Request $request, $id)
    {
        $request->validate([
            'acciones_tomadas' => 'required|string',
            'estado_atencion' => 'required|in:EN_PROCESO,RESUELTA,IGNORADA',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/alarmas/{$id}/atender", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'seguridad',
                    'ALARMA_ATENDIDA',
                    'Alarmas',
                    "Alarma atendida ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.show', $id)
                    ->with('success', 'Alarma atendida exitosamente');
            }

            if ($response->status === 403) {
                return redirect()->back()
                    ->with('error', 'La alarma ya ha sido atendida');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al atender alarma'));

        } catch (\Exception $e) {
            Log::error('Error al atender alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al atender alarma');
        }
    }

    /**
     * Actualizar estado de alarma
     */
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado_atencion' => 'required|in:PENDIENTE,EN_PROCESO,RESUELTA,IGNORADA',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/alarmas/{$id}/actualizar-estado", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'seguridad',
                    'ESTADO_ALARMA_ACTUALIZADO',
                    'Alarmas',
                    "Estado de alarma actualizado ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.show', $id)
                    ->with('success', 'Estado de alarma actualizado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar estado'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar estado');
        }
    }

    /**
     * Obtener estadísticas de alarmas
     */
    public function estadisticas(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/alarmas/estadisticas', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar estadísticas'));
            }

            $estadisticas = $this->apiResponseData($response, []);

            return view('alarmas.estadisticas', [
                'estadisticas' => $estadisticas,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas de alarmas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar estadísticas');
        }
    }

    /**
     * Obtener alarmas activas
     */
    public function activas(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['componente_tipo', 'componente_id', 'gravedad']);

            $response = $this->apiGet('/api/alarmas/activas', $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar alarmas activas'));
            }

            $alarmas = $this->apiResponseData($response, []);

            if ($request->expectsJson()) {
                return $this->jsonSuccess($alarmas, 'Alarmas activas obtenidas');
            }

            return view('alarmas.activas', [
                'alarmas' => $alarmas
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener alarmas activas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar alarmas activas');
        }
    }

    /**
     * Mostrar formulario para atender alarma
     */
    public function atenderForm($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            return view('alarmas.atender', [
                'alarma' => $alarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de atención', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario de atención');
        }
    }

    /**
     * Mostrar formulario para actualizar estado de alarma
     */
    public function actualizarEstadoForm($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            return view('alarmas.actualizar-estado', [
                'alarma' => $alarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de actualización', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario de actualización');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            // Obtener tipos de alarma del catálogo
            $tiposAlarma = $this->getCatalog('/api/catalogos', ['tipo' => 'tipo_alarma']);

            return view('alarmas.edit', [
                'alarma' => $alarma,
                'tiposAlarma' => $tiposAlarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario de edición');
        }
    }

    /**
     * Actualizar alarma
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'numero_registro' => 'sometimes|string|max:255',
            'fecha_hora' => 'sometimes|date',
            'componente_tipo' => 'sometimes|string|max:255',
            'componente_identificador' => 'sometimes|string|max:255',
            'tipo_alarma_id' => 'sometimes|integer',
            'gravedad' => 'sometimes|in:BAJA,MEDIA,ALTA,CRITICA',
            'descripcion' => 'sometimes|string',
            'estado_atencion' => 'sometimes|in:PENDIENTE,EN_PROCESO,RESUELTA,IGNORADA',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/alarmas/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'seguridad',
                    'ALARMA_ACTUALIZADA',
                    'Alarmas',
                    "Alarma actualizada ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.show', $id)
                    ->with('success', 'Alarma actualizada exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar alarma'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar alarma');
        }
    }

    /**
     * Eliminar alarma
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/alarmas/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'seguridad',
                    'ALARMA_ELIMINADA',
                    'Alarmas',
                    "Alarma eliminada ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.index')
                    ->with('success', 'Alarma eliminada exitosamente');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al eliminar alarma'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al eliminar alarma');
        }
    }
}