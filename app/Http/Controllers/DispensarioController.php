<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
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
     * Almacenar dispensario
     */
    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'fabricante' => 'required|string|max:255',
            'instalacion_id' => 'required|integer',
            'estado' => 'required|in:OPERATIVO,MANTENIMIENTO,INACTIVO',
            'numero_serie' => 'nullable|string|max:255',
            'fecha_instalacion' => 'nullable|date',
            'fecha_ultimo_mantenimiento' => 'nullable|date',
            'fecha_proximo_mantenimiento' => 'nullable|date',
            'capacidad_maxima' => 'nullable|numeric|min:0',
            'presion_operacion' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/dispensarios', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $dispensarioData = $this->apiResponseData($response, []);
                $dispensarioId = $dispensarioData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'DISPENSARIO_CREADO',
                    'Dispensarios',
                    "Dispensario creado: {$request->clave}",
                    'dispensarios',
                    $dispensarioId
                );

                return redirect()->route('dispensarios.show', $dispensarioId)
                    ->with('success', 'Dispensario creado exitosamente');
            }

            if ($response['status'] === 422) {
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
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);

            return view('dispensarios.edit', [
                'dispensario' => $dispensario,
                'instalaciones' => $instalaciones
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
            'clave' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'fabricante' => 'required|string|max:255',
            'instalacion_id' => 'required|integer',
            'estado' => 'required|in:OPERATIVO,MANTENIMIENTO,INACTIVO',
            'numero_serie' => 'nullable|string|max:255',
            'fecha_instalacion' => 'nullable|date',
            'fecha_ultimo_mantenimiento' => 'nullable|date',
            'fecha_proximo_mantenimiento' => 'nullable|date',
            'capacidad_maxima' => 'nullable|numeric|min:0',
            'presion_operacion' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/dispensarios/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'DISPENSARIO_ACTUALIZADO',
                    'Dispensarios',
                    "Dispensario actualizado ID: {$id}",
                    'dispensarios',
                    $id
                );

                return redirect()->route('dispensarios.show', $id)
                    ->with('success', 'Dispensario actualizado exitosamente');
            }

            if ($response['status'] === 422) {
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
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'DISPENSARIO_ELIMINADO',
                    'Dispensarios',
                    "Dispensario eliminado ID: {$id}",
                    'dispensarios',
                    $id
                );

                return redirect()->route('dispensarios.index')
                    ->with('success', 'Dispensario eliminado exitosamente');
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
     * Exportar dispensarios
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener parámetros de filtro opcionales
            $params = $request->only([
                'instalacion_id', 'clave', 'modelo', 'fabricante',
                'estado', 'activo'
            ]);

            $modulo = 'dispensarios';
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
            $json = $response->json();
            return $this->jsonError(
                $json['message'] ?? 'Error al exportar dispensarios',
                $response->status(),
                $json['errors'] ?? null
            );
        } catch (\Exception $e) {
            Log::error('Error al exportar dispensarios', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al exportar dispensarios');
        }
    }
}
