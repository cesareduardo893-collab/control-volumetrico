<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Http\Controllers\Traits\ValidacionEspanol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CertificadoVerificacionController extends BaseController
{
    use ValidacionEspanol;
    /**
     * Listar certificados de verificación
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'contribuyente_id', 'folio', 'proveedor_rfc', 'resultado',
                'fecha_emision_inicio', 'fecha_emision_fin', 'vigente',
                'requiere_verificacion_extraordinaria', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/certificados-verificacion', $params);

            return $this->renderView('certificados-verificacion.index', $response, ['key' => 'certificados'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar certificados de verificación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar certificados');
        }
    }

    /**
     * Exportar certificados de verificación
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener parámetros de filtro opcionales
            $params = $request->only([
                'contribuyente_id', 'folio', 'proveedor_rfc', 'resultado',
                'fecha_emision_inicio', 'fecha_emision_fin', 'vigente',
                'requiere_verificacion_extraordinaria'
            ]);

            $response = $this->apiGetRaw('/api/certificados-verificacion/exportar', $params);

            if ($response && $response->successful()) {
                // Si la API devuelve un archivo, lo enviamos directamente
                $contentType = $response->headers->get('Content-Type');
                $contentDisposition = $response->headers->get('Content-Disposition');

                return response($response->body(), $response->status())
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', $contentDisposition);
            }

            // Si no es exitoso, manejamos el error
            $json = $response->json();
            return $this->jsonError(
                $json['message'] ?? 'Error al exportar certificados de verificación',
                $response->status(),
                $json['errors'] ?? null
            );
        } catch (\Exception $e) {
            Log::error('Error al exportar certificados de verificación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al exportar certificados de verificación');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener contribuyentes para el select
            $contribuyentes = $this->getCatalog('/api/catalogo/contribuyentes');

            return view('certificados-verificacion.create', [
                'contribuyentes' => $contribuyentes
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('certificados-verificacion.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear certificado de verificación
     */
    public function store(Request $request)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasCertificadoVerificacion());
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/certificados-verificacion', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $certificadoData = $this->apiResponseData($response, []);
                $certificadoId = $certificadoData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_VERIFICACIONES,
                    'verificacion',
                    'Verificación',
                    "Certificado de verificación creado: {$request->folio}",
                    'certificados_verificacion',
                    $certificadoId
                );

                return redirect()->route('certificados-verificacion.show', $certificadoId)
                    ->with('success', 'Certificado de verificación creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear certificado'));

        } catch (\Exception $e) {
            Log::error('Error al crear certificado de verificación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear certificado');
        }
    }

    /**
     * Mostrar certificado de verificación
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/certificados-verificacion/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('certificados-verificacion.index')
                    ->with('error', $this->apiResponseMessage($response, 'Certificado no encontrado'));
            }

            $certificado = $this->apiResponseData($response, []);

            return view('certificados-verificacion.show', [
                'certificado' => $certificado
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar certificado de verificación', [
                'error' => $e->getMessage(),
                'certificado_id' => $id
            ]);

            return redirect()->route('certificados-verificacion.index')
                ->with('error', 'Error al cargar certificado');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/certificados-verificacion/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('certificados-verificacion.index')
                    ->with('error', $this->apiResponseMessage($response, 'Certificado no encontrado'));
            }

            $certificado = $this->apiResponseData($response, []);

            return view('certificados-verificacion.edit', [
                'certificado' => $certificado
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'certificado_id' => $id
            ]);

            return redirect()->route('certificados-verificacion.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar certificado de verificación
     */
    public function update(Request $request, $id)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasCertificadoVerificacion(true));
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/certificados-verificacion/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_VERIFICACIONES,
                    'verificacion',
                    'Verificación',
                    "Certificado de verificación actualizado ID: {$id}",
                    'certificados_verificacion',
                    $id
                );

                return redirect()->route('certificados-verificacion.show', $id)
                    ->with('success', 'Certificado actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar certificado'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar certificado de verificación', [
                'error' => $e->getMessage(),
                'certificado_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar certificado');
        }
    }

    /**
     * Verificar vigencia del certificado
     */
    public function verificarVigencia($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/certificados-verificacion/{$id}/verificar-vigencia");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('certificados-verificacion.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al verificar vigencia'));
            }

            $resultado = $this->apiResponseData($response, []);

            if (request()->expectsJson()) {
                return $this->jsonSuccess($resultado, 'Vigencia verificada');
            }

            return view('certificados-verificacion.vigencia', [
                'resultado' => $resultado
            ]);

        } catch (\Exception $e) {
            Log::error('Error al verificar vigencia', [
                'error' => $e->getMessage(),
                'certificado_id' => $id
            ]);

            return redirect()->route('certificados-verificacion.show', $id)
                ->with('error', 'Error al verificar vigencia');
        }
    }

    /**
     * Obtener estadísticas de certificados
     */
    public function estadisticas(Request $request)
    {
        $request->validate([
            'contribuyente_id' => 'required|integer',
            'anio' => 'required|integer|min:2020',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/certificados-verificacion/estadisticas', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar estadísticas'));
            }

            $estadisticas = $this->apiResponseData($response, []);

            return view('certificados-verificacion.estadisticas', [
                'estadisticas' => $estadisticas,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas de certificados', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar estadísticas');
        }
    }
}