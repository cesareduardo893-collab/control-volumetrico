<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MedidorController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar medidores
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'tanque_id', 'dispensario_id', 'clave_medidor', 'activo', 'per_page'
        ]);

        $response = $this->apiGet('/api/medidores', $params);

        if ($this->apiResponseSuccessful($response)) {
            $medidores = $this->apiResponseData($response);
            return view('medidores.index', compact('medidores'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener tanques y dispensarios para los selects
        $tanquesResponse = $this->apiGet('/api/tanques');
        $tanques = $this->apiResponseSuccessful($tanquesResponse) 
            ? $this->apiResponseData($tanquesResponse) 
            : [];

        $dispensariosResponse = $this->apiGet('/api/dispensarios');
        $dispensarios = $this->apiResponseSuccessful($dispensariosResponse) 
            ? $this->apiResponseData($dispensariosResponse) 
            : [];

        return view('medidores.create', compact('tanques', 'dispensarios'));
    }

    /**
     * Crear medidor
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'tanque_id' => 'nullable|integer|exists:tanques,id',
            'dispensario_id' => 'nullable|integer|exists:dispensarios,id',
            'clave_medidor' => 'required|string|max:50|unique:medidores,clave_medidor',
            'nombre' => 'required|string|max:255',
            'tipo_medidor' => 'required|in:flotador,ultrasonico,radar,electromagnetico',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serie' => 'required|string|max:100|unique:medidores,serie',
            'rango_medicion_min' => 'required|numeric|min:0',
            'rango_medicion_max' => 'required|numeric|min:0',
            'precision' => 'required|numeric|min:0',
            'unidad_medida' => 'required|in:litros,galones,barriles',
            'resolucion' => 'required|numeric|min:0',
            'frecuencia_muestreo' => 'nullable|integer|min:1',
            'tipo_comunicacion' => 'required|in:analogica,digital,rs485,rs232,ethernet',
            'protocolo_comunicacion' => 'nullable|string|max:50',
            'direccion_comunicacion' => 'nullable|string|max:50',
            'estado_calibracion' => 'required|in:calibrado,no_calibrado,pendiente_calibracion',
            'fecha_calibracion' => 'nullable|date',
            'fecha_proxima_calibracion' => 'nullable|date',
            'activo' => 'sometimes|boolean',
            // Configuración de alarmas
            'umbral_flujo_min' => 'nullable|numeric|min:0',
            'umbral_flujo_max' => 'nullable|numeric|min:0',
            'umbral_presion_min' => 'nullable|numeric',
            'umbral_presion_max' => 'nullable|numeric',
            'umbral_temperatura_min' => 'nullable|numeric',
            'umbral_temperatura_max' => 'nullable|numeric'
        ]);

        $response = $this->apiPost('/api/medidores', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('medidores.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar medidor
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/medidores/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $medidor = $this->apiResponseData($response);
            return view('medidores.show', compact('medidor'));
        }

        return redirect()->route('medidores.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener tanques y dispensarios para los selects
        $tanquesResponse = $this->apiGet('/api/tanques');
        $tanques = $this->apiResponseSuccessful($tanquesResponse) 
            ? $this->apiResponseData($tanquesResponse) 
            : [];

        $dispensariosResponse = $this->apiGet('/api/dispensarios');
        $dispensarios = $this->apiResponseSuccessful($dispensariosResponse) 
            ? $this->apiResponseData($dispensariosResponse) 
            : [];

        $response = $this->apiGet("/api/medidores/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $medidor = $this->apiResponseData($response);
            return view('medidores.edit', compact('medidor', 'tanques', 'dispensarios'));
        }

        return redirect()->route('medidores.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar medidor
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'tanque_id' => 'nullable|integer|exists:tanques,id',
            'dispensario_id' => 'nullable|integer|exists:dispensarios,id',
            'clave_medidor' => 'sometimes|string|max:50|unique:medidores,clave_medidor,' . $id,
            'nombre' => 'sometimes|string|max:255',
            'tipo_medidor' => 'sometimes|in:flotador,ultrasonico,radar,electromagnetico',
            'marca' => 'sometimes|string|max:100',
            'modelo' => 'sometimes|string|max:100',
            'serie' => 'sometimes|string|max:100|unique:medidores,serie,' . $id,
            'rango_medicion_min' => 'sometimes|numeric|min:0',
            'rango_medicion_max' => 'sometimes|numeric|min:0',
            'precision' => 'sometimes|numeric|min:0',
            'unidad_medida' => 'sometimes|in:litros,galones,barriles',
            'resolucion' => 'sometimes|numeric|min:0',
            'frecuencia_muestreo' => 'nullable|integer|min:1',
            'tipo_comunicacion' => 'sometimes|in:analogica,digital,rs485,rs232,ethernet',
            'protocolo_comunicacion' => 'nullable|string|max:50',
            'direccion_comunicacion' => 'nullable|string|max:50',
            'estado_calibracion' => 'sometimes|in:calibrado,no_calibrado,pendiente_calibracion',
            'fecha_calibracion' => 'nullable|date',
            'fecha_proxima_calibracion' => 'nullable|date',
            'activo' => 'sometimes|boolean',
            // Configuración de alarmas
            'umbral_flujo_min' => 'nullable|numeric|min:0',
            'umbral_flujo_max' => 'nullable|numeric|min:0',
            'umbral_presion_min' => 'nullable|numeric',
            'umbral_presion_max' => 'nullable|numeric',
            'umbral_temperatura_min' => 'nullable|numeric',
            'umbral_temperatura_max' => 'nullable|numeric'
        ]);

        $response = $this->apiPut("/api/medidores/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('medidores.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar medidor
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/medidores/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('medidores.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('medidores.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Calibrar medidor
     */
    public function calibrar(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'valor_referencia' => 'required|numeric|min:0',
            'valor_medido' => 'required|numeric|min:0',
            'factor_correccion' => 'required|numeric',
            'observaciones' => 'nullable|string|max:500',
            'tecnico_calibracion' => 'required|string|max:255',
            'fecha_calibracion' => 'required|date'
        ]);

        $response = $this->apiPost("/api/medidores/{$id}/calibrar", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('medidores.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener tanques por instalación
     */
    public function getTanquesByInstalacion($instalacionId)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/medidores/tanques/{$instalacionId}");

        if ($this->apiResponseSuccessful($response)) {
            return response()->json($this->apiResponseData($response));
        }

        return response()->json([
            'success' => false,
            'message' => $this->apiResponseMessage($response)
        ], 500);
    }
}