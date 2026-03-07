<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReporteSatController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar reportes SAT
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'instalacion_id', 'periodo', 'anio', 'mes', 'estado', 'per_page'
        ]);

        $response = $this->apiGet('/api/reportes-sat', $params);

        if ($this->apiResponseSuccessful($response)) {
            $reportes = $this->apiResponseData($response);
            return view('reportes-sat.index', compact('reportes'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones para el select
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        return view('reportes-sat.create', compact('instalaciones'));
    }

    /**
     * Crear reporte SAT
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'required|integer|exists:instalaciones,id',
            'periodo' => 'required|in:diario,semanal,quincenal,mensual',
            'anio' => 'required|integer|min:2020|max:2030',
            'mes' => 'required|integer|min:1|max:12',
            'semana' => 'nullable|integer|min:1|max:52',
            'dia' => 'nullable|integer|min:1|max:31',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'registros_generados' => 'required|integer|min:0',
            'registros_validos' => 'required|integer|min:0',
            'registros_invalidos' => 'required|integer|min:0',
            'estado' => 'required|in:generado,firmado,enviado,recibido,error',
            'observaciones' => 'nullable|string|max:500',
            'usuario_generacion' => 'required|integer|exists:users,id',
            'usuario_firma' => 'nullable|integer|exists:users,id',
            'usuario_envio' => 'nullable|integer|exists:users,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/reportes-sat', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('reportes-sat.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar reporte SAT
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/reportes-sat/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $reporte = $this->apiResponseData($response);
            return view('reportes-sat.show', compact('reporte'));
        }

        return redirect()->route('reportes-sat.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones para el select
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        $response = $this->apiGet("/api/reportes-sat/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $reporte = $this->apiResponseData($response);
            return view('reportes-sat.edit', compact('reporte', 'instalaciones'));
        }

        return redirect()->route('reportes-sat.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar reporte SAT
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'sometimes|integer|exists:instalaciones,id',
            'periodo' => 'sometimes|in:diario,semanal,quincenal,mensual',
            'anio' => 'sometimes|integer|min:2020|max:2030',
            'mes' => 'sometimes|integer|min:1|max:12',
            'semana' => 'nullable|integer|min:1|max:52',
            'dia' => 'nullable|integer|min:1|max:31',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin' => 'sometimes|date|after_or_equal:fecha_inicio',
            'registros_generados' => 'sometimes|integer|min:0',
            'registros_validos' => 'sometimes|integer|min:0',
            'registros_invalidos' => 'sometimes|integer|min:0',
            'estado' => 'sometimes|in:generado,firmado,enviado,recibido,error',
            'observaciones' => 'nullable|string|max:500',
            'usuario_generacion' => 'sometimes|integer|exists:users,id',
            'usuario_firma' => 'nullable|integer|exists:users,id',
            'usuario_envio' => 'nullable|integer|exists:users,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/reportes-sat/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('reportes-sat.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar reporte SAT
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/reportes-sat/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('reportes-sat.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('reportes-sat.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Firmar reporte SAT
     */
    public function firmar($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost("/api/reportes-sat/{$id}/firmar");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('reportes-sat.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('reportes-sat.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Enviar reporte SAT
     */
    public function enviar($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost("/api/reportes-sat/{$id}/enviar");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('reportes-sat.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('reportes-sat.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Descargar XML del reporte SAT
     */
    public function descargarXml($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/reportes-sat/{$id}/descargar-xml");

        if ($this->apiResponseSuccessful($response)) {
            $xmlContent = $this->apiResponseData($response);
            return response($xmlContent)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Disposition', 'attachment; filename="reporte-sat-' . $id . '.xml"');
        }

        return redirect()->route('reportes-sat.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Descargar PDF del reporte SAT
     */
    public function descargarPdf($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/reportes-sat/{$id}/descargar-pdf");

        if ($this->apiResponseSuccessful($response)) {
            $pdfContent = $this->apiResponseData($response);
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="reporte-sat-' . $id . '.pdf"');
        }

        return redirect()->route('reportes-sat.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener acuse del reporte SAT
     */
    public function acuse($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/reportes-sat/{$id}/acuse");

        if ($this->apiResponseSuccessful($response)) {
            $acuse = $this->apiResponseData($response);
            return view('reportes-sat.acuse', compact('acuse', 'id'));
        }

        return redirect()->route('reportes-sat.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }
}