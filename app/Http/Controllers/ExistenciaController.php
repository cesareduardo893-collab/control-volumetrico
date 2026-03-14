<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ExistenciaController extends BaseController
{
    /**
     * Listar existencias
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'tanque_id', 'producto_id', 'fecha', 'fecha_inicio', 'fecha_fin',
                'tipo_registro', 'tipo_movimiento', 'estado', 'numero_registro',
                'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/existencias', $params);

            return $this->renderView('existencias.index', $response, ['key' => 'existencias'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar existencias', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar existencias');
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
            $tanquesRaw = $this->getCatalog('/api/tanques', ['activo' => true]);
            // Estandarizar estructura de tanques para la vista
            $tanques = collect($tanquesRaw)->map(function ($tanque) {
                return [
                    'id' => data_get($tanque, 'id'),
                    'identificador' => data_get($tanque, 'identificador'),
                    'instalacion' => ['nombre' => data_get($tanque, 'instalacion.nombre', '')],
                    'producto' => ['nombre' => data_get($tanque, 'producto.nombre')]
                ];
            })->values()->toArray();
            $productosRaw = $this->getCatalog('/api/productos', ['activo' => true]);
            // Normalizar productos para manejo seguro en la vista
            $productos = collect($productosRaw)->map(function ($p) {
                return [
                    'id' => data_get($p, 'id'),
                    'nombre' => data_get($p, 'nombre'),
                    'clave_sat' => data_get($p, 'clave_sat')
                ];
            })->values()->toArray();

            return view('existencias.create', [
                'tanques' => $tanques,
                'productos' => $productos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('existencias.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear existencia
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_registro' => 'required|string|max:255',
            'tanque_id' => 'required|integer',
            'producto_id' => 'required|integer',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i:s',
            'volumen_medido' => 'required|numeric|min:0',
            'temperatura' => 'required|numeric',
            'densidad' => 'nullable|numeric|min:0',
            'volumen_corregido' => 'required|numeric|min:0',
            'volumen_disponible' => 'required|numeric|min:0',
            'volumen_agua' => 'required|numeric|min:0',
            'volumen_sedimentos' => 'required|numeric|min:0',
            'tipo_registro' => 'required|in:inicial,operacion,final',
            'tipo_movimiento' => 'required|in:INICIAL,RECEPCION,ENTREGA,VENTA,TRASPASO,AJUSTE,INVENTARIO',
            'estado' => 'required|in:PENDIENTE,VALIDADO,EN_REVISION,CON_ALARMA',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/existencias', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $existenciaData = $this->apiResponseData($response, []);
                $existenciaId = $existenciaData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'EXISTENCIA_CREADA',
                    'Existencias',
                    "Existencia creada: {$request->numero_registro}",
                    'existencias',
                    $existenciaId
                );

                return redirect()->route('existencias.show', $existenciaId)
                    ->with('success', 'Existencia creada exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear existencia'));

        } catch (\Exception $e) {
            Log::error('Error al crear existencia', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear existencia');
        }
    }

    /**
     * Mostrar existencia
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/existencias/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('existencias.index')
                    ->with('error', $this->apiResponseMessage($response, 'Existencia no encontrada'));
            }

            $existencia = $this->apiResponseData($response, []);

            return view('existencias.show', [
                'existencia' => $existencia
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar existencia', [
                'error' => $e->getMessage(),
                'existencia_id' => $id
            ]);

            return redirect()->route('existencias.index')
                ->with('error', 'Error al cargar existencia');
        }
    }

    /**
     * Validar existencia
     */
    public function validar(Request $request, $id)
    {
        $request->validate([
            'observaciones_validacion' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/existencias/{$id}/validar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'EXISTENCIA_VALIDADA',
                    'Existencias',
                    "Existencia validada ID: {$id}",
                    'existencias',
                    $id
                );

                return redirect()->route('existencias.show', $id)
                    ->with('success', 'Existencia validada exitosamente');
            }

            if ($response['status'] === 403) {
                return redirect()->back()
                    ->with('error', 'La existencia ya está validada');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al validar existencia'));

        } catch (\Exception $e) {
            Log::error('Error al validar existencia', [
                'error' => $e->getMessage(),
                'existencia_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al validar existencia');
        }
    }

    /**
     * Obtener inventario actual por tanque
     */
    public function inventarioActual($tanqueId)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/existencias/inventario-actual/{$tanqueId}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar inventario'));
            }

            $inventario = $this->apiResponseData($response, []);

            return view('existencias.inventario-actual', [
                'inventario' => $inventario
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener inventario actual', [
                'error' => $e->getMessage(),
                'tanque_id' => $tanqueId
            ]);

            return redirect()->back()->with('error', 'Error al cargar inventario');
        }
    }

    /**
     * Obtener histórico de existencias
     */
    public function historico(Request $request, $tanqueId)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/existencias/historico/{$tanqueId}", $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar histórico'));
            }

            $historico = $this->apiResponseData($response, []);

            return view('existencias.historico', [
                'historico' => $historico,
                'tanqueId' => $tanqueId,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener histórico de existencias', [
                'error' => $e->getMessage(),
                'tanque_id' => $tanqueId
            ]);

            return redirect()->back()->with('error', 'Error al cargar histórico');
        }
    }

    /**
     * Obtener reporte de mermas
     */
    public function reporteMermas(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/existencias/reporte-mermas', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al generar reporte'));
            }

            $mermas = $this->apiResponseData($response, []);

            return view('existencias.reporte-mermas', [
                'mermas' => $mermas,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al generar reporte de mermas', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al generar reporte');
        }
    }

    /**
     * Obtener existencias por fecha
     */
    public function porFecha(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/existencias/por-fecha', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar existencias'));
            }

            $existencias = $this->apiResponseData($response, []);

            return view('existencias.por-fecha', [
                'existencias' => $existencias,
                'fecha' => $request->fecha
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener existencias por fecha', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar existencias');
        }
    }
}
