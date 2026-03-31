<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
                'fecha_generacion_fin', 'per_page', 'page',
            ]);

            $response = $this->apiGet('/api/reportes-sat', $params);

            return $this->renderView('reportes-sat.index', $response, ['key' => 'reportes'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar reportes SAT', [
                'error' => $e->getMessage(),
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
                'instalaciones' => $instalaciones,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('reportes-sat.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear reporte SAT (Generación automática)
     */
    public function store(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'periodo' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'tipo_reporte' => 'nullable|in:MENSUAL,ANUAL,ESPECIAL',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $data = [
                'instalacion_id' => $request->instalacion_id,
                'periodo' => $request->periodo,
                'tipo_reporte' => $request->tipo_reporte ?? 'MENSUAL',
            ];

            $response = $this->apiPost('/api/reportes-sat/generar', $data);

            if ($this->apiResponseSuccessful($response)) {
                $reporteData = $this->apiResponseData($response, []);
                $reporteId = $reporteData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'REPORTE_SAT_CREADO',
                    'Reportes SAT',
                    'Reporte SAT generado: '.($reporteData['folio'] ?? $reporteId),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    $reporteId
                );

                return redirect()->route('reportes-sat.show', $reporteId)
                    ->with('success', 'Reporte SAT generado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);

                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al generar reporte'));

        } catch (\Exception $e) {
            Log::error('Error al generar reporte SAT', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al generar reporte: '.$e->getMessage());
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

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->route('reportes-sat.index')
                    ->with('error', $this->apiResponseMessage($response, 'Reporte no encontrado'));
            }

            $reporte = $this->apiResponseData($response, []);

            return view('reportes-sat.show', [
                'reporte' => $reporte,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar reporte SAT', [
                'error' => $e->getMessage(),
                'reporte_id' => $id,
            ]);

            return redirect()->route('reportes-sat.index')
                ->with('error', 'Error al cargar reporte');
        }
    }

    /**
     * Firmar reporte (automático)
     */
    public function firmar(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/reportes-sat/{$id}/firmar", []);

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'REPORTE_SAT_FIRMADO',
                    'Reportes SAT',
                    "Reporte SAT firmado ID: {$id}",
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    $id
                );

                return redirect()->route('reportes-sat.show', $id)
                    ->with('success', 'Reporte firmado exitosamente');
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al firmar reporte'));

        } catch (\Exception $e) {
            Log::error('Error al firmar reporte SAT', [
                'error' => $e->getMessage(),
                'reporte_id' => $id,
            ]);

            return redirect()->back()
                ->with('error', 'Error al firmar reporte: '.$e->getMessage());
        }
    }

    /**
     * Enviar reporte al SAT (automático)
     */
    public function enviar(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/reportes-sat/{$id}/enviar-sat", []);

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'REPORTE_SAT_ENVIADO',
                    'Reportes SAT',
                    "Reporte SAT enviado ID: {$id}",
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    $id
                );

                return redirect()->route('reportes-sat.show', $id)
                    ->with('success', 'Reporte enviado al SAT exitosamente');
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al enviar reporte'));

        } catch (\Exception $e) {
            Log::error('Error al enviar reporte SAT', [
                'error' => $e->getMessage(),
                'reporte_id' => $id,
            ]);

            return redirect()->back()
                ->with('error', 'Error al enviar reporte: '.$e->getMessage());
        }
    }

    /**
     * Generar reporte anual
     */
    public function generarAnual(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'anio' => 'required|integer|min:2020',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/reportes-sat/generar-anual', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $data = $this->apiResponseData($response, []);

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'REPORTE_ANUAL_GENERADO',
                    'Reportes SAT',
                    "Reportes anuales generados para año {$request->anio}",
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    null
                );

                return redirect()->route('reportes-sat.index')
                    ->with('success', "Se generaron {$data['total_reportes']} reportes para el año {$request->anio}");
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al generar reportes anuales'));

        } catch (\Exception $e) {
            Log::error('Error al generar reportes anuales', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Error al generar reportes anuales: '.$e->getMessage());
        }
    }

    /**
     * Descargar XML del reporte
     */
    public function descargarXml($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/reportes-sat/{$id}/xml");

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', 'Reporte no encontrado');
            }

            $contenido = $this->apiResponseData($response, []);

            return response($contenido, 200, [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => 'attachment; filename="reporte_sat_'.$id.'.xml"',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al descargar XML');
        }
    }

    /**
     * Descargar acuse del SAT
     */
    public function descargarAcuse($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/reportes-sat/{$id}/acuse");

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', 'Acuse no encontrado');
            }

            $contenido = $this->apiResponseData($response, []);

            return response($contenido, 200, [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => 'attachment; filename="acuse_'.$id.'.xml"',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al descargar acuse');
        }
    }

    /**
     * Exportar reportes
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $tipo = $request->get('tipo', 'excel');
            $params = $request->all();
            $params['per_page'] = 1000;

            $response = $this->apiGet('/api/reportes-sat', $params);

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', 'Error al obtener datos');
            }

            $reportes = $this->apiResponseData($response, []);

            if ($tipo === 'pdf') {
                $pdf = \PDF::loadView('reportes-sat.pdf', ['reportes' => $reportes]);

                return $pdf->download('reportes_sat_'.date('Ymd').'.pdf');
            }

            return \Excel::download(new \App\Exports\ReporteSatExport($reportes),
                'reportes_sat_'.date('Ymd').'.xlsx');

        } catch (\Exception $e) {
            Log::error('Error al exportar reportes SAT', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Error al exportar');
        }
    }
}
