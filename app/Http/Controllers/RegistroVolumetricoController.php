<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegistroVolumetricoController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar registros volumétricos
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'instalacion_id', 'tanque_id', 'producto_id', 'tipo_movimiento', 
            'fecha_inicio', 'fecha_fin', 'estado', 'per_page'
        ]);

        $response = $this->apiGet('/api/registros-volumetricos', $params);

        if ($this->apiResponseSuccessful($response)) {
            $registros = $this->apiResponseData($response);
            return view('registros-volumetricos.index', compact('registros'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones, tanques y productos para los selects
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        $tanquesResponse = $this->apiGet('/api/tanques');
        $tanques = $this->apiResponseSuccessful($tanquesResponse) 
            ? $this->apiResponseData($tanquesResponse) 
            : [];

        $productosResponse = $this->apiGet('/api/productos');
        $productos = $this->apiResponseSuccessful($productosResponse) 
            ? $this->apiResponseData($productosResponse) 
            : [];

        return view('registros-volumetricos.create', compact('instalaciones', 'tanques', 'productos'));
    }

    /**
     * Crear registro volumétrico
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'required|integer|exists:instalaciones,id',
            'tanque_id' => 'required|integer|exists:tanques,id',
            'producto_id' => 'required|integer|exists:productos,id',
            'tipo_movimiento' => 'required|in:entrada,salida,trasiego,ajuste',
            'volumen_bruto' => 'required|numeric|min:0',
            'volumen_neto' => 'required|numeric|min:0',
            'temperatura' => 'required|numeric',
            'densidad' => 'required|numeric|min:0',
            'factor_correccion' => 'required|numeric|min:0',
            'fecha_movimiento' => 'required|date',
            'hora_movimiento' => 'required|date_format:H:i:s',
            'observaciones' => 'nullable|string|max:500',
            'usuario_id' => 'required|integer|exists:users,id',
            'medidor_id' => 'nullable|integer|exists:medidores,id',
            'dispensario_id' => 'nullable|integer|exists:dispensarios,id',
            'manguera_id' => 'nullable|integer|exists:mangueras,id',
            'estado' => 'required|in:registrado,validado,anulado',
            'motivo_anulacion' => 'nullable|string|max:500',
            'dictamen_id' => 'nullable|integer|exists:dictamenes,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/registros-volumetricos', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar registro volumétrico
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/registros-volumetricos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $registro = $this->apiResponseData($response);
            return view('registros-volumetricos.show', compact('registro'));
        }

        return redirect()->route('registros-volumetricos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones, tanques y productos para los selects
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        $tanquesResponse = $this->apiGet('/api/tanques');
        $tanques = $this->apiResponseSuccessful($tanquesResponse) 
            ? $this->apiResponseData($tanquesResponse) 
            : [];

        $productosResponse = $this->apiGet('/api/productos');
        $productos = $this->apiResponseSuccessful($productosResponse) 
            ? $this->apiResponseData($productosResponse) 
            : [];

        $response = $this->apiGet("/api/registros-volumetricos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $registro = $this->apiResponseData($response);
            return view('registros-volumetricos.edit', compact('registro', 'instalaciones', 'tanques', 'productos'));
        }

        return redirect()->route('registros-volumetricos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar registro volumétrico
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'sometimes|integer|exists:instalaciones,id',
            'tanque_id' => 'sometimes|integer|exists:tanques,id',
            'producto_id' => 'sometimes|integer|exists:productos,id',
            'tipo_movimiento' => 'sometimes|in:entrada,salida,trasiego,ajuste',
            'volumen_bruto' => 'sometimes|numeric|min:0',
            'volumen_neto' => 'sometimes|numeric|min:0',
            'temperatura' => 'sometimes|numeric',
            'densidad' => 'sometimes|numeric|min:0',
            'factor_correccion' => 'sometimes|numeric|min:0',
            'fecha_movimiento' => 'sometimes|date',
            'hora_movimiento' => 'sometimes|date_format:H:i:s',
            'observaciones' => 'nullable|string|max:500',
            'usuario_id' => 'sometimes|integer|exists:users,id',
            'medidor_id' => 'nullable|integer|exists:medidores,id',
            'dispensario_id' => 'nullable|integer|exists:dispensarios,id',
            'manguera_id' => 'nullable|integer|exists:mangueras,id',
            'estado' => 'sometimes|in:registrado,validado,anulado',
            'motivo_anulacion' => 'nullable|string|max:500',
            'dictamen_id' => 'nullable|integer|exists:dictamenes,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/registros-volumetricos/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar registro volumétrico
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/registros-volumetricos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('registros-volumetricos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Validar registro volumétrico
     */
    public function validar($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost("/api/registros-volumetricos/{$id}/validar");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('registros-volumetricos.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Asociar CFDI a registro volumétrico
     */
    public function asociarCfdi(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'cfdi_id' => 'required|integer|exists:cfdi,id'
        ]);

        $response = $this->apiPost("/api/registros-volumetricos/{$id}/asociar-cfdi", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Asociar pedimento a registro volumétrico
     */
    public function asociarPedimento(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'pedimento_id' => 'required|integer|exists:pedimentos,id'
        ]);

        $response = $this->apiPost("/api/registros-volumetricos/{$id}/asociar-pedimento", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Marcar registro con alarma
     */
    public function marcarConAlarma(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'alarma_id' => 'required|integer|exists:alarmas,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $response = $this->apiPost("/api/registros-volumetricos/{$id}/marcar-con-alarma", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Cancelar registro volumétrico
     */
    public function cancelar(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'motivo_cancelacion' => 'required|string|max:500',
            'usuario_cancelacion' => 'required|integer|exists:users,id'
        ]);

        $response = $this->apiPost("/api/registros-volumetricos/{$id}/cancelar", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('registros-volumetricos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener resumen diario
     */
    public function resumenDiario($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/registros-volumetricos/{$id}/resumen-diario");

        if ($this->apiResponseSuccessful($response)) {
            $resumen = $this->apiResponseData($response);
            return view('registros-volumetricos.resumen-diario', compact('resumen', 'id'));
        }

        return redirect()->route('registros-volumetricos.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener estadísticas mensuales
     */
    public function estadisticasMensuales($id)
    {
        $this->setApiToken(session('api_token'));

        $params = request()->only(['mes', 'anio']);

        $response = $this->apiGet("/api/registros-volumetricos/{$id}/estadisticas-mensuales", $params);

        if ($this->apiResponseSuccessful($response)) {
            $estadisticas = $this->apiResponseData($response);
            return view('registros-volumetricos.estadisticas-mensuales', compact('estadisticas', 'id'));
        }

        return redirect()->route('registros-volumetricos.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }
}