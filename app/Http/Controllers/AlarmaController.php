<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AlarmaController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar alarmas
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'instalacion_id', 'tipo_alarma', 'estado', 'fecha_inicio', 'fecha_fin', 'per_page'
        ]);

        $response = $this->apiGet('/api/alarmas', $params);

        if ($this->apiResponseSuccessful($response)) {
            $alarmas = $this->apiResponseData($response);
            return view('alarmas.index', compact('alarmas'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar alarma
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/alarmas/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $alarma = $this->apiResponseData($response);
            return view('alarmas.show', compact('alarma'));
        }

        return redirect()->route('alarmas.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Atender alarma
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'estado' => 'required|in:activa,atendida,descartada',
            'observaciones' => 'nullable|string|max:500',
            'usuario_atencion' => 'required|integer|exists:users,id',
            'fecha_atencion' => 'required|date',
            'hora_atencion' => 'required|date_format:H:i:s'
        ]);

        $response = $this->apiPost("/api/alarmas/{$id}/atender", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('alarmas.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar alarma
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/alarmas/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('alarmas.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('alarmas.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Dashboard de alarmas
     */
    public function dashboard()
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet('/api/alarmas/estadisticas/dashboard');

        if ($this->apiResponseSuccessful($response)) {
            $dashboard = $this->apiResponseData($response);
            return view('alarmas.dashboard', compact('dashboard'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Estadísticas de alarmas
     */
    public function estadisticas(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'fecha_inicio', 'fecha_fin', 'instalacion_id', 'tipo_alarma'
        ]);

        $response = $this->apiGet('/api/alarmas/estadisticas/reporte', $params);

        if ($this->apiResponseSuccessful($response)) {
            $estadisticas = $this->apiResponseData($response);
            return view('alarmas.estadisticas', compact('estadisticas'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }
}