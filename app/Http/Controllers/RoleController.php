<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar roles
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only(['per_page']);

        $response = $this->apiGet('/api/roles', $params);

        if ($this->apiResponseSuccessful($response)) {
            $roles = $this->apiResponseData($response);
            return view('roles.index', compact('roles'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener permisos para el select múltiple
        $permisosResponse = $this->apiGet('/api/permissions');
        $permisos = $this->apiResponseSuccessful($permisosResponse) 
            ? $this->apiResponseData($permisosResponse) 
            : [];

        return view('roles.create', compact('permisos'));
    }

    /**
     * Crear rol
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permission_ids' => 'array',
            'permission_ids.*' => 'integer|exists:permissions,id'
        ]);

        $response = $this->apiPost('/api/roles', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('roles.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar rol
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/roles/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $role = $this->apiResponseData($response);
            return view('roles.show', compact('role'));
        }

        return redirect()->route('roles.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener permisos para el select múltiple
        $permisosResponse = $this->apiGet('/api/permissions');
        $permisos = $this->apiResponseSuccessful($permisosResponse) 
            ? $this->apiResponseData($permisosResponse) 
            : [];

        $response = $this->apiGet("/api/roles/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $role = $this->apiResponseData($response);
            return view('roles.edit', compact('role', 'permisos'));
        }

        return redirect()->route('roles.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'name' => 'sometimes|string|max:255|unique:roles,name,' . $id,
            'display_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:500',
            'permission_ids' => 'array',
            'permission_ids.*' => 'integer|exists:permissions,id'
        ]);

        $response = $this->apiPut("/api/roles/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('roles.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar rol
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/roles/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('roles.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('roles.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Asignar permisos a rol
     */
    public function asignarPermisos(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'integer|exists:permissions,id'
        ]);

        $response = $this->apiPost("/api/roles/{$id}/asignar-permisos", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('roles.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }
}