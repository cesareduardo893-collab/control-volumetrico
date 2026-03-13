<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PedimentoController extends BaseController
{
    /**
     * Listar pedimentos
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'contribuyente_id', 'numero_pedimento', 'producto_id',
                'pais_origen', 'pais_destino', 'fecha_inicio', 'fecha_fin',
                'estado', 'registro_volumetrico_id', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/pedimentos', $params);

            return $this->renderView('pedimentos.index', $response, ['key' => 'pedimentos'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar pedimentos', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar pedimentos');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener contribuyentes y productos para los selects
            $contribuyentes = $this->getCatalog('/api/catalogo/contribuyentes');
            $productos = $this->getCatalog('/api/catalogo/productos');

            return view('pedimentos.create', [
                'contribuyentes' => $contribuyentes,
                'productos' => $productos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('pedimentos.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear pedimento
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_pedimento' => 'required|string|max:255',
            'contribuyente_id' => 'required|integer',
            'producto_id' => 'required|integer',
            'pais_destino' => 'required|string|size:3',
            'pais_origen' => 'required|string|size:3',
            'medio_transporte_entrada' => 'required|string|max:255',
            'incoterms' => 'required|string|max:10',
            'volumen' => 'required|numeric|min:0',
            'unidad_medida' => 'required|string|max:10',
            'valor_comercial' => 'required|numeric|min:0',
            'moneda' => 'required|string|size:3',
            'fecha_pedimento' => 'required|date',
            'estado' => 'required|in:ACTIVO,UTILIZADO,CANCELADO',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/pedimentos', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $pedimentoData = $this->apiResponseData($response, []);
                $pedimentoId = $pedimentoData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    'comercio_exterior',
                    'PEDIMENTO_CREADO',
                    'Pedimentos',
                    "Pedimento creado: {$request->numero_pedimento}",
                    'pedimentos',
                    $pedimentoId
                );

                return redirect()->route('pedimentos.index')
                    ->with('success', 'Pedimento creado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear pedimento'));

        } catch (\Exception $e) {
            Log::error('Error al crear pedimento', [
                'error' => $e->getMessage(),
                'data' => $request->except('_token')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear pedimento');
        }
    }

    /**
     * Mostrar pedimento
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/pedimentos/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('pedimentos.index')
                    ->with('error', $this->apiResponseMessage($response, 'Pedimento no encontrado'));
            }

            $pedimento = $this->apiResponseData($response, []);

            return view('pedimentos.show', [
                'pedimento' => $pedimento
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar pedimento', [
                'error' => $e->getMessage(),
                'pedimento_id' => $id
            ]);

            return redirect()->route('pedimentos.index')
                ->with('error', 'Error al cargar pedimento');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener contribuyentes y productos para los selects
            $contribuyentes = $this->getCatalog('/api/catalogo/contribuyentes');
            $productos = $this->getCatalog('/api/catalogo/productos');

            // Obtener datos del pedimento
            $response = $this->apiGet("/api/pedimentos/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('pedimentos.index')
                    ->with('error', $this->apiResponseMessage($response, 'Pedimento no encontrado'));
            }

            $pedimento = $this->apiResponseData($response, []);

            return view('pedimentos.edit', [
                'pedimento' => $pedimento,
                'contribuyentes' => $contribuyentes,
                'productos' => $productos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'pedimento_id' => $id
            ]);

            return redirect()->route('pedimentos.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar pedimento
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_arribo' => 'nullable|date',
            'fecha_pago' => 'nullable|date',
            'registro_volumetrico_id' => 'nullable|integer',
            'estado' => 'sometimes|in:ACTIVO,UTILIZADO,CANCELADO',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/pedimentos/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'comercio_exterior',
                    'PEDIMENTO_ACTUALIZADO',
                    'Pedimentos',
                    "Pedimento actualizado ID: {$id}",
                    'pedimentos',
                    $id
                );

                return redirect()->route('pedimentos.show', $id)
                    ->with('success', 'Pedimento actualizado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar pedimento'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar pedimento', [
                'error' => $e->getMessage(),
                'pedimento_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar pedimento');
        }
    }

    /**
     * Cancelar pedimento
     */
    public function cancelar(Request $request, $id)
    {
        $request->validate([
            'motivo_cancelacion' => 'required|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/pedimentos/{$id}/cancelar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'comercio_exterior',
                    'PEDIMENTO_CANCELADO',
                    'Pedimentos',
                    "Pedimento cancelado ID: {$id}",
                    'pedimentos',
                    $id
                );

                return redirect()->route('pedimentos.show', $id)
                    ->with('success', $this->apiResponseMessage($response, 'Pedimento cancelado exitosamente'));
            }

            if ($response->status === 403) {
                return redirect()->back()
                    ->with('error', 'El pedimento ya está cancelado');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al cancelar pedimento'));

        } catch (\Exception $e) {
            Log::error('Error al cancelar pedimento', [
                'error' => $e->getMessage(),
                'pedimento_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al cancelar pedimento');
        }
    }

    /**
     * Marcar como utilizado
     */
    public function marcarUtilizado(Request $request, $id)
    {
        $request->validate([
            'registro_volumetrico_id' => 'required|integer',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/pedimentos/{$id}/utilizado", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'comercio_exterior',
                    'PEDIMENTO_UTILIZADO',
                    'Pedimentos',
                    "Pedimento marcado como utilizado ID: {$id}",
                    'pedimentos',
                    $id
                );

                return redirect()->route('pedimentos.show', $id)
                    ->with('success', $this->apiResponseMessage($response, 'Pedimento marcado como utilizado exitosamente'));
            }

            if ($response->status === 403) {
                return redirect()->back()
                    ->with('error', 'El pedimento no está en estado ACTIVO');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al marcar pedimento'));

        } catch (\Exception $e) {
            Log::error('Error al marcar pedimento como utilizado', [
                'error' => $e->getMessage(),
                'pedimento_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al marcar pedimento');
        }
    }

    /**
     * Obtener resumen de comercio exterior
     */
    public function resumenComercioExterior(Request $request)
    {
        $request->validate([
            'contribuyente_id' => 'required|integer',
            'anio' => 'required|integer|min:2020',
            'mes' => 'nullable|integer|min:1|max:12',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['contribuyente_id', 'anio', 'mes']);

            $response = $this->apiGet('/api/pedimentos/resumen-comercio-exterior', $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al generar resumen'));
            }

            $resumen = $this->apiResponseData($response, []);

            return view('pedimentos.resumen', [
                'resumen' => $resumen,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al generar resumen de comercio exterior', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al generar resumen');
        }
    }

    /**
     * Eliminar pedimento
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/pedimentos/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'comercio_exterior',
                    'PEDIMENTO_ELIMINADO',
                    'Pedimentos',
                    "Pedimento eliminado ID: {$id}",
                    'pedimentos',
                    $id
                );

                return redirect()->route('pedimentos.index')
                    ->with('success', 'Pedimento eliminado exitosamente');
            }

            if ($response->status === 403) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el pedimento');
            }

            return redirect()->route('pedimentos.index')
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar pedimento'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar pedimento', [
                'error' => $e->getMessage(),
                'pedimento_id' => $id
            ]);

            return redirect()->route('pedimentos.index')
                ->with('error', 'Error al eliminar pedimento');
        }
    }
}