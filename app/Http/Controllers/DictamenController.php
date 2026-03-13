<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DictamenController extends BaseController
{
    /**
     * Listar dictámenes
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'contribuyente_id', 'instalacion_id', 'producto_id', 'folio',
                'numero_lote', 'laboratorio_rfc', 'fecha_emision_inicio',
                'fecha_emision_fin', 'estado', 'vigente', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/dictamenes', $params);

            return $this->renderView('dictamenes.index', $response, ['key' => 'dictamenes'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar dictámenes', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar dictámenes');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener catálogos para los selects
            $contribuyentes = $this->getCatalog('/api/catalogo/contribuyentes');
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);
            $productos = $this->getCatalog('/api/catalogo/productos');

            return view('dictamenes.create', [
                'contribuyentes' => $contribuyentes,
                'instalaciones' => $instalaciones,
                'productos' => $productos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('dictamenes.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear dictamen
     */
    public function store(Request $request)
    {
        $request->validate([
            'folio' => 'required|string|max:255',
            'numero_lote' => 'required|string|max:255',
            'contribuyente_id' => 'required|integer',
            'laboratorio_rfc' => 'required|string|size:13',
            'laboratorio_nombre' => 'required|string|max:255',
            'laboratorio_numero_acreditacion' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'fecha_toma_muestra' => 'required|date',
            'fecha_pruebas' => 'required|date',
            'fecha_resultados' => 'required|date',
            'producto_id' => 'required|integer',
            'volumen_muestra' => 'required|numeric|min:0',
            'unidad_medida_muestra' => 'required|string|max:10',
            'metodo_muestreo' => 'required|string|max:255',
            'metodo_ensayo' => 'required|string|max:255',
            'estado' => 'required|in:VIGENTE,CADUCADO,CANCELADO',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/dictamenes', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $dictamenData = $this->apiResponseData($response, []);
                $dictamenId = $dictamenData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    'calidad',
                    'DICTAMEN_CREADO',
                    'Dictámenes',
                    "Dictamen creado: {$request->folio}",
                    'dictamenes',
                    $dictamenId
                );

                return redirect()->route('dictamenes.show', $dictamenId)
                    ->with('success', 'Dictamen creado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear dictamen'));

        } catch (\Exception $e) {
            Log::error('Error al crear dictamen', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear dictamen');
        }
    }

    /**
     * Mostrar dictamen
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/dictamenes/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('dictamenes.index')
                    ->with('error', $this->apiResponseMessage($response, 'Dictamen no encontrado'));
            }

            $dictamen = $this->apiResponseData($response, []);

            return view('dictamenes.show', [
                'dictamen' => $dictamen
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar dictamen', [
                'error' => $e->getMessage(),
                'dictamen_id' => $id
            ]);

            return redirect()->route('dictamenes.index')
                ->with('error', 'Error al cargar dictamen');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/dictamenes/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('dictamenes.index')
                    ->with('error', $this->apiResponseMessage($response, 'Dictamen no encontrado'));
            }

            $dictamen = $this->apiResponseData($response, []);

            return view('dictamenes.edit', [
                'dictamen' => $dictamen
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'dictamen_id' => $id
            ]);

            return redirect()->route('dictamenes.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar dictamen
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'nullable|string',
            'estado' => 'sometimes|in:VIGENTE,CADUCADO,CANCELADO',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/dictamenes/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'calidad',
                    'DICTAMEN_ACTUALIZADO',
                    'Dictámenes',
                    "Dictamen actualizado ID: {$id}",
                    'dictamenes',
                    $id
                );

                return redirect()->route('dictamenes.show', $id)
                    ->with('success', 'Dictamen actualizado exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar dictamen'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar dictamen', [
                'error' => $e->getMessage(),
                'dictamen_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar dictamen');
        }
    }

    /**
     * Cancelar dictamen
     */
    public function cancelar(Request $request, $id)
    {
        $request->validate([
            'motivo_cancelacion' => 'required|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/dictamenes/{$id}/cancelar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'calidad',
                    'DICTAMEN_CANCELADO',
                    'Dictámenes',
                    "Dictamen cancelado ID: {$id}",
                    'dictamenes',
                    $id
                );

                return redirect()->route('dictamenes.show', $id)
                    ->with('success', 'Dictamen cancelado exitosamente');
            }

            if ($response->status === 403) {
                return redirect()->back()
                    ->with('error', 'El dictamen ya está cancelado');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al cancelar dictamen'));

        } catch (\Exception $e) {
            Log::error('Error al cancelar dictamen', [
                'error' => $e->getMessage(),
                'dictamen_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al cancelar dictamen');
        }
    }

    /**
     * Verificar vigencia del dictamen
     */
    public function verificarVigencia($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/dictamenes/{$id}/verificar-vigencia");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('dictamenes.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al verificar vigencia'));
            }

            $resultado = $this->apiResponseData($response, []);

            return view('dictamenes.vigencia', [
                'resultado' => $resultado
            ]);

        } catch (\Exception $e) {
            Log::error('Error al verificar vigencia', [
                'error' => $e->getMessage(),
                'dictamen_id' => $id
            ]);

            return redirect()->route('dictamenes.show', $id)
                ->with('error', 'Error al verificar vigencia');
        }
    }

    /**
     * Obtener estadísticas de dictámenes
     */
    public function estadisticas(Request $request)
    {
        $request->validate([
            'contribuyente_id' => 'required|integer',
            'anio' => 'required|integer|min:2020',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/dictamenes/estadisticas', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar estadísticas'));
            }

            $estadisticas = $this->apiResponseData($response, []);

            return view('dictamenes.estadisticas', [
                'estadisticas' => $estadisticas,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas de dictámenes', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar estadísticas');
        }
    }

    /**
     * Obtener dictámenes por producto
     */
    public function porProducto($productoId)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/dictamenes/producto/{$productoId}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar dictámenes'));
            }

            $resultado = $this->apiResponseData($response, []);

            return view('dictamenes.por-producto', [
                'resultado' => $resultado
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener dictámenes por producto', [
                'error' => $e->getMessage(),
                'producto_id' => $productoId
            ]);

            return redirect()->back()->with('error', 'Error al cargar dictámenes');
        }
    }
}