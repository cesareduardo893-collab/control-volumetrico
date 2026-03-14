<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PermissionController extends BaseController
{
    /**
     * Listar permisos
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['name', 'slug', 'modulo', 'activo', 'per_page', 'page']);

            $response = $this->apiGet('/api/permissions', $params);

            return $this->renderView('permissions.index', $response, ['key' => 'permisos'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar permisos', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar permisos');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Crear permiso
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'modulo' => 'required|string|max:100',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/permissions', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $permisoData = $this->apiResponseData($response, []);
                $permisoId = $permisoData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'PERMISO_CREADO',
                    'Permisos',
                    "Permiso creado: {$request->slug}",
                    'permissions',
                    $permisoId
                );

                return redirect()->route('permissions.index')
                    ->with('success', 'Permiso creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear permiso'));

        } catch (\Exception $e) {
            Log::error('Error al crear permiso', [
                'error' => $e->getMessage(),
                'data' => $request->except('_token')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear permiso');
        }
    }

    /**
     * Mostrar permiso
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/permissions/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('permissions.index')
                    ->with('error', $this->apiResponseMessage($response, 'Permiso no encontrado'));
            }

            $permiso = $this->apiResponseData($response, []);

            return view('permissions.show', [
                'permiso' => $permiso
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar permiso', [
                'error' => $e->getMessage(),
                'permiso_id' => $id
            ]);

            return redirect()->route('permissions.index')
                ->with('error', 'Error al cargar permiso');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/permissions/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('permissions.index')
                    ->with('error', $this->apiResponseMessage($response, 'Permiso no encontrado'));
            }

            $permiso = $this->apiResponseData($response, []);

            return view('permissions.edit', [
                'permiso' => $permiso
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'permiso_id' => $id
            ]);

            return redirect()->route('permissions.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar permiso
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:500',
            'modulo' => 'sometimes|string|max:100',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/permissions/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'PERMISO_ACTUALIZADO',
                    'Permisos',
                    "Permiso actualizado ID: {$id}",
                    'permissions',
                    $id
                );

                return redirect()->route('permissions.show', $id)
                    ->with('success', 'Permiso actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar permiso'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar permiso', [
                'error' => $e->getMessage(),
                'permiso_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar permiso');
        }
    }

    /**
     * Eliminar permiso (soft delete)
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/permissions/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'PERMISO_ELIMINADO',
                    'Permisos',
                    "Permiso eliminado ID: {$id}",
                    'permissions',
                    $id
                );

                return redirect()->route('permissions.index')
                    ->with('success', 'Permiso eliminado exitosamente');
            }

            if ($response['status'] === 409) {
                return redirect()->back()
                    ->with('error', $this->apiResponseData($response, 'No se puede eliminar el permiso'));
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al eliminar permiso'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar permiso', [
                'error' => $e->getMessage(),
                'permiso_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al eliminar permiso');
        }
    }

    /**
     * Obtener permisos por módulo
     */
    public function porModulo()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/permissions/por-modulo');

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar permisos'));
            }

            $permisos = $this->apiResponseData($response, []);

            return view('permissions.por-modulo', [
                'permisosPorModulo' => $permisos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar permisos por módulo', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar permisos');
        }
    }

    /**
     * Verificar permiso
     */
    public function verificar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'permiso_slug' => 'required|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/permissions/verificar', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $resultado = $this->apiResponseData($response, []);

                if ($request->expectsJson()) {
                    return $this->jsonSuccess($resultado, 'Verificación completada');
                }

                return view('permissions.verificar', [
                    'resultado' => $resultado
                ]);
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al verificar permiso'));

        } catch (\Exception $e) {
            Log::error('Error al verificar permiso', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al verificar permiso');
        }
    }
}