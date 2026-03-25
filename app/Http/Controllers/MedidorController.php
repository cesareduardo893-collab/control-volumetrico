<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Http\Controllers\Traits\ValidacionEspanol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class MedidorController extends BaseController
{
    use ValidacionEspanol;
    /**
     * Listar medidores
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'instalacion_id', 'tanque_id', 'numero_serie', 'clave',
                'elemento_tipo', 'tipo_medicion', 'tecnologia_id', 'estado',
                'protocolo_comunicacion', 'activo', 'calibracion_proxima',
                'alerta_alteracion', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/medidores', $params);

            return $this->renderView('medidores.index', $response, ['key' => 'medidores'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar medidores', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar medidores');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener instalaciones y tanques para los selects
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);
            $tanques = $this->getCatalog('/api/tanques', ['activo' => true]);

            return view('medidores.create', [
                'instalaciones' => $instalaciones,
                'tanques' => $tanques
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('medidores.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear medidor
     */
    public function store(Request $request)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasMedidor());
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/medidores', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $medidorData = $this->apiResponseData($response, []);
                $medidorId = $medidorData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'MEDIDOR_CREADO',
                    'Medidores',
                    "Medidor creado: {$request->numero_serie}",
                    'medidores',
                    $medidorId
                );

                return redirect()->route('medidores.index')
                    ->with('success', 'Medidor creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear medidor'));

        } catch (\Exception $e) {
            Log::error('Error al crear medidor', [
                'error' => $e->getMessage(),
                'data' => $request->except('_token')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear medidor');
        }
    }

    /**
     * Mostrar medidor
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/medidores/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('medidores.index')
                    ->with('error', $this->apiResponseMessage($response, 'Medidor no encontrado'));
            }

            $medidor = $this->apiResponseData($response, []);

            return view('medidores.show', [
                'medidor' => $medidor
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar medidor', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->route('medidores.index')
                ->with('error', 'Error al cargar medidor');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener instalaciones y tanques para los selects
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);
            $tanques = $this->getCatalog('/api/tanques', ['activo' => true]);

            // Obtener datos del medidor
            $response = $this->apiGet("/api/medidores/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('medidores.index')
                    ->with('error', $this->apiResponseMessage($response, 'Medidor no encontrado'));
            }

            $medidor = $this->apiResponseData($response, []);

            return view('medidores.edit', [
                'medidor' => $medidor,
                'instalaciones' => $instalaciones,
                'tanques' => $tanques
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->route('medidores.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar medidor
     */
    public function update(Request $request, $id)
    {
        $resultadoValidacion = $this->validar($request, $this->reglasMedidor(true));
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/medidores/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'MEDIDOR_ACTUALIZADO',
                    'Medidores',
                    "Medidor actualizado ID: {$id}",
                    'medidores',
                    $id
                );

                return redirect()->route('medidores.show', $id)
                    ->with('success', 'Medidor actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            if ($response['status'] === 409) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $this->apiResponseData($response, 'El medidor no se puede actualizar'));
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar medidor'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar medidor', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar medidor');
        }
    }

    /**
     * Eliminar medidor (soft delete)
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/medidores/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'MEDIDOR_ELIMINADO',
                    'Medidores',
                    "Medidor eliminado ID: {$id}",
                    'medidores',
                    $id
                );

                return redirect()->route('medidores.index')
                    ->with('success', 'Medidor eliminado exitosamente');
            }

            if ($response['status'] === 409) {
                return redirect()->back()
                    ->with('error', $this->apiResponseData($response, 'No se puede eliminar el medidor'));
            }

            return redirect()->route('medidores.index')
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar medidor'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar medidor', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->route('medidores.index')
                ->with('error', 'Error al eliminar medidor');
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
            'laboratorio_calibracion' => 'required|string|max:255',
            'precision' => 'required|numeric|min:0',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/medidores/{$id}/calibrar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'CALIBRACION_MEDIDOR_REGISTRADA',
                    'Medidores',
                    "Calibración registrada para medidor ID: {$id}",
                    'medidores',
                    $id
                );

                return redirect()->route('medidores.show', $id)
                    ->with('success', $this->apiResponseMessage($response, 'Calibración registrada exitosamente'));
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al registrar calibración'));

        } catch (\Exception $e) {
            Log::error('Error al registrar calibración', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar calibración');
        }
    }

    /**
     * Probar comunicación
     */
    public function probarComunicacion($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/medidores/{$id}/probar-comunicacion");

            if ($this->apiResponseSuccessful($response)) {
                $resultado = $this->apiResponseData($response, []);

                if (request()->expectsJson()) {
                    return $this->jsonSuccess($resultado, 'Prueba realizada');
                }

                return redirect()->route('medidores.show', $id)
                    ->with('success', 'Prueba de comunicación realizada');
            }

            return redirect()->route('medidores.show', $id)
                ->with('error', $this->apiResponseMessage($response, 'Error al probar comunicación'));

        } catch (\Exception $e) {
            Log::error('Error al probar comunicación', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->route('medidores.show', $id)
                ->with('error', 'Error al probar comunicación');
        }
    }

    /**
     * Verificar estado del medidor
     */
    public function verificarEstado($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/medidores/{$id}/verificar-estado");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('medidores.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al verificar estado'));
            }

            $estado = $this->apiResponseData($response, []);

            if (request()->expectsJson()) {
                return $this->jsonSuccess($estado, 'Estado verificado');
            }

            return view('medidores.estado', [
                'estado' => $estado,
                'medidor_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al verificar estado del medidor', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->route('medidores.show', $id)
                ->with('error', 'Error al verificar estado');
        }
    }

    /**
     * Obtener historial de calibraciones
     */
    public function historialCalibraciones($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/medidores/{$id}/historial-calibraciones");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('medidores.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar historial'));
            }

            $historial = $this->apiResponseData($response, []);

            return view('medidores.historial-calibraciones', [
                'historial' => $historial,
                'medidor_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar historial de calibraciones', [
                'error' => $e->getMessage(),
                'medidor_id' => $id
            ]);

            return redirect()->route('medidores.show', $id)
                ->with('error', 'Error al cargar historial');
        }
    }
}