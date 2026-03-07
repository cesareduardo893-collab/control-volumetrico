<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContribuyenteController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar contribuyentes
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'rfc', 'razon_social', 'activo', 'caracter', 'permiso', 'per_page'
        ]);

        $response = $this->apiGet('/api/contribuyentes', $params);

        if ($this->apiResponseSuccessful($response)) {
            $contribuyentes = $this->apiResponseData($response);
            return view('contribuyentes.index', compact('contribuyentes'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('contribuyentes.create');
    }

    /**
     * Crear contribuyente
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'rfc' => 'required|string|size:13|unique:contribuyentes,rfc',
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'regimen_fiscal' => 'required|string|max:255',
            'domicilio_fiscal' => 'required|string|max:255',
            'codigo_postal' => 'required|string|size:5',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'representante_legal' => 'nullable|string|max:255',
            'representante_rfc' => 'nullable|string|size:13',
            'caracter_actua' => 'required|in:contratista,asignatario,permisionario,usuario',
            'numero_permiso' => 'nullable|string|max:255',
            'tipo_permiso' => 'nullable|string|max:255',
            'proveedor_equipos_rfc' => 'nullable|string|size:13',
            'proveedor_equipos_nombre' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/contribuyentes', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('contribuyentes.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar contribuyente
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/contribuyentes/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $contribuyente = $this->apiResponseData($response);
            return view('contribuyentes.show', compact('contribuyente'));
        }

        return redirect()->route('contribuyentes.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/contribuyentes/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $contribuyente = $this->apiResponseData($response);
            return view('contribuyentes.edit', compact('contribuyente'));
        }

        return redirect()->route('contribuyentes.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar contribuyente
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'rfc' => 'sometimes|string|size:13|unique:contribuyentes,rfc,' . $id,
            'razon_social' => 'sometimes|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'regimen_fiscal' => 'sometimes|string|max:255',
            'domicilio_fiscal' => 'sometimes|string|max:255',
            'codigo_postal' => 'sometimes|string|size:5',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'representante_legal' => 'nullable|string|max:255',
            'representante_rfc' => 'nullable|string|size:13',
            'caracter_actua' => 'sometimes|in:contratista,asignatario,permisionario,usuario',
            'numero_permiso' => 'nullable|string|max:255',
            'tipo_permiso' => 'nullable|string|max:255',
            'proveedor_equipos_rfc' => 'nullable|string|size:13',
            'proveedor_equipos_nombre' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/contribuyentes/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('contribuyentes.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar contribuyente
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/contribuyentes/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('contribuyentes.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('contribuyentes.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Restaurar contribuyente
     */
    public function restore($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost("/api/contribuyentes/{$id}/restore");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('contribuyentes.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('contribuyentes.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener instalaciones del contribuyente
     */
    public function instalaciones($id)
    {
        $this->setApiToken(session('api_token'));

        $params = request()->only(['per_page']);

        $response = $this->apiGet("/api/contribuyentes/{$id}/instalaciones", $params);

        if ($this->apiResponseSuccessful($response)) {
            $instalaciones = $this->apiResponseData($response);
            return view('contribuyentes.instalaciones', compact('instalaciones', 'id'));
        }

        return redirect()->route('contribuyentes.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener cumplimiento del contribuyente
     */
    public function cumplimiento($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/contribuyentes/{$id}/cumplimiento");

        if ($this->apiResponseSuccessful($response)) {
            $cumplimiento = $this->apiResponseData($response);
            return view('contribuyentes.cumplimiento', compact('cumplimiento', 'id'));
        }

        return redirect()->route('contribuyentes.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }
}