<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExistenciaController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar existencias
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'tanque_id', 'producto_id', 'fecha_medicion', 'activo', 'per_page'
        ]);

        $response = $this->apiGet('/api/existencias', $params);

        if ($this->apiResponseSuccessful($response)) {
            $existencias = $this->apiResponseData($response);
            return view('existencias.index', compact('existencias'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener tanques y productos para los selects
        $tanquesResponse = $this->apiGet('/api/tanques');
        $tanques = $this->apiResponseSuccessful($tanquesResponse) 
            ? $this->apiResponseData($tanquesResponse) 
            : [];

        $productosResponse = $this->apiGet('/api/productos');
        $productos = $this->apiResponseSuccessful($productosResponse) 
            ? $this->apiResponseData($productosResponse) 
            : [];

        return view('existencias.create', compact('tanques', 'productos'));
    }

    /**
     * Crear existencia
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'tanque_id' => 'required|integer|exists:tanques,id',
            'producto_id' => 'required|integer|exists:productos,id',
            'fecha_medicion' => 'required|date',
            'hora_medicion' => 'required|date_format:H:i:s',
            'volumen_bruto' => 'required|numeric|min:0',
            'volumen_neto' => 'required|numeric|min:0',
            'temperatura' => 'required|numeric',
            'densidad' => 'required|numeric|min:0',
            'factor_correccion' => 'required|numeric|min:0',
            'nivel_agua' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
            'metodo_medicion' => 'required|in:manual,automatica',
            'estado' => 'required|in:valida,invalida,pendiente',
            'usuario_id' => 'required|integer|exists:users,id',
            'medidor_id' => 'nullable|integer|exists:medidores,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/existencias', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('existencias.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar existencia
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/existencias/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $existencia = $this->apiResponseData($response);
            return view('existencias.show', compact('existencia'));
        }

        return redirect()->route('existencias.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener tanques y productos para los selects
        $tanquesResponse = $this->apiGet('/api/tanques');
        $tanques = $this->apiResponseSuccessful($tanquesResponse) 
            ? $this->apiResponseData($tanquesResponse) 
            : [];

        $productosResponse = $this->apiGet('/api/productos');
        $productos = $this->apiResponseSuccessful($productosResponse) 
            ? $this->apiResponseData($productosResponse) 
            : [];

        $response = $this->apiGet("/api/existencias/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $existencia = $this->apiResponseData($response);
            return view('existencias.edit', compact('existencia', 'tanques', 'productos'));
        }

        return redirect()->route('existencias.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar existencia
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'tanque_id' => 'sometimes|integer|exists:tanques,id',
            'producto_id' => 'sometimes|integer|exists:productos,id',
            'fecha_medicion' => 'sometimes|date',
            'hora_medicion' => 'sometimes|date_format:H:i:s',
            'volumen_bruto' => 'sometimes|numeric|min:0',
            'volumen_neto' => 'sometimes|numeric|min:0',
            'temperatura' => 'sometimes|numeric',
            'densidad' => 'sometimes|numeric|min:0',
            'factor_correccion' => 'sometimes|numeric|min:0',
            'nivel_agua' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
            'metodo_medicion' => 'sometimes|in:manual,automatica',
            'estado' => 'sometimes|in:valida,invalida,pendiente',
            'usuario_id' => 'sometimes|integer|exists:users,id',
            'medidor_id' => 'nullable|integer|exists:medidores,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/existencias/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('existencias.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar existencia
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/existencias/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('existencias.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('existencias.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Validar existencia
     */
    public function validar($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost("/api/existencias/{$id}/validar");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('existencias.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('existencias.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Asociar CFDI a existencia
     */
    public function asociarCfdi(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'cfdi_id' => 'required|integer|exists:cfdi,id'
        ]);

        $response = $this->apiPost("/api/existencias/{$id}/asociar-cfdi", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('existencias.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Asociar pedimento a existencia
     */
    public function asociarPedimento(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'pedimento_id' => 'required|integer|exists:pedimentos,id'
        ]);

        $response = $this->apiPost("/api/existencias/{$id}/asociar-pedimento", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('existencias.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener inventario diario
     */
    public function inventarioDiario(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only(['fecha', 'instalacion_id', 'producto_id']);

        $response = $this->apiGet('/api/existencias/reporte/inventario-diario', $params);

        if ($this->apiResponseSuccessful($response)) {
            $inventario = $this->apiResponseData($response);
            return view('existencias.inventario-diario', compact('inventario'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }
}