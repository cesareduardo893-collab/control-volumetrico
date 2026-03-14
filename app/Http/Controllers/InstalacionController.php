<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class InstalacionController extends BaseController
{
    /**
     * Listar instalaciones
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'contribuyente_id', 'clave_instalacion', 'nombre', 'tipo_instalacion',
                'estatus', 'municipio', 'estado', 'activo', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/instalaciones', $params);

            return $this->renderView('instalaciones.index', $response, ['key' => 'instalaciones'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar instalaciones', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar instalaciones');
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

            return view('instalaciones.create', [
                'contribuyentes' => $contribuyentes
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('instalaciones.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear instalación
     */
    public function store(Request $request)
    {
        $request->validate([
            'contribuyente_id' => 'required|integer',
            'clave_instalacion' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'tipo_instalacion' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'required|string|size:5',
            'municipio' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'estatus' => 'required|in:OPERACION,SUSPENDIDA,CANCELADA',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/instalaciones', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $instalacionData = $this->apiResponseData($response, []);
                $instalacionId = $instalacionData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'INSTALACION_CREADA',
                    'Instalaciones',
                    "Instalación creada: {$request->clave_instalacion}",
                    'instalaciones',
                    $instalacionId
                );

                return redirect()->route('instalaciones.show', $instalacionId)
                    ->with('success', 'Instalación creada exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear instalación'));

        } catch (\Exception $e) {
            Log::error('Error al crear instalación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear instalación');
        }
    }

    /**
     * Mostrar instalación
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/instalaciones/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('instalaciones.index')
                    ->with('error', $this->apiResponseMessage($response, 'Instalación no encontrada'));
            }

            $instalacion = $this->apiResponseData($response, []);

            return view('instalaciones.show', [
                'instalacion' => $instalacion
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar instalación', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->route('instalaciones.index')
                ->with('error', 'Error al cargar instalación');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/instalaciones/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('instalaciones.index')
                    ->with('error', $this->apiResponseMessage($response, 'Instalación no encontrada'));
            }

            $instalacion = $this->apiResponseData($response, []);

            return view('instalaciones.edit', [
                'instalacion' => $instalacion
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->route('instalaciones.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar instalación
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'clave_instalacion' => "sometimes|string|max:255",
            'nombre' => 'sometimes|string|max:255',
            'tipo_instalacion' => 'sometimes|string|max:255',
            'domicilio' => 'sometimes|string|max:255',
            'codigo_postal' => 'sometimes|string|size:5',
            'municipio' => 'sometimes|string|max:255',
            'estado' => 'sometimes|string|max:255',
            'estatus' => 'sometimes|in:OPERACION,SUSPENDIDA,CANCELADA',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/instalaciones/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'INSTALACION_ACTUALIZADA',
                    'Instalaciones',
                    "Instalación actualizada ID: {$id}",
                    'instalaciones',
                    $id
                );

                return redirect()->route('instalaciones.show', $id)
                    ->with('success', 'Instalación actualizada exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar instalación'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar instalación', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar instalación');
        }
    }

    /**
     * Eliminar instalación
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/instalaciones/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'INSTALACION_ELIMINADA',
                    'Instalaciones',
                    "Instalación eliminada ID: {$id}",
                    'instalaciones',
                    $id
                );

                return redirect()->route('instalaciones.index')
                    ->with('success', 'Instalación eliminada exitosamente');
            }

            if ($response->status === 409) {
                $error = $this->apiResponseData($response, 'No se puede eliminar la instalación');
                return redirect()->back()
                    ->with('error', $error);
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar instalación'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar instalación', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar instalación');
        }
    }

    /**
     * Obtener tanques de la instalación
     */
    public function tanques(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['estado', 'producto_id', 'activos', 'per_page']);

            $response = $this->apiGet("/api/instalaciones/{$id}/tanques", $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('instalaciones.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar tanques'));
            }

            $tanques = $this->apiResponseData($response, []);

            return view('instalaciones.tanques', [
                'tanques' => $tanques['data'] ?? $tanques,
                'instalacion_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar tanques de la instalación', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->route('instalaciones.show', $id)
                ->with('error', 'Error al cargar tanques');
        }
    }

    /**
     * Obtener medidores de la instalación
     */
    public function medidores(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['tipo_medicion', 'estado', 'activos', 'calibracion_proxima', 'per_page']);

            $response = $this->apiGet("/api/instalaciones/{$id}/medidores", $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('instalaciones.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar medidores'));
            }

            $medidores = $this->apiResponseData($response, []);

            return view('instalaciones.medidores', [
                'medidores' => $medidores['data'] ?? $medidores,
                'instalacion_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar medidores de la instalación', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->route('instalaciones.show', $id)
                ->with('error', 'Error al cargar medidores');
        }
    }

    /**
     * Obtener dispensarios de la instalación
     */
    public function dispensarios(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['estado', 'activos', 'per_page']);

            $response = $this->apiGet("/api/instalaciones/{$id}/dispensarios", $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('instalaciones.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar dispensarios'));
            }

            $dispensarios = $this->apiResponseData($response, []);

            return view('instalaciones.dispensarios', [
                'dispensarios' => $dispensarios['data'] ?? $dispensarios,
                'instalacion_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar dispensarios de la instalación', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->route('instalaciones.show', $id)
                ->with('error', 'Error al cargar dispensarios');
        }
    }

    /**
     * Obtener resumen operativo
     */
    public function resumenOperativo($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/instalaciones/{$id}/resumen-operativo");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('instalaciones.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar resumen'));
            }

            $resumen = $this->apiResponseData($response, []);

            return view('instalaciones.resumen-operativo', [
                'resumen' => $resumen
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar resumen operativo', [
                'error' => $e->getMessage(),
                'instalacion_id' => $id
            ]);

            return redirect()->route('instalaciones.show', $id)
                ->with('error', 'Error al cargar resumen');
        }
    }
}