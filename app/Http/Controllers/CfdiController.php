<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CfdiController extends BaseController
{
    /**
     * Listar CFDI
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'uuid', 'rfc_emisor', 'rfc_receptor', 'tipo_operacion',
                'producto_id', 'fecha_inicio', 'fecha_fin', 'estado',
                'registro_volumetrico_id', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/cfdi', $params);

            return $this->renderView('cfdi.index', $response, ['key' => 'cfdis'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar CFDI', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar CFDI');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener productos para el select
            $productos = $this->getCatalog('/api/productos', ['activo' => true]);

            return view('cfdi.create', [
                'productos' => $productos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('cfdi.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear CFDI
     */
    public function store(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string|size:36',
            'rfc_emisor' => 'required|string|size:13',
            'rfc_receptor' => 'required|string|size:13',
            'tipo_operacion' => 'required|in:adquisicion,enajenacion,servicio',
            'subtotal' => 'required|numeric|min:0',
            'iva' => 'required|numeric|min:0',
            'ieps' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'fecha_emision' => 'required|date',
            'estado' => 'required|in:VIGENTE,CANCELADO',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/cfdi', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $cfdiData = $this->apiResponseData($response, []);
                $cfdiId = $cfdiData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    'fiscal',
                    'CFDI_CREADO',
                    'CFDI',
                    "CFDI creado: {$request->uuid}",
                    'cfdi',
                    $cfdiId
                );

                return redirect()->route('cfdi.show', $cfdiId)
                    ->with('success', 'CFDI creado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear CFDI'));

        } catch (\Exception $e) {
            Log::error('Error al crear CFDI', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear CFDI');
        }
    }

    /**
     * Mostrar CFDI
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/cfdi/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('cfdi.index')
                    ->with('error', $this->apiResponseMessage($response, 'CFDI no encontrado'));
            }

            $cfdi = $this->apiResponseData($response, []);

            return view('cfdi.show', [
                'cfdi' => $cfdi
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar CFDI', [
                'error' => $e->getMessage(),
                'cfdi_id' => $id
            ]);

            return redirect()->route('cfdi.index')
                ->with('error', 'Error al cargar CFDI');
        }
    }

    /**
     * Cancelar CFDI
     */
    public function cancelar(Request $request, $id)
    {
        $request->validate([
            'motivo_cancelacion' => 'required|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/cfdi/{$id}/cancelar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'fiscal',
                    'CFDI_CANCELADO',
                    'CFDI',
                    "CFDI cancelado ID: {$id}",
                    'cfdi',
                    $id
                );

                return redirect()->route('cfdi.show', $id)
                    ->with('success', 'CFDI cancelado exitosamente');
            }

            if ($response->status === 403) {
                return redirect()->back()
                    ->with('error', 'El CFDI ya está cancelado');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al cancelar CFDI'));

        } catch (\Exception $e) {
            Log::error('Error al cancelar CFDI', [
                'error' => $e->getMessage(),
                'cfdi_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al cancelar CFDI');
        }
    }

    /**
     * Obtener CFDI por RFC
     */
    public function porRfc(Request $request, $rfc)
    {
        $request->validate([
            'tipo' => 'required|in:emisor,receptor',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/cfdi/rfc/{$rfc}", $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar CFDI'));
            }

            $resultado = $this->apiResponseData($response, []);

            return view('cfdi.por-rfc', [
                'resultado' => $resultado,
                'rfc' => $rfc,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener CFDI por RFC', [
                'error' => $e->getMessage(),
                'rfc' => $rfc
            ]);

            return redirect()->back()->with('error', 'Error al cargar CFDI');
        }
    }

    /**
     * Obtener resumen fiscal
     */
    public function resumenFiscal(Request $request)
    {
        $request->validate([
            'contribuyente_rfc' => 'required|string|size:13',
            'anio' => 'required|integer|min:2020',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/cfdi/resumen-fiscal', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al generar resumen'));
            }

            $resumen = $this->apiResponseData($response, []);

            return view('cfdi.resumen-fiscal', [
                'resumen' => $resumen,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener resumen fiscal', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al generar resumen');
        }
    }
}