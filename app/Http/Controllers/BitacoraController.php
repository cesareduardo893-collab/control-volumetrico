<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BitacoraController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar bitácora de eventos
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'usuario_id', 'tipo_evento', 'fecha_inicio', 'fecha_fin', 'per_page'
        ]);

        $response = $this->apiGet('/api/bitacora', $params);

        if ($this->apiResponseSuccessful($response)) {
            $eventos = $this->apiResponseData($response);
            return view('bitacora.index', compact('eventos'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar evento de bitácora
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/bitacora/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $evento = $this->apiResponseData($response);
            return view('bitacora.show', compact('evento'));
        }

        return redirect()->route('bitacora.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Filtrar eventos por rango de fechas
     */
    public function filtrar(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_evento' => 'nullable|string',
            'usuario_id' => 'nullable|integer',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $response = $this->apiGet('/api/bitacora/filtrar', $params);

        if ($this->apiResponseSuccessful($response)) {
            $eventos = $this->apiResponseData($response);
            return view('bitacora.index', compact('eventos'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Exportar bitácora a CSV
     */
    public function exportarCsv(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'fecha_inicio', 'fecha_fin', 'tipo_evento', 'usuario_id'
        ]);

        $response = $this->apiGet('/api/bitacora/exportar-csv', $params);

        if ($this->apiResponseSuccessful($response)) {
            $csvContent = $this->apiResponseData($response);
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="bitacora-' . date('Y-m-d') . '.csv"');
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Dashboard de bitácora
     */
    public function dashboard()
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet('/api/bitacora/dashboard');

        if ($this->apiResponseSuccessful($response)) {
            $dashboard = $this->apiResponseData($response);
            return view('bitacora.dashboard', compact('dashboard'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }
}