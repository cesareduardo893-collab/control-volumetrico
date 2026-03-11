<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ReporteSatController extends BaseController
{
    /**
     * Listar reportes SAT
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'instalacion_id', 'usuario_genera_id', 'folio', 'periodo',
                'tipo_reporte', 'estado', 'fecha_generacion_inicio',
                'fecha_generacion_fin', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/reportes-sat', $params);

            return $this->renderView('reportes-sat.index', $response, ['key' => 'reportes'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar reportes SAT', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar reportes');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener instalaciones para el select
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);

            return view('reportes-sat.create', [
                'instalaciones' => $instalaciones
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('reportes-sat.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear reporte SAT
     */
    public function store(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'folio' => 'required|string|max:255',
            'periodo' => 'required|string|size:7',
            'tipo_reporte' => 'required|in:MENSUAL,ANUAL,ESPECIAL',
            'estado' => 'required|in:PENDIENTE,GENERADO,FIRMADO,ENVIADO,ACEPTADO,RECHAZADO,ERROR,REQUIERE_REENVIO',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $data = $request->all();
            $data['usuario_genera_id'] = Session::get('user_id');
            $data['fecha_generacion'] = now()->toDateString();

            $response = $this->apiPost('/api/reportes-sat', $data);

            if ($this->apiResponseSuccessful($response)) {
                $reporteData = $this->apiResponseData($response, []);
                $reporteId = $reporteData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    'reportes_sat',
                    'REPORTE_SAT_CREADO',
                    'Reportes SAT',
                    "Reporte SAT creado: {$request->folio}",
                    'reportes_sat',
                    $reporteId
                );

                return redirect()->route('reportes-sat.show', $reporteId)
                    ->with('success', 'Reporte SAT creado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear reporte'));

        } catch (\Exception $e) {
            Log::error('Error al crear reporte SAT', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear reporte');
        }
    }

    /**
     * Mostrar reporte SAT
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/reportes-sat/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('reportes-sat.index')
                    ->with('error', $this->apiResponseMessage($response, 'Reporte no encontrado'));
            }

            $reporte = $this->apiResponseData($response, []);

            return view('reportes-sat.show', [
                'reporte' => $reporte
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar reporte SAT', [
                'error' => $e->getMessage(),
                'reporte_id' => $id
            ]);

            return redirect()->route('reportes-sat.index')
                ->with('error', 'Error al cargar reporte');
        }
    }

    /**
     * Enviar reporte al SAT
     */
    public function enviar(Request $request, $id)
    {
        $request->validate([
            'fecha_envio' => 'required|date',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/reportes-sat/{$id}/enviar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'reportes_sat',
                    'REPORTE_SAT_ENVIADO',
                    'Reportes SAT',
                    "Reporte SAT enviado ID: {$id}",
                    'reportes_sat',
                    $id
                );

                return redirect()->route('reportes-sat.show', $id)
                    ->with('success', 'Reporte enviado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al enviar reporte'));

        } catch (\Exception $e) {
            Log::error('Error al enviar reporte SAT', [
                'error' => $e->getMessage(),
                'reporte_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al enviar reporte');
        }
    }

    /**
     * Firmar reporte
     */
    public function firmar(Request $request, $id)
    {
        $request->validate([
            'cadena_original' => 'required|string',
            'sello_digital' => 'required|string',
            'certificado_sat' => 'required|string',
            'fecha_firma' => 'required|date',
            'folio_firma' => 'required|string|size:36',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/reportes-sat/{$id}/firmar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'reportes_sat',
                    'REPORTE_SAT_FIRMADO',
                    'Reportes SAT',
                    "Reporte SAT firmado ID: {$id}",
                    'reportes_sat',
                    $id
                );

                return redirect()->route('reportes-sat.show', $id)
                    ->with('success', 'Reporte firmado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al firmar reporte'));

        } catch (\Exception $e) {
            Log::error('Error al firmar reporte SAT', [
                'error' => $e->getMessage(),
                'reporte_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al firmar reporte');
        }
    }

    /**
     * Cancelar reporte
     */
    public function cancelar(Request $request, $id)
    {
        $request->validate([
            'motivo_cancelacion' => 'required|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/reportes-sat/{$id}/cancelar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'reportes_sat',
                    'REPORTE_SAT_CANCELADO',
                    'Reportes SAT',
                    "Reporte SAT cancelado ID: {$id}",
                    'reportes_sat',
                    $id
                );

                return redirect()->route('reportes-sat.show', $id)
                    ->with('success', 'Reporte cancelado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al cancelar reporte'));

        } catch (\Exception $e) {
            Log::error('Error al cancelar reporte SAT', [
                'error' => $e->getMessage(),
                'reporte_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al cancelar reporte');
        }
    }

    /**
     * Obtener historial de envíos
     */
    public function historialEnvios(Request $request, $instalacionId)
    {
        $request->validate([
            'anio' => 'required|integer|min:2020',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/reportes-sat/historial-envios/{$instalacionId}", $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar historial'));
            }

            $historial = $this->apiResponseData($response, []);

            return view('reportes-sat.historial', [
                'historial' => $historial,
                'instalacionId' => $instalacionId,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener historial de envíos', [
                'error' => $e->getMessage(),
                'instalacion_id' => $instalacionId
            ]);

            return redirect()->back()->with('error', 'Error al cargar historial');
        }
    }
}