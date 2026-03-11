<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DispensarioController extends BaseController
{
    /**
     * Listar dispensarios
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'instalacion_id', 'clave', 'modelo', 'fabricante',
                'estado', 'activo', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/dispensarios', $params);

            return $this->renderView('dispensarios.index', $response, ['key' => 'dispensarios'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar dispensarios', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar dispensarios');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener instalaciones para el select
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);

            return view('dispensarios.create', [
                'instalaciones' => $instalaciones
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('dispensarios.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear dispensario
     */
    public function store(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'clave' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'modelo' => 'nullable|string|max:255',
            'fabricante' => 'nullable|string|max:255',
            'estado' => 'required|in:OPERATIVO,MANTENIMIENTO,FUERA_SERVICIO',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/dispensarios', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $dispensarioData = $this->apiResponseData($response, []);
                $dispensarioId = $dispensarioData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    'configuracion',
                    'DISPENSARIO_CREADO',
                    'Dispensarios',
                    "Dispensario creado: {$request->clave}",
                    'dispensarios',
                    $dispensarioId
                );

                return redirect()->route('dispensarios.show', $dispensarioId)
                    ->with('success', 'Dispensario creado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear dispensario'));

        } catch (\Exception $e) {
            Log::error('Error al crear dispensario', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear dispensario');
        }
    }

    /**
     * Mostrar dispensario
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/dispensarios/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('dispensarios.index')
                    ->with('error', $this->apiResponseMessage($response, 'Dispensario no encontrado'));
            }

            $dispensario = $this->apiResponseData($response, []);

            return view('dispensarios.show', [
                'dispensario' => $dispensario
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar dispensario', [
                'error' => $e->getMessage(),
                'dispensario_id' => $id
            ]);

            return redirect()->route('dispensarios.index')
                ->with('error', 'Error al cargar dispensario');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/dispensarios/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('dispensarios.index')
                    ->with('error', $this->apiResponseMessage($response, 'Dispensario no encontrado'));
            }

            $dispensario = $this->apiResponseData($response, []);

            return view('dispensarios.edit', [
                'dispensario' => $dispensario
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'dispensario_id' => $id
            ]);

            return redirect()->route('dispensarios.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar dispensario
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'clave' => "sometimes|string|max:255",
            'descripcion' => 'nullable|string',
            'modelo' => 'nullable|string|max:255',
            'fabricante' => 'nullable|string|max:255',
            'estado' => 'sometimes|in:OPERATIVO,MANTENIMIENTO,FUERA_SERVICIO',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/dispensarios/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'configuracion',
                    'DISPENSARIO_ACTUALIZADO',
                    'Dispensarios',
                    "Dispensario actualizado ID: {$id}",
                    'dispensarios',
                    $id
                );

                return redirect()->route('dispensarios.show', $id)
                    ->with('success', 'Dispensario actualizado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar dispensario'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar dispensario', [
                'error' => $e->getMessage(),
                'dispensario_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar dispensario');
        }
    }

    /**
     * Eliminar dispensario
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/dispensarios/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'configuracion',
                    'DISPENSARIO_ELIMINADO',
                    'Dispensarios',
                    "Dispensario eliminado ID: {$id}",
                    'dispensarios',
                    $id
                );

                return redirect()->route('dispensarios.index')
                    ->with('success', 'Dispensario eliminado exitosamente');
            }

            if ($response->status === 409) {
                $error = $this->apiResponseData($response, 'No se puede eliminar el dispensario');
                return redirect()->back()
                    ->with('error', $error);
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar dispensario'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar dispensario', [
                'error' => $e->getMessage(),
                'dispensario_id' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar dispensario');
        }
    }

    /**
     * Obtener mangueras del dispensario
     */
    public function mangueras(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['medidor_id', 'estado', 'activas', 'per_page']);

            $response = $this->apiGet("/api/dispensarios/{$id}/mangueras", $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('dispensarios.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar mangueras'));
            }

            $mangueras = $this->apiResponseData($response, []);

            return view('dispensarios.mangueras', [
                'mangueras' => $mangueras['data'] ?? $mangueras,
                'dispensario_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar mangueras del dispensario', [
                'error' => $e->getMessage(),
                'dispensario_id' => $id
            ]);

            return redirect()->route('dispensarios.show', $id)
                ->with('error', 'Error al cargar mangueras');
        }
    }

    /**
     * Verificar estado del dispensario
     */
    public function verificarEstado($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/dispensarios/{$id}/verificar-estado");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('dispensarios.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al verificar estado'));
            }

            $estado = $this->apiResponseData($response, []);

            if (request()->expectsJson()) {
                return $this->jsonSuccess($estado, 'Estado verificado');
            }

            return view('dispensarios.estado', [
                'estado' => $estado,
                'dispensario_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al verificar estado del dispensario', [
                'error' => $e->getMessage(),
                'dispensario_id' => $id
            ]);

            return redirect()->route('dispensarios.show', $id)
                ->with('error', 'Error al verificar estado');
        }
    }
}