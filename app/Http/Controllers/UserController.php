<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
        $this->setApiToken(Session::get('api_token'));
    }

    public function index(Request $request)
    {
        $response = $this->apiGet('/api/users', $request->all());

        if (!$this->apiResponseSuccessful($response)) {
            return back()->with('error', 'Error al cargar usuarios');
        }

        $data = $this->apiResponseData($response);

        return view('users.index', [
            'users' => $data['data'] ?? [],
            'meta' => $data['meta'] ?? [],
            'filtros' => $request->all(),
        ]);
    }

    public function create()
    {
        // Cargar roles para el formulario
        $rolesResponse = $this->apiGet('/api/roles', ['per_page' => 100]);
        
        return view('users.create', [
            'roles' => $this->apiResponseData($rolesResponse)['data'] ?? [],
        ]);
    }

    public function store(Request $request)
    {
        $response = $this->apiPost('/api/users', $request->all());

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente');
        }

        return back()
            ->withInput()
            ->withErrors($response['errors'] ?? ['error' => $response['message'] ?? 'Error al crear']);
    }

    public function show($id)
    {
        $response = $this->apiGet("/api/users/{$id}");

        if (!$this->apiResponseSuccessful($response)) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Usuario no encontrado');
        }

        return view('users.show', [
            'user' => $this->apiResponseData($response),
        ]);
    }

    public function edit($id)
    {
        $response = $this->apiGet("/api/users/{$id}");
        $rolesResponse = $this->apiGet('/api/roles', ['per_page' => 100]);

        if (!$this->apiResponseSuccessful($response)) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Usuario no encontrado');
        }

        return view('users.edit', [
            'user' => $this->apiResponseData($response),
            'roles' => $this->apiResponseData($rolesResponse)['data'] ?? [],
        ]);
    }

    public function update(Request $request, $id)
    {
        $response = $this->apiPut("/api/users/{$id}", $request->all());

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('usuarios.show', $id)
                ->with('success', 'Usuario actualizado exitosamente');
        }

        return back()
            ->withInput()
            ->withErrors($response['errors'] ?? ['error' => $response['message'] ?? 'Error al actualizar']);
    }

    public function destroy($id)
    {
        $response = $this->apiDelete("/api/users/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente');
        }

        return back()->with('error', $response['message'] ?? 'Error al eliminar');
    }
}