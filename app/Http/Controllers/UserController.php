<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Http\Controllers\Traits\ValidacionEspanol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{
    use ValidacionEspanol;
    /**
     * Listar usuarios
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'identificacion', 'nombres', 'apellidos', 'email',
                'role_id', 'activo', 'bloqueados', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/users', $params);

            return $this->renderView('users.index', $response, ['key' => 'users'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar usuarios', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar usuarios');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Cargar roles para el formulario
            $roles = $this->getCatalog('/api/roles', ['activo' => true]);

            return view('users.create', [
                'roles' => $roles
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear usuario
     */
    public function store(Request $request)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasUsuario());
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/users', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $userData = $this->apiResponseData($response, []);
                $userId = $userData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'USUARIO_CREADO',
                    'Usuarios',
                    "Usuario creado: {$request->email}",
                    'users',
                    $userId
                );

                return redirect()->route('users.index')
                    ->with('success', 'Usuario creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear usuario'));

        } catch (\Exception $e) {
            Log::error('Error al crear usuario', [
                'error' => $e->getMessage(),
                'data' => $request->except('password', 'password_confirmation')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear usuario');
        }
    }

    /**
     * Mostrar usuario
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/users/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('users.index')
                    ->with('error', $this->apiResponseMessage($response, 'Usuario no encontrado'));
            }

            $user = $this->apiResponseData($response, []);

            return view('users.show', [
                'user' => $user
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar usuario', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Error al cargar usuario');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener datos del usuario
            $userResponse = $this->apiGet("/api/users/{$id}");

            if (!$this->apiResponseSuccessful($userResponse)) {
                return redirect()->route('users.index')
                    ->with('error', $this->apiResponseMessage($userResponse, 'Usuario no encontrado'));
            }

            // Obtener roles
            $roles = $this->getCatalog('/api/roles', ['activo' => true]);

            $user = $this->apiResponseData($userResponse, []);

            return view('users.edit', [
                'user' => $user,
                'roles' => $roles
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasUsuario(true));
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/users/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'USUARIO_ACTUALIZADO',
                    'Usuarios',
                    "Usuario actualizado: {$request->email}",
                    'users',
                    $id
                );

                return redirect()->route('users.show', $id)
                    ->with('success', 'Usuario actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar usuario'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar usuario');
        }
    }

    /**
     * Eliminar usuario (soft delete)
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/users/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'USUARIO_ELIMINADO',
                    'Usuarios',
                    "Usuario eliminado ID: {$id}",
                    'users',
                    $id
                );

                return redirect()->route('users.index')
                    ->with('success', 'Usuario eliminado exitosamente');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al eliminar usuario'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al eliminar usuario');
        }
    }

    /**
     * Bloquear usuario
     */
    public function bloquear(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:500',
            'minutos_bloqueo' => 'nullable|integer|min:1'
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/users/{$id}/bloquear", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_SEGURIDAD,
                    'USUARIO_BLOQUEADO',
                    'Seguridad',
                    "Usuario bloqueado ID: {$id} - Motivo: {$request->motivo}",
                    'users',
                    $id
                );

                return redirect()->route('users.show', $id)
                    ->with('success', 'Usuario bloqueado exitosamente');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al bloquear usuario'));

        } catch (\Exception $e) {
            Log::error('Error al bloquear usuario', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al bloquear usuario');
        }
    }

    /**
     * Desbloquear usuario
     */
    public function desbloquear(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/users/{$id}/desbloquear", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_SEGURIDAD,
                    'USUARIO_DESBLOQUEADO',
                    'Seguridad',
                    "Usuario desbloqueado ID: {$id} - Motivo: {$request->motivo}",
                    'users',
                    $id
                );

                return redirect()->route('users.show', $id)
                    ->with('success', 'Usuario desbloqueado exitosamente');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al desbloquear usuario'));

        } catch (\Exception $e) {
            Log::error('Error al desbloquear usuario', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al desbloquear usuario');
        }
    }

    /**
     * Asignar rol a usuario
     */
    public function asignarRol(Request $request, $id)
    {
        $request->validate([
            'rol_id' => 'required|integer'
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/users/{$id}/asignar-rol", [
                'rol_id' => $request->rol_id
            ]);

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'ROL_ASIGNADO',
                    'Usuarios',
                    "Rol asignado a usuario ID: {$id}",
                    'users',
                    $id
                );

                return redirect()->route('users.show', $id)
                    ->with('success', 'Rol asignado exitosamente');
            }

            if ($response['status'] === 409) {
                return redirect()->back()
                    ->with('error', 'El usuario ya tiene este rol asignado');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al asignar rol'));

        } catch (\Exception $e) {
            Log::error('Error al asignar rol', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'rol_id' => $request->rol_id
            ]);

            return redirect()->back()->with('error', 'Error al asignar rol');
        }
    }

    /**
     * Quitar rol a usuario
     */
    public function quitarRol(Request $request, $id)
    {
        $request->validate([
            'rol_id' => 'required|integer'
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/users/{$id}/quitar-rol", [
                'rol_id' => $request->rol_id
            ]);

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'ROL_REVOCADO',
                    'Usuarios',
                    "Rol revocado de usuario ID: {$id}",
                    'users',
                    $id
                );

                return redirect()->route('users.show', $id)
                    ->with('success', 'Rol revocado exitosamente');
            }

            if ($response['status'] === 404) {
                return redirect()->back()
                    ->with('error', 'El usuario no tiene este rol asignado');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al revocar rol'));

        } catch (\Exception $e) {
            Log::error('Error al revocar rol', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'rol_id' => $request->rol_id
            ]);

            return redirect()->back()->with('error', 'Error al revocar rol');
        }
    }

    /**
     * Obtener permisos del usuario
     */
    public function permisos($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/users/{$id}/permisos");

            if ($this->apiResponseSuccessful($response)) {
                $permisos = $this->apiResponseData($response, []);
                return view('users.permisos', compact('permisos', 'id'));
            }

            return redirect()->route('users.show', $id)
                ->with('error', $this->apiResponseMessage($response, 'Error al cargar permisos'));

        } catch (\Exception $e) {
            Log::error('Error al cargar permisos', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->route('users.show', $id)
                ->with('error', 'Error al cargar permisos');
        }
    }

    /**
     * Obtener actividad del usuario
     */
    public function actividad(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['fecha_inicio', 'fecha_fin', 'tipo_evento', 'per_page']);

            $response = $this->apiGet("/api/users/{$id}/actividad", $params);

            if ($this->apiResponseSuccessful($response)) {
                $actividad = $this->apiResponseData($response, []);
                return view('users.actividad', compact('actividad', $id));
            }

            return redirect()->route('users.show', $id)
                ->with('error', $this->apiResponseMessage($response, 'Error al cargar actividad'));
        } catch (\Exception $e) {
            Log::error('Error al cargar actividad', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return redirect()->route('users.show', $id)
                ->with('error', 'Error al cargar actividad');
        }
    }

    /**
     * Exportar usuarios
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener parámetros de filtro opcionales
            $params = $request->only([
                'name', 'email', 'role_id', 'status'
            ]);

            $response = $this->apiGetRaw('/api/users/exportar', $params);

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
                    $json['message'] ?? 'Error al exportar usuarios',
                    $response->status(),
                    $json['errors'] ?? null
                );
            }

            return $this->jsonError('Error al exportar usuarios', 500);
        } catch (\Exception $e) {
            Log::error('Error al exportar usuarios', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al exportar usuarios');
        }
    }
}