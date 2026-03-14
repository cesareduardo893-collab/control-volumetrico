<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RegistroVolumetricoController extends BaseController
{
    /**
     * Listar registros volumétricos
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'instalacion_id', 'tanque_id', 'medidor_id', 'producto_id',
                'numero_registro', 'fecha', 'fecha_inicio', 'fecha_fin',
                'tipo_registro', 'operacion', 'estado', 'documento_fiscal_uuid',
                'rfc_contraparte', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/registros-volumetricos', $params);

            return $this->renderView('registros-volumetricos.index', $response, ['key' => 'registros'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar registros volumétricos', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar registros');
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
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);
            $tanques = $this->getCatalog('/api/tanques', ['activo' => true]);
            $medidores = $this->getCatalog('/api/medidores', ['activo' => true]);
            $productos = $this->getCatalog('/api/productos', ['activo' => true]);
            $usuarios = $this->getCatalog('/api/users');

            return view('registros-volumetricos.create', [
                'instalaciones' => $instalaciones,
                'tanques' => $tanques,
                'medidores' => $medidores,
                'productos' => $productos,
                'usuarios' => $usuarios
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('registros-volumetricos.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Crear registro volumétrico
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_registro' => 'required|string|max:255',
            'instalacion_id' => 'required|integer',
            'tanque_id' => 'required|integer',
            'producto_id' => 'required|integer',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
            'volumen_inicial' => 'required|numeric|min:0',
            'volumen_final' => 'required|numeric|min:0',
            'volumen_operacion' => 'required|numeric|min:0',
            'temperatura_inicial' => 'required|numeric',
            'temperatura_final' => 'required|numeric',
            'densidad' => 'required|numeric|min:0',
            'volumen_corregido' => 'required|numeric|min:0',
            'factor_correccion' => 'required|numeric|min:0',
            'tipo_registro' => 'required|in:operacion,acumulado,existencias',
            'operacion' => 'required|in:recepcion,entrega,inventario_inicial,inventario_final,venta',
            'estado' => 'required|in:PENDIENTE,PROCESADO,VALIDADO,ERROR,CANCELADO,CON_ALARMA',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/registros-volumetricos', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $registroData = $this->apiResponseData($response, []);
                $registroId = $registroData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'REGISTRO_VOLUMETRICO_CREADO',
                    'Registros Volumétricos',
                    "Registro volumétrico creado: {$request->numero_registro}",
                    'registros_volumetricos',
                    $registroId
                );

                return redirect()->route('registros-volumetricos.show', $registroId)
                    ->with('success', 'Registro volumétrico creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear registro'));

        } catch (\Exception $e) {
            Log::error('Error al crear registro volumétrico', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear registro');
        }
    }

    /**
     * Mostrar registro volumétrico
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/registros-volumetricos/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('registros-volumetricos.index')
                    ->with('error', $this->apiResponseMessage($response, 'Registro no encontrado'));
            }

            $registro = $this->apiResponseData($response, []);

            return view('registros-volumetricos.show', [
                'registro' => $registro
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar registro volumétrico', [
                'error' => $e->getMessage(),
                'registro_id' => $id
            ]);

            return redirect()->route('registros-volumetricos.index')
                ->with('error', 'Error al cargar registro');
        }
    }

    /**
     * Validar registro volumétrico
     */
    public function validar(Request $request, $id)
    {
        $request->validate([
            'observaciones_validacion' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/registros-volumetricos/{$id}/validar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'REGISTRO_VOLUMETRICO_VALIDADO',
                    'Registros Volumétricos',
                    "Registro volumétrico validado ID: {$id}",
                    'registros_volumetricos',
                    $id
                );

                return redirect()->route('registros-volumetricos.show', $id)
                    ->with('success', 'Registro validado exitosamente');
            }

            if ($response['status'] === 403) {
                return redirect()->back()
                    ->with('error', 'El registro ya está validado');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al validar registro'));

        } catch (\Exception $e) {
            Log::error('Error al validar registro volumétrico', [
                'error' => $e->getMessage(),
                'registro_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al validar registro');
        }
    }

    /**
     * Cancelar registro volumétrico
     */
    public function cancelar(Request $request, $id)
    {
        $request->validate([
            'motivo_cancelacion' => 'required|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/registros-volumetricos/{$id}/cancelar", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'REGISTRO_VOLUMETRICO_CANCELADO',
                    'Registros Volumétricos',
                    "Registro volumétrico cancelado ID: {$id}",
                    'registros_volumetricos',
                    $id
                );

                return redirect()->route('registros-volumetricos.show', $id)
                    ->with('success', 'Registro cancelado exitosamente');
            }

            if ($response['status'] === 403) {
                return redirect()->back()
                    ->with('error', 'El registro ya está cancelado');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al cancelar registro'));

        } catch (\Exception $e) {
            Log::error('Error al cancelar registro volumétrico', [
                'error' => $e->getMessage(),
                'registro_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al cancelar registro');
        }
    }

    /**
     * Obtener resumen diario
     */
    public function resumenDiario(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'fecha' => 'required|date',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/registros-volumetricos/resumen-diario', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al generar resumen'));
            }

            $resumen = $this->apiResponseData($response, []);

            return view('registros-volumetricos.resumen-diario', [
                'resumen' => $resumen,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener resumen diario', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al generar resumen');
        }
    }

    /**
     * Obtener estadísticas mensuales
     */
    public function estadisticasMensuales(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'anio' => 'required|integer|min:2020',
            'mes' => 'required|integer|min:1|max:12',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/registros-volumetricos/estadisticas-mensuales', $request->all());

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar estadísticas'));
            }

            $estadisticas = $this->apiResponseData($response, []);

            return view('registros-volumetricos.estadisticas', [
                'estadisticas' => $estadisticas,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas mensuales', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar estadísticas');
        }
    }

    /**
     * Asociar dictamen a registro volumétrico
     */
    public function asociarDictamen(Request $request, $id)
    {
        $request->validate([
            'dictamen_id' => 'required|integer',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/registros-volumetricos/{$id}/asociar-dictamen", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'DICTAMEN_ASOCIADO_REGISTRO',
                    'Registros Volumétricos',
                    "Dictamen asociado a registro ID: {$id}",
                    'registros_volumetricos',
                    $id
                );

                return redirect()->route('registros-volumetricos.show', $id)
                    ->with('success', 'Dictamen asociado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al asociar dictamen'));

        } catch (\Exception $e) {
            Log::error('Error al asociar dictamen a registro', [
                'error' => $e->getMessage(),
                'registro_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al asociar dictamen');
        }
    }
}