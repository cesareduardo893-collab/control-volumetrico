<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TanqueController extends BaseController
{
    /**
     * Listar tanques
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'instalacion_id', 'producto_id', 'identificador', 'numero_serie',
                'estado', 'tipo_tanque_id', 'activo', 'calibracion_proxima',
                'alerta_alteracion', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/tanques', $params);

            return $this->renderView('tanques.index', $response, ['key' => 'tanques'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar tanques', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar tanques');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Contexto para depuración incluso si falla la obtención de catálogos
        $instalaciones = [];
        $productos = [];
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener catálogos para los selects
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);
            $productos = $this->getCatalog('/api/productos', ['activo' => true]);

            return view('tanques.create', [
                'instalaciones' => $instalaciones,
                'productos' => $productos
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'instalaciones_count' => count($instalaciones),
                'productos_count' => count($productos)
            ]);

            return redirect()->route('tanques.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear tanque
     */
    public function store(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'identificador' => 'required|string|max:255',
            'material' => 'required|string|max:100',
            'capacidad_total' => 'required|numeric|min:0',
            'capacidad_util' => 'required|numeric|min:0|lte:capacidad_total',
            'capacidad_operativa' => 'required|numeric|min:0|lte:capacidad_util',
            'capacidad_minima' => 'required|numeric|min:0',
            'temperatura_referencia' => 'required|numeric',
            'presion_referencia' => 'required|numeric',
            'tipo_medicion' => 'required|in:estatica,dinamica',
            'estado' => 'required|in:OPERATIVO,MANTENIMIENTO,FUERA_SERVICIO,CALIBRACION',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/tanques', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $tanqueData = $this->apiResponseData($response, []);
                $tanqueId = $tanqueData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'TANQUE_CREADO',
                    'Tanques',
                    "Tanque creado: {$request->identificador}",
                    'tanques',
                    $tanqueId
                );

                return redirect()->route('tanques.show', $tanqueId)
                    ->with('success', 'Tanque creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear tanque'));

        } catch (\Exception $e) {
            Log::error('Error al crear tanque', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear tanque');
        }
    }

    /**
     * Mostrar tanque
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/tanques/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('tanques.index')
                    ->with('error', $this->apiResponseMessage($response, 'Tanque no encontrado'));
            }

            $tanque = $this->apiResponseData($response, []);

            return view('tanques.show', [
                'tanque' => $tanque
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar tanque', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->route('tanques.index')
                ->with('error', 'Error al cargar tanque');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/tanques/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('tanques.index')
                    ->with('error', $this->apiResponseMessage($response, 'Tanque no encontrado'));
            }

            $tanque = $this->apiResponseData($response, []);

            // Obtener productos para el select
            $productos = $this->getCatalog('/api/productos', ['activo' => true]);

            return view('tanques.edit', [
                'tanque' => $tanque,
                'productos' => $productos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->route('tanques.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar tanque
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'producto_id' => 'nullable|integer',
            'identificador' => "sometimes|string|max:255",
            'material' => 'sometimes|string|max:100',
            'capacidad_total' => 'sometimes|numeric|min:0',
            'capacidad_util' => 'sometimes|numeric|min:0|lte:capacidad_total',
            'capacidad_operativa' => 'sometimes|numeric|min:0|lte:capacidad_util',
            'capacidad_minima' => 'sometimes|numeric|min:0',
            'estado' => 'sometimes|in:OPERATIVO,MANTENIMIENTO,FUERA_SERVICIO,CALIBRACION',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/tanques/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'TANQUE_ACTUALIZADO',
                    'Tanques',
                    "Tanque actualizado ID: {$id}",
                    'tanques',
                    $id
                );

                return redirect()->route('tanques.show', $id)
                    ->with('success', 'Tanque actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar tanque'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar tanque', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar tanque');
        }
    }

    /**
     * Eliminar tanque
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/tanques/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'TANQUE_ELIMINADO',
                    'Tanques',
                    "Tanque eliminado ID: {$id}",
                    'tanques',
                    $id
                );

                return redirect()->route('tanques.index')
                    ->with('success', 'Tanque eliminado exitosamente');
            }

            if ($response['status'] === 409) {
                $error = $this->apiResponseData($response, 'No se puede eliminar el tanque');
                return redirect()->back()
                    ->with('error', $error);
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar tanque'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar tanque', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar tanque');
        }
    }

    /**
     * Registrar calibración
     */
    public function registrarCalibracion(Request $request, $id)
    {
        $request->validate([
            'fecha_calibracion' => 'required|date',
            'fecha_proxima_calibracion' => 'required|date|after:fecha_calibracion',
            'certificado_calibracion' => 'required|string|max:255',
            'entidad_calibracion' => 'required|string|max:255',
            'tabla_aforo' => 'required|array',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/tanques/{$id}/calibrar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'CALIBRACION_TANQUE_REGISTRADA',
                    'Tanques',
                    "Calibración registrada para tanque ID: {$id}",
                    'tanques',
                    $id
                );

                return redirect()->route('tanques.show', $id)
                    ->with('success', 'Calibración registrada exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al registrar calibración'));

        } catch (\Exception $e) {
            Log::error('Error al registrar calibración de tanque', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar calibración');
        }
    }

    /**
     * Verificar estado del tanque
     */
    public function verificarEstado($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/tanques/{$id}/verificar-estado");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('tanques.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al verificar estado'));
            }

            $estado = $this->apiResponseData($response, []);

            return view('tanques.estado', [
                'estado' => $estado,
                'tanque_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al verificar estado del tanque', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->route('tanques.show', $id)
                ->with('error', 'Error al verificar estado');
        }
    }

    /**
     * Cambiar producto del tanque
     */
    public function cambiarProducto(Request $request, $id)
    {
        $request->validate([
            'producto_id' => 'required|integer',
            'motivo' => 'required|string|max:500',
            'fecha_cambio' => 'required|date',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/tanques/{$id}/cambiar-producto", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'CAMBIO_PRODUCTO_TANQUE',
                    'Tanques',
                    "Producto cambiado en tanque ID: {$id}",
                    'tanques',
                    $id
                );

                return redirect()->route('tanques.show', $id)
                    ->with('success', 'Producto cambiado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al cambiar producto'));

        } catch (\Exception $e) {
            Log::error('Error al cambiar producto del tanque', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al cambiar producto');
        }
    }

    /**
     * Obtener curva de calibración
     */
    public function curvaCalibracion($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/tanques/{$id}/curva-calibracion");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('tanques.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar curva'));
            }

            $curva = $this->apiResponseData($response, []);

            return view('tanques.curva-calibracion', [
                'curva' => $curva,
                'tanque_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener curva de calibración', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->route('tanques.show', $id)
                ->with('error', 'Error al cargar curva');
        }
    }

    /**
     * Obtener historial de calibraciones
     */
    public function historialCalibraciones($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/tanques/{$id}/historial-calibraciones");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('tanques.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar historial'));
            }

            $historial = $this->apiResponseData($response, []);

            return view('tanques.historial-calibraciones', [
                'historial' => $historial,
                'tanque_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener historial de calibraciones', [
                'error' => $e->getMessage(),
                'tanque_id' => $id
            ]);

            return redirect()->route('tanques.show', $id)
                ->with('error', 'Error al cargar historial');
        }
    }
}
