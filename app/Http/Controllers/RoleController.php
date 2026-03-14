<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RoleController extends BaseController
{
    /**
     * Listar roles
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'nombre', 'activo', 'es_administrador', 'nivel_minimo', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/roles', $params);

            return $this->renderView('roles.index', $response, ['key' => 'roles'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar roles', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar roles');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener permisos para el select múltiple
            $permisosResponse = $this->apiGet('/api/permissions', ['per_page' => 500, 'activo' => true]);

            if ($this->apiResponseSuccessful($permisosResponse)) {
                $permisosData = $this->apiResponseData($permisosResponse, []);
                $permisos = $permisosData['data'] ?? $permisosData;
            } else {
                $permisos = [];
            }

            return view('roles.create', [
                'permisos' => $permisos,
                'modulos' => $this->agruparPermisosPorModulo($permisos)
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('roles.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear rol
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'nivel_jerarquico' => 'required|integer|min:1|max:100',
            'es_administrador' => 'sometimes|boolean',
            'permisos' => 'nullable|array',
            'permisos.*' => 'integer',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/roles', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $roleData = $this->apiResponseData($response, []);
                $roleId = $roleData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'ROL_CREADO',
                    'Roles',
                    "Rol creado: {$request->nombre}",
                    'roles',
                    $roleId
                );

                return redirect()->route('roles.index')
                    ->with('success', 'Rol creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear rol'));

        } catch (\Exception $e) {
            Log::error('Error al crear rol', [
                'error' => $e->getMessage(),
                'data' => $request->except('_token')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear rol');
        }
    }

    /**
     * Mostrar rol
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/roles/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('roles.index')
                    ->with('error', $this->apiResponseMessage($response, 'Rol no encontrado'));
            }

            $role = $this->apiResponseData($response, []);

            return view('roles.show', [
                'role' => $role,
                'permisosAgrupados' => $this->agruparPermisosPorModulo($role['permissions'] ?? [])
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar rol', [
                'error' => $e->getMessage(),
                'role_id' => $id
            ]);

            return redirect()->route('roles.index')
                ->with('error', 'Error al cargar rol');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener datos del rol
            $roleResponse = $this->apiGet("/api/roles/{$id}");

            if (!$this->apiResponseSuccessful($roleResponse)) {
                return redirect()->route('roles.index')
                    ->with('error', $this->apiResponseMessage($roleResponse, 'Rol no encontrado'));
            }

            // Obtener permisos
            $permisosResponse = $this->apiGet('/api/permissions', ['per_page' => 500, 'activo' => true]);

            if ($this->apiResponseSuccessful($permisosResponse)) {
                $permisosData = $this->apiResponseData($permisosResponse, []);
                $permisos = $permisosData['data'] ?? $permisosData;
            } else {
                $permisos = [];
            }

            $role = $this->apiResponseData($roleResponse, []);

            // Obtener IDs de permisos actuales
            $permisosActuales = collect($role['permissions'] ?? [])->pluck('id')->toArray();

            return view('roles.edit', [
                'role' => $role,
                'permisos' => $permisos,
                'modulos' => $this->agruparPermisosPorModulo($permisos),
                'permisosActuales' => $permisosActuales
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'role_id' => $id
            ]);

            return redirect()->route('roles.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'nivel_jerarquico' => 'sometimes|integer|min:1|max:100',
            'es_administrador' => 'sometimes|boolean',
            'permisos' => 'nullable|array',
            'permisos.*' => 'integer',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/roles/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'ROL_ACTUALIZADO',
                    'Roles',
                    "Rol actualizado ID: {$id}",
                    'roles',
                    $id
                );

                return redirect()->route('roles.show', $id)
                    ->with('success', 'Rol actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar rol'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar rol', [
                'error' => $e->getMessage(),
                'role_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar rol');
        }
    }

    /**
     * Eliminar rol (soft delete)
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/roles/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'ROL_ELIMINADO',
                    'Roles',
                    "Rol eliminado ID: {$id}",
                    'roles',
                    $id
                );

                return redirect()->route('roles.index')
                    ->with('success', 'Rol eliminado exitosamente');
            }

            if ($response['status'] === 409) {
                return redirect()->back()
                    ->with('error', $this->apiResponseData($response, 'No se puede eliminar el rol'));
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al eliminar rol'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar rol', [
                'error' => $e->getMessage(),
                'role_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al eliminar rol');
        }
    }

    /**
     * Asignar permisos al rol
     */
    public function asignarPermisos(Request $request, $id)
    {
        $request->validate([
            'permisos' => 'required|array',
            'permisos.*' => 'integer',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/roles/{$id}/asignar-permisos", [
                'permisos' => $request->permisos
            ]);

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'PERMISOS_ASIGNADOS',
                    'Roles',
                    "Permisos asignados a rol ID: {$id}",
                    'roles',
                    $id
                );

                return redirect()->route('roles.show', $id)
                    ->with('success', 'Permisos asignados exitosamente');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al asignar permisos'));

        } catch (\Exception $e) {
            Log::error('Error al asignar permisos', [
                'error' => $e->getMessage(),
                'role_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al asignar permisos');
        }
    }

    /**
     * Clonar rol
     */
    public function clonar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'incluir_permisos' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/roles/{$id}/clonar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $roleData = $this->apiResponseData($response, []);
                $newRoleId = $roleData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'ROL_CLONADO',
                    'Roles',
                    "Rol clonado desde ID: {$id} a nuevo ID: {$newRoleId}",
                    'roles',
                    $newRoleId
                );

                return redirect()->route('roles.show', $newRoleId)
                    ->with('success', 'Rol clonado exitosamente');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al clonar rol'));

        } catch (\Exception $e) {
            Log::error('Error al clonar rol', [
                'error' => $e->getMessage(),
                'role_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al clonar rol');
        }
    }

    /**
     * Obtener matriz de permisos
     */
    public function matrizPermisos()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/roles/matriz-permisos');

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar matriz'));
            }

            $matriz = $this->apiResponseData($response, []);

            return view('roles.matriz-permisos', [
                'roles' => $matriz['roles'] ?? [],
                'permisos' => $matriz['permisos'] ?? [],
                'matriz' => $matriz['matriz'] ?? []
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar matriz de permisos', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar matriz de permisos');
        }
    }

    /**
     * Agrupa permisos por módulo
     */
    private function agruparPermisosPorModulo($permisos)
    {
        return collect($permisos)->groupBy('modulo')->map(function ($items, $modulo) {
            return [
                'modulo' => $modulo,
                'permisos' => $items->map(function ($p) {
                    return [
                        'id' => $p['id'],
                        'name' => $p['name'],
                        'slug' => $p['slug'],
                        'description' => $p['description'] ?? '',
                    ];
                })->values()
            ];
        })->values();
    }
}