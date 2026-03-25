<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Http\Controllers\Traits\ValidacionEspanol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AlarmaController extends BaseController
{
    use ValidacionEspanol;
    /**
     * Listar alarmas
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'componente_tipo', 'componente_id', 'tipo_alarma_id', 'gravedad',
                'atendida', 'estado_atencion', 'requiere_atencion_inmediata',
                'fecha_inicio', 'fecha_fin', 'numero_registro', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/alarmas', $params);

            return $this->renderView('alarmas.index', $response, ['key' => 'alarmas'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar alarmas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar alarmas');
        }
    }

    /**
     * Exportar alarmas
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener parámetros de filtro opcionales
            $params = $request->only([
                'componente_tipo', 'componente_id', 'tipo_alarma_id', 'gravedad',
                'atendida', 'estado_atencion', 'requiere_atencion_inmediata',
                'fecha_inicio', 'fecha_fin', 'numero_registro'
            ]);

            $modulo = 'alarmas';
            $response = $this->apiGetRaw('/api/exportar/' . $modulo, $params);

            if ($response && $response->successful()) {
                // Si la API devuelve un archivo, lo enviamos directamente
                $contentType = $response->headers->get('Content-Type');
                $contentDisposition = $response->headers->get('Content-Disposition');

                return response($response->body(), $response->status())
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', $contentDisposition);
            }

            // Si no es exitoso, manejamos el error
            if ($response) {
                $json = $response->json();
                return $this->jsonError(
                    $json['message'] ?? 'Error al exportar alarmas',
                    $response->status(),
                    $json['errors'] ?? null
                );
            }

            return $this->jsonError('Error al exportar alarmas', 500);
        } catch (\Exception $e) {
            Log::error('Error al exportar alarmas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al exportar alarmas');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener tipos de alarma del catálogo
            $tiposAlarma = $this->getCatalog('/api/catalogos', ['tipo' => 'tipo_alarma']);
            
            // Si el catálogo no devuelve datos, usar valores predeterminados
            if (empty($tiposAlarma)) {
                $tiposAlarma = [
                    ['id' => 1, 'nombre' => 'Nivel Alto'],
                    ['id' => 2, 'nombre' => 'Nivel Bajo'],
                    ['id' => 3, 'nombre' => 'Presión Alta'],
                    ['id' => 4, 'nombre' => 'Presión Baja'],
                    ['id' => 5, 'nombre' => 'Temperatura Alta'],
                    ['id' => 6, 'nombre' => 'Temperatura Baja'],
                    ['id' => 7, 'nombre' => 'Fuga Detectada'],
                    ['id' => 8, 'nombre' => 'Válvula Abierta'],
                    ['id' => 9, 'nombre' => 'Válvula Cerrada'],
                    ['id' => 10, 'nombre' => 'Fallo de Sensor']
                ];
            }

            return view('alarmas.create', [
                'tiposAlarma' => $tiposAlarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear alarma
     */
    public function store(Request $request)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasAlarma());
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/alarmas', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $alarmaData = $this->apiResponseData($response, []);
                $alarmaId = $alarmaData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_SEGURIDAD,
                    'ALARMA_CREADA',
                    'Alarmas',
                    "Alarma creada: {$request->numero_registro}",
                    'alarmas',
                    $alarmaId
                );

                return redirect()->route('alarmas.show', $alarmaId)
                    ->with('success', 'Alarma creada exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear alarma'));

        } catch (\Exception $e) {
            Log::error('Error al crear alarma', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear alarma');
        }
    }

    /**
     * Mostrar alarma
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            return view('alarmas.show', [
                'alarma' => $alarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar alarma');
        }
    }

    /**
     * Atender alarma
     */
    public function atender(Request $request, $id)
    {
        $request->validate([
            'acciones_tomadas' => 'required|string',
            'estado_atencion' => 'required|in:EN_PROCESO,RESUELTA,IGNORADA',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/alarmas/{$id}/atender", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_SEGURIDAD,
                    'ALARMA_ATENDIDA',
                    'Alarmas',
                    "Alarma atendida ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.show', $id)
                    ->with('success', 'Alarma atendida exitosamente');
            }

            if ($response['status'] === 403) {
                return redirect()->back()
                    ->with('error', 'La alarma ya ha sido atendida');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al atender alarma'));

        } catch (\Exception $e) {
            Log::error('Error al atender alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al atender alarma');
        }
    }

    /**
     * Actualizar estado de alarma
     */
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado_atencion' => 'required|in:PENDIENTE,EN_PROCESO,RESUELTA,IGNORADA',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/alarmas/{$id}/actualizar-estado", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_SEGURIDAD,
                    'ESTADO_ALARMA_ACTUALIZADO',
                    'Alarmas',
                    "Estado de alarma actualizado ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.show', $id)
                    ->with('success', 'Estado de alarma actualizado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar estado'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar estado');
        }
    }

    /**
     * Obtener estadísticas de alarmas
     */
    public function estadisticas(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/alarmas/estadisticas', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar estadísticas'));
            }

            $estadisticas = $this->apiResponseData($response, []);

            return view('alarmas.estadisticas', [
                'estadisticas' => $estadisticas,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas de alarmas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar estadísticas');
        }
    }

    /**
     * Obtener alarmas activas
     */
    public function activas(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['componente_tipo', 'componente_id', 'gravedad']);

            $response = $this->apiGet('/api/alarmas/activas', $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar alarmas activas'));
            }

            $alarmas = $this->apiResponseData($response, []);

            if ($request->expectsJson()) {
                return $this->jsonSuccess($alarmas, 'Alarmas activas obtenidas');
            }

            return view('alarmas.activas', [
                'alarmas' => $alarmas
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener alarmas activas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar alarmas activas');
        }
    }

    /**
     * Mostrar formulario para atender alarma
     */
    public function atenderForm($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            return view('alarmas.atender', [
                'alarma' => $alarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de atención', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario de atención');
        }
    }

    /**
     * Mostrar formulario para actualizar estado de alarma
     */
    public function actualizarEstadoForm($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            return view('alarmas.actualizar-estado', [
                'alarma' => $alarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de actualización', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario de actualización');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/alarmas/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('alarmas.index')
                    ->with('error', $this->apiResponseMessage($response, 'Alarma no encontrada'));
            }

            $alarma = $this->apiResponseData($response, []);

            // Obtener tipos de alarma del catálogo
            $tiposAlarma = $this->getCatalog('/api/catalogos', ['tipo' => 'tipo_alarma']);

            return view('alarmas.edit', [
                'alarma' => $alarma,
                'tiposAlarma' => $tiposAlarma
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->route('alarmas.index')
                ->with('error', 'Error al cargar formulario de edición');
        }
    }

    /**
     * Actualizar alarma
     */
    public function update(Request $request, $id)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasAlarma(true));
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/alarmas/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_SEGURIDAD,
                    'ALARMA_ACTUALIZADA',
                    'Alarmas',
                    "Alarma actualizada ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.show', $id)
                    ->with('success', 'Alarma actualizada exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar alarma'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar alarma');
        }
    }

    /**
     * Eliminar alarma
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/alarmas/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_SEGURIDAD,
                    'ALARMA_ELIMINADA',
                    'Alarmas',
                    "Alarma eliminada ID: {$id}",
                    'alarmas',
                    $id
                );

                return redirect()->route('alarmas.index')
                    ->with('success', 'Alarma eliminada exitosamente');
            }

            return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al eliminar alarma'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar alarma', [
                'error' => $e->getMessage(),
                'alarma_id' => $id
            ]);

            return redirect()->back()->with('error', 'Error al eliminar alarma');
        }
    }
}