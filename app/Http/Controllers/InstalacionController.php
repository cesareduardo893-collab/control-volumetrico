<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InstalacionController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar instalaciones
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'contribuyente_id', 'clave_instalacion', 'nombre', 'tipo_instalacion', 'activo', 'per_page'
        ]);

        $response = $this->apiGet('/api/instalaciones', $params);

        if ($this->apiResponseSuccessful($response)) {
            $instalaciones = $this->apiResponseData($response);
            return view('instalaciones.index', compact('instalaciones'));
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

        return view('instalaciones.create', compact('contribuyentes'));
    }

    /**
     * Crear instalación
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'contribuyente_id' => 'required|integer|exists:contribuyentes,id',
            'clave_instalacion' => 'required|string|max:50|unique:instalaciones,clave_instalacion',
            'nombre' => 'required|string|max:255',
            'tipo_instalacion' => 'required|in:estacion_servicio,almacenamiento,transporte',
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'required|string|size:5',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'horario_atencion' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean',
            // Configuración de red
            'ip_servidor' => 'nullable|ip',
            'puerto_servidor' => 'nullable|integer|min:1|max:65535',
            'protocolo_comunicacion' => 'nullable|in:TCP,UDP,HTTP,HTTPS',
            'intervalo_comunicacion' => 'nullable|integer|min:1',
            'timeout_comunicacion' => 'nullable|integer|min:1',
            // Configuración de alarmas
            'umbral_temperatura_min' => 'nullable|numeric',
            'umbral_temperatura_max' => 'nullable|numeric',
            'umbral_presion_min' => 'nullable|numeric',
            'umbral_presion_max' => 'nullable|numeric',
            'umbral_nivel_min' => 'nullable|numeric',
            'umbral_nivel_max' => 'nullable|numeric',
            'umbral_flujo_min' => 'nullable|numeric',
            'umbral_flujo_max' => 'nullable|numeric'
        ]);

        $response = $this->apiPost('/api/instalaciones', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('instalaciones.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar instalación
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/instalaciones/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $instalacion = $this->apiResponseData($response);
            return view('instalaciones.show', compact('instalacion'));
        }

        return redirect()->route('instalaciones.index')
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

        $response = $this->apiGet("/api/instalaciones/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $instalacion = $this->apiResponseData($response);
            return view('instalaciones.edit', compact('instalacion', 'contribuyentes'));
        }

        return redirect()->route('instalaciones.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar instalación
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'contribuyente_id' => 'sometimes|integer|exists:contribuyentes,id',
            'clave_instalacion' => 'sometimes|string|max:50|unique:instalaciones,clave_instalacion,' . $id,
            'nombre' => 'sometimes|string|max:255',
            'tipo_instalacion' => 'sometimes|in:estacion_servicio,almacenamiento,transporte',
            'domicilio' => 'sometimes|string|max:255',
            'codigo_postal' => 'sometimes|string|size:5',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'horario_atencion' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean',
            // Configuración de red
            'ip_servidor' => 'nullable|ip',
            'puerto_servidor' => 'nullable|integer|min:1|max:65535',
            'protocolo_comunicacion' => 'nullable|in:TCP,UDP,HTTP,HTTPS',
            'intervalo_comunicacion' => 'nullable|integer|min:1',
            'timeout_comunicacion' => 'nullable|integer|min:1',
            // Configuración de alarmas
            'umbral_temperatura_min' => 'nullable|numeric',
            'umbral_temperatura_max' => 'nullable|numeric',
            'umbral_presion_min' => 'nullable|numeric',
            'umbral_presion_max' => 'nullable|numeric',
            'umbral_nivel_min' => 'nullable|numeric',
            'umbral_nivel_max' => 'nullable|numeric',
            'umbral_flujo_min' => 'nullable|numeric',
            'umbral_flujo_max' => 'nullable|numeric'
        ]);

        $response = $this->apiPut("/api/instalaciones/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('instalaciones.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar instalación
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/instalaciones/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('instalaciones.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('instalaciones.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener tanques de la instalación
     */
    public function tanques($id)
    {
        $this->setApiToken(session('api_token'));

        $params = request()->only(['per_page']);

        $response = $this->apiGet("/api/instalaciones/{$id}/tanques", $params);

        if ($this->apiResponseSuccessful($response)) {
            $tanques = $this->apiResponseData($response);
            return view('instalaciones.tanques', compact('tanques', 'id'));
        }

        return redirect()->route('instalaciones.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener medidores de la instalación
     */
    public function medidores($id)
    {
        $this->setApiToken(session('api_token'));

        $params = request()->only(['per_page']);

        $response = $this->apiGet("/api/instalaciones/{$id}/medidores", $params);

        if ($this->apiResponseSuccessful($response)) {
            $medidores = $this->apiResponseData($response);
            return view('instalaciones.medidores', compact('medidores', 'id'));
        }

        return redirect()->route('instalaciones.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener dispensarios de la instalación
     */
    public function dispensarios($id)
    {
        $this->setApiToken(session('api_token'));

        $params = request()->only(['per_page']);

        $response = $this->apiGet("/api/instalaciones/{$id}/dispensarios", $params);

        if ($this->apiResponseSuccessful($response)) {
            $dispensarios = $this->apiResponseData($response);
            return view('instalaciones.dispensarios', compact('dispensarios', 'id'));
        }

        return redirect()->route('instalaciones.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Verificar comunicación de la instalación
     */
    public function verificarComunicacion($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/instalaciones/{$id}/verificar-comunicacion");

        if ($this->apiResponseSuccessful($response)) {
            $comunicacion = $this->apiResponseData($response);
            return view('instalaciones.comunicacion', compact('comunicacion', 'id'));
        }

        return redirect()->route('instalaciones.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener resumen operativo de la instalación
     */
    public function resumenOperativo($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/instalaciones/{$id}/resumen-operativo");

        if ($this->apiResponseSuccessful($response)) {
            $resumen = $this->apiResponseData($response);
            return view('instalaciones.resumen', compact('resumen', 'id'));
        }

        return redirect()->route('instalaciones.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar configuración de red
     */
    public function actualizarConfiguracionRed(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'ip_servidor' => 'required|ip',
            'puerto_servidor' => 'required|integer|min:1|max:65535',
            'protocolo_comunicacion' => 'required|in:TCP,UDP,HTTP,HTTPS',
            'intervalo_comunicacion' => 'required|integer|min:1',
            'timeout_comunicacion' => 'required|integer|min:1'
        ]);

        $response = $this->apiPut("/api/instalaciones/{$id}/configuracion-red", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('instalaciones.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar umbrales de alarma
     */
    public function actualizarUmbralesAlarma(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'umbral_temperatura_min' => 'required|numeric',
            'umbral_temperatura_max' => 'required|numeric',
            'umbral_presion_min' => 'required|numeric',
            'umbral_presion_max' => 'required|numeric',
            'umbral_nivel_min' => 'required|numeric',
            'umbral_nivel_max' => 'required|numeric',
            'umbral_flujo_min' => 'required|numeric',
            'umbral_flujo_max' => 'required|numeric'
        ]);

        $response = $this->apiPut("/api/instalaciones/{$id}/umbrales-alarma", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('instalaciones.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener reporte de cumplimiento normativo
     */
    public function reporteCumplimientoNormativo($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/instalaciones/{$id}/reporte-cumplimiento");

        if ($this->apiResponseSuccessful($response)) {
            $reporte = $this->apiResponseData($response);
            return view('instalaciones.reporte-cumplimiento', compact('reporte', 'id'));
        }

        return redirect()->route('instalaciones.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }
}