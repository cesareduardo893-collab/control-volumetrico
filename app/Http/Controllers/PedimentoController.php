<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PedimentoController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar pedimentos
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'contribuyente_id', 'aduana', 'patente', 'estado', 'fecha_inicio', 'fecha_fin', 'per_page'
        ]);

        $response = $this->apiGet('/api/pedimentos', $params);

        if ($this->apiResponseSuccessful($response)) {
            $pedimentos = $this->apiResponseData($response);
            return view('pedimentos.index', compact('pedimentos'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener contribuyentes para el select
        $contribuyentesResponse = $this->apiGet('/api/contribuyentes');
        $contribuyentes = $this->apiResponseSuccessful($contribuyentesResponse) 
            ? $this->apiResponseData($contribuyentesResponse) 
            : [];

        return view('pedimentos.create', compact('contribuyentes'));
    }

    /**
     * Crear pedimento
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'contribuyente_id' => 'required|integer|exists:contribuyentes,id',
            'numero_pedimento' => 'required|string|max:21|unique:pedimentos,numero_pedimento',
            'aduana' => 'required|string|max:3',
            'patente' => 'required|string|max:4',
            'ejercicio' => 'required|integer|min:2020|max:2030',
            'fecha_importacion' => 'required|date',
            'tipo_cambio' => 'required|numeric|min:0',
            'peso_bruto' => 'required|numeric|min:0',
            'peso_neto' => 'required|numeric|min:0',
            'volumen' => 'required|numeric|min:0',
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad_importada' => 'required|numeric|min:0',
            'cantidad_despachada' => 'required|numeric|min:0',
            'cantidad_pendiente' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,liquidado,cancelado',
            'observaciones' => 'nullable|string|max:500',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/pedimentos', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('pedimentos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar pedimento
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/pedimentos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $pedimento = $this->apiResponseData($response);
            return view('pedimentos.show', compact('pedimento'));
        }

        return redirect()->route('pedimentos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener contribuyentes para el select
        $contribuyentesResponse = $this->apiGet('/api/contribuyentes');
        $contribuyentes = $this->apiResponseSuccessful($contribuyentesResponse) 
            ? $this->apiResponseData($contribuyentesResponse) 
            : [];

        $response = $this->apiGet("/api/pedimentos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $pedimento = $this->apiResponseData($response);
            return view('pedimentos.edit', compact('pedimento', 'contribuyentes'));
        }

        return redirect()->route('pedimentos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar pedimento
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'contribuyente_id' => 'sometimes|integer|exists:contribuyentes,id',
            'numero_pedimento' => 'sometimes|string|max:21|unique:pedimentos,numero_pedimento,' . $id,
            'aduana' => 'sometimes|string|max:3',
            'patente' => 'sometimes|string|max:4',
            'ejercicio' => 'sometimes|integer|min:2020|max:2030',
            'fecha_importacion' => 'sometimes|date',
            'tipo_cambio' => 'sometimes|numeric|min:0',
            'peso_bruto' => 'sometimes|numeric|min:0',
            'peso_neto' => 'sometimes|numeric|min:0',
            'volumen' => 'sometimes|numeric|min:0',
            'producto_id' => 'sometimes|integer|exists:productos,id',
            'cantidad_importada' => 'sometimes|numeric|min:0',
            'cantidad_despachada' => 'sometimes|numeric|min:0',
            'cantidad_pendiente' => 'sometimes|numeric|min:0',
            'estado' => 'sometimes|in:activo,liquidado,cancelado',
            'observaciones' => 'nullable|string|max:500',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/pedimentos/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('pedimentos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar pedimento
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/pedimentos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('pedimentos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('pedimentos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Asociar registro volumétrico al pedimento
     */
    public function asociarRegistro(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'registro_volumetrico_id' => 'required|integer|exists:registros_volumetricos,id'
        ]);

        $response = $this->apiPost("/api/pedimentos/{$id}/asociar-registro", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('pedimentos.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }
}