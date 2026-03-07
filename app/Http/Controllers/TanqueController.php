<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TanqueController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar tanques
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'instalacion_id', 'clave_tanque', 'producto_id', 'activo', 'per_page'
        ]);

        $response = $this->apiGet('/api/tanques', $params);

        if ($this->apiResponseSuccessful($response)) {
            $tanques = $this->apiResponseData($response);
            return view('tanques.index', compact('tanques'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones y productos para los selects
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        $productosResponse = $this->apiGet('/api/productos');
        $productos = $this->apiResponseSuccessful($productosResponse) 
            ? $this->apiResponseData($productosResponse) 
            : [];

        return view('tanques.create', compact('instalaciones', 'productos'));
    }

    /**
     * Crear tanque
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'required|integer|exists:instalaciones,id',
            'producto_id' => 'required|integer|exists:productos,id',
            'clave_tanque' => 'required|string|max:50|unique:tanques,clave_tanque',
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|numeric|min:0',
            'capacidad_operativa' => 'required|numeric|min:0',
            'capacidad_seguridad' => 'required|numeric|min:0',
            'diametro' => 'nullable|numeric|min:0',
            'longitud' => 'nullable|numeric|min:0',
            'altura_total' => 'nullable|numeric|min:0',
            'altura_operativa' => 'nullable|numeric|min:0',
            'altura_seguridad' => 'nullable|numeric|min:0',
            'forma' => 'required|in:cilindrico,rectangular,esferico',
            'tipo_tanque' => 'required|in:atmosferico,soterrado,areometro',
            'material' => 'required|string|max:100',
            'ubicacion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'activo' => 'sometimes|boolean',
            // Configuración de medición
            'tipo_medicion' => 'required|in:manual,automatica',
            'unidad_medida' => 'required|in:litros,galones,barriles',
            'precision_medicion' => 'nullable|numeric|min:0',
            'factor_correccion' => 'nullable|numeric|min:0',
            // Configuración de alarmas
            'umbral_nivel_min' => 'nullable|numeric|min:0',
            'umbral_nivel_max' => 'nullable|numeric|min:0',
            'umbral_temperatura_min' => 'nullable|numeric',
            'umbral_temperatura_max' => 'nullable|numeric',
            'umbral_presion_min' => 'nullable|numeric',
            'umbral_presion_max' => 'nullable|numeric'
        ]);

        $response = $this->apiPost('/api/tanques', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('tanques.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar tanque
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/tanques/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $tanque = $this->apiResponseData($response);
            return view('tanques.show', compact('tanque'));
        }

        return redirect()->route('tanques.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones y productos para los selects
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        $productosResponse = $this->apiGet('/api/productos');
        $productos = $this->apiResponseSuccessful($productosResponse) 
            ? $this->apiResponseData($productosResponse) 
            : [];

        $response = $this->apiGet("/api/tanques/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $tanque = $this->apiResponseData($response);
            return view('tanques.edit', compact('tanque', 'instalaciones', 'productos'));
        }

        return redirect()->route('tanques.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar tanque
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'sometimes|integer|exists:instalaciones,id',
            'producto_id' => 'sometimes|integer|exists:productos,id',
            'clave_tanque' => 'sometimes|string|max:50|unique:tanques,clave_tanque,' . $id,
            'nombre' => 'sometimes|string|max:255',
            'capacidad' => 'sometimes|numeric|min:0',
            'capacidad_operativa' => 'sometimes|numeric|min:0',
            'capacidad_seguridad' => 'sometimes|numeric|min:0',
            'diametro' => 'nullable|numeric|min:0',
            'longitud' => 'nullable|numeric|min:0',
            'altura_total' => 'nullable|numeric|min:0',
            'altura_operativa' => 'nullable|numeric|min:0',
            'altura_seguridad' => 'nullable|numeric|min:0',
            'forma' => 'sometimes|in:cilindrico,rectangular,esferico',
            'tipo_tanque' => 'sometimes|in:atmosferico,soterrado,areometro',
            'material' => 'sometimes|string|max:100',
            'ubicacion' => 'sometimes|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'activo' => 'sometimes|boolean',
            // Configuración de medición
            'tipo_medicion' => 'sometimes|in:manual,automatica',
            'unidad_medida' => 'sometimes|in:litros,galones,barriles',
            'precision_medicion' => 'nullable|numeric|min:0',
            'factor_correccion' => 'nullable|numeric|min:0',
            // Configuración de alarmas
            'umbral_nivel_min' => 'nullable|numeric|min:0',
            'umbral_nivel_max' => 'nullable|numeric|min:0',
            'umbral_temperatura_min' => 'nullable|numeric',
            'umbral_temperatura_max' => 'nullable|numeric',
            'umbral_presion_min' => 'nullable|numeric',
            'umbral_presion_max' => 'nullable|numeric'
        ]);

        $response = $this->apiPut("/api/tanques/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('tanques.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar tanque
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/tanques/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('tanques.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('tanques.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener existencias del tanque
     */
    public function existencias($id)
    {
        $this->setApiToken(session('api_token'));

        $params = request()->only(['per_page', 'fecha_inicio', 'fecha_fin']);

        $response = $this->apiGet("/api/tanques/{$id}/existencias", $params);

        if ($this->apiResponseSuccessful($response)) {
            $existencias = $this->apiResponseData($response);
            return view('tanques.existencias', compact('existencias', 'id'));
        }

        return redirect()->route('tanques.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener última existencia del tanque
     */
    public function ultimaExistencia($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/tanques/{$id}/ultima-existencia");

        if ($this->apiResponseSuccessful($response)) {
            $existencia = $this->apiResponseData($response);
            return view('tanques.ultima-existencia', compact('existencia', 'id'));
        }

        return redirect()->route('tanques.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }
}