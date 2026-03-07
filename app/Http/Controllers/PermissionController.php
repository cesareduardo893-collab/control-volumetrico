<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PermissionController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar permisos
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only(['per_page']);

        $response = $this->apiGet('/api/permissions', $params);

        if ($this->apiResponseSuccessful($response)) {
            $permisos = $this->apiResponseData($response);
            return view('permissions.index', compact('permisos'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));
        return view('permissions.create');
    }

    /**
     * Crear permiso
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        $response = $this->apiPost('/api/permissions', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('permissions.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar permiso
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/permissions/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $permiso = $this->apiResponseData($response);
            return view('permissions.show', compact('permiso'));
        }

        return redirect()->route('permissions.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/permissions/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $permiso = $this->apiResponseData($response);
            return view('permissions.edit', compact('permiso'));
        }

        return redirect()->route('permissions.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar permiso
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'name' => 'sometimes|string|max:255|unique:permissions,name,' . $id,
            'display_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        $response = $this->apiPut("/api/permissions/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('permissions.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar permiso
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/permissions/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('permissions.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('permissions.index')
            ->with('error', $this->apiResponseMessage($response));
    }
}