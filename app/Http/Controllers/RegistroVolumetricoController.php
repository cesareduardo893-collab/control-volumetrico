<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
                'rfc_contraparte', 'per_page', 'page',
            ]);

            $response = $this->apiGet('/api/registros-volumetricos', $params);

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar registros'));
            }

            $responseData = $this->apiResponseData($response, []);
            $registros = $responseData['data'] ?? [];

            // Calcular resumen de totales
            $resumen = [
                'total' => count($registros),
                'validados' => 0,
                'pendientes' => 0,
                'con_alarma' => 0,
            ];

            foreach ($registros as $registro) {
                $estado = $registro['estado'] ?? '';
                if ($estado === 'VALIDADO') {
                    $resumen['validados']++;
                } elseif ($estado === 'PENDIENTE') {
                    $resumen['pendientes']++;
                } elseif ($estado === 'CON_ALARMA') {
                    $resumen['con_alarma']++;
                }
            }

            // Obtener catálogos para los filtros
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);
            $tanques = $this->getCatalog('/api/tanques', ['activo' => true]);
            $productos = $this->getCatalog('/api/productos', ['activo' => true]);

            return view('registros-volumetricos.index', [
                'registros' => $registros,
                'resumen' => $resumen,
                'meta' => $responseData['meta'] ?? [],
                'links' => $responseData['links'] ?? [],
                'instalaciones' => $instalaciones,
                'tanques' => $tanques,
                'productos' => $productos,
                'filters' => $request->all(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error al listar registros volumétricos', [
                'error' => $e->getMessage(),
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
                'usuarios' => $usuarios,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage(),
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
            'usuario_registro_id' => 'nullable|integer',
            'documento_fiscal_uuid' => 'nullable|string|max:255',
            'rfc_contraparte' => 'nullable|string|max:13',
            'observaciones' => 'nullable|string',
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
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear registro');
        }
    }

    /**
     * Crear registro volumétrico usando el emulador
     */
    public function crearConEmulador(Request $request)
    {
        $request->validate([
            'instalacion_id' => 'required|integer',
            'tanque_id' => 'required|integer',
            'medidor_id' => 'nullable|integer',
            'volumen_inicial' => 'required|numeric|min:0',
            'volumen_final' => 'required|numeric|min:0',
            'volumen_operacion' => 'required|numeric|min:0',
            'volumen_corregido' => 'required|numeric|min:0',
            'temperatura' => 'nullable|numeric',
            'presion' => 'nullable|numeric|min:0',
            'densidad' => 'required|numeric|min:0',
            'factor_correccion' => 'required|numeric|min:0',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s',
            'fecha' => 'required|date',
            'tipo_registro' => 'required|in:operacion,acumulado,existencias',
            'operacion' => 'required|in:recepcion,entrega,inventario_inicial,inventario_final,venta',
            'estado' => 'required|in:PENDIENTE,PROCESADO,VALIDADO,CON_ALARMA',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/registros-volumetricos/emulador', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $responseData = $this->apiResponseData($response, []);
                $registroId = $responseData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'REGISTRO_VOLUMETRICO_EMULADOR_CREADO',
                    'Registros Volumétricos',
                    'Registro volumétrico creado desde emulador',
                    'registros_volumetricos',
                    $registroId
                );

                // Siempre redirigir al índice con mensaje de éxito
                return redirect()->route('registros-volumetricos.index')
                    ->with('success', 'Registro volumétrico creado exitosamente desde emulador');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);

                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear registro desde emulador'));

        } catch (\Exception $e) {
            Log::error('Error al crear registro volumétrico desde emulador', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear registro desde emulador');
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

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->route('registros-volumetricos.index')
                    ->with('error', $this->apiResponseMessage($response, 'Registro no encontrado'));
            }

            $registro = $this->apiResponseData($response, []);

            return view('registros-volumetricos.show', [
                'registro' => $registro,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar registro volumétrico', [
                'error' => $e->getMessage(),
                'registro_id' => $id,
            ]);

            return redirect()->route('registros-volumetricos.index')
                ->with('error', 'Error al cargar registro');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/registros-volumetricos/{$id}");

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->route('registros-volumetricos.index')
                    ->with('error', $this->apiResponseMessage($response, 'Registro no encontrado'));
            }

            $registro = $this->apiResponseData($response, []);

            // Obtener catálogos para los selects
            $instalaciones = $this->getCatalog('/api/instalaciones', ['activo' => true]);
            $tanques = $this->getCatalog('/api/tanques', ['activo' => true]);
            $medidores = $this->getCatalog('/api/medidores', ['activo' => true]);
            $productos = $this->getCatalog('/api/productos', ['activo' => true]);
            $usuarios = $this->getCatalog('/api/users');

            return view('registros-volumetricos.edit', [
                'registro' => $registro,
                'instalaciones' => $instalaciones,
                'tanques' => $tanques,
                'medidores' => $medidores,
                'productos' => $productos,
                'usuarios' => $usuarios,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'registro_id' => $id,
            ]);

            return redirect()->route('registros-volumetricos.index')
                ->with('error', 'Error al cargar formulario de edición');
        }
    }

    /**
     * Actualizar registro volumétrico
     */
    public function update(Request $request, $id)
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
            'usuario_registro_id' => 'nullable|integer',
            'documento_fiscal_uuid' => 'nullable|string|max:255',
            'rfc_contraparte' => 'nullable|string|max:13',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/registros-volumetricos/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'REGISTRO_VOLUMETRICO_ACTUALIZADO',
                    'Registros Volumétricos',
                    "Registro volumétrico actualizado: {$request->numero_registro}",
                    'registros_volumetricos',
                    $id
                );

                return redirect()->route('registros-volumetricos.show', $id)
                    ->with('success', 'Registro volumétrico actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);

                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar registro'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar registro volumétrico', [
                'error' => $e->getMessage(),
                'registro_id' => $id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar registro');
        }
    }

    /**
     * Eliminar registro volumétrico
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/registros-volumetricos/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_OPERACIONES,
                    'REGISTRO_VOLUMETRICO_ELIMINADO',
                    'Registros Volumétricos',
                    "Registro volumétrico eliminado ID: {$id}",
                    'registros_volumetricos',
                    $id
                );

                return redirect()->route('registros-volumetricos.index')
                    ->with('success', 'Registro volumétrico eliminado exitosamente');
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar registro'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar registro volumétrico', [
                'error' => $e->getMessage(),
                'registro_id' => $id,
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar registro');
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
                'registro_id' => $id,
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
                'registro_id' => $id,
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

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al generar resumen'));
            }

            $resumen = $this->apiResponseData($response, []);

            return view('registros-volumetricos.resumen-diario', [
                'resumen' => $resumen,
                'filters' => $request->all(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener resumen diario', [
                'error' => $e->getMessage(),
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

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar estadísticas'));
            }

            $estadisticas = $this->apiResponseData($response, []);

            return view('registros-volumetricos.estadisticas', [
                'estadisticas' => $estadisticas,
                'filters' => $request->all(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas mensuales', [
                'error' => $e->getMessage(),
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
                'registro_id' => $id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al asociar dictamen');
        }
    }

    /**
     * Exportar registros volumétricos
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'instalacion_id', 'tanque_id', 'medidor_id', 'producto_id',
                'numero_registro', 'fecha', 'fecha_inicio', 'fecha_fin',
                'tipo_registro', 'operacion', 'estado', 'documento_fiscal_uuid',
                'rfc_contraparte',
            ]);

            $response = $this->apiGet('/api/registros-volumetricos/exportar', $params);

            if (! $this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al exportar registros'));
            }

            $data = $this->apiResponseData($response, []);

            // Crear CSV
            $filename = 'registros_volumetricos_'.date('Y-m-d_H-i-s').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ];

            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');

                // Encabezados
                fputcsv($file, [
                    'ID',
                    'Número Registro',
                    'Instalación',
                    'Tanque',
                    'Producto',
                    'Fecha',
                    'Hora Inicio',
                    'Hora Fin',
                    'Volumen Inicial',
                    'Volumen Final',
                    'Volumen Operación',
                    'Volumen Corregido',
                    'Temperatura Inicial',
                    'Temperatura Final',
                    'Densidad',
                    'Factor Corrección',
                    'Tipo Registro',
                    'Operación',
                    'Estado',
                    'Observaciones',
                ]);

                // Datos
                foreach ($data as $registro) {
                    fputcsv($file, [
                        $registro['id'] ?? '',
                        $registro['numero_registro'] ?? '',
                        $registro['instalacion']['nombre'] ?? '',
                        $registro['tanque']['identificador'] ?? '',
                        $registro['producto']['nombre'] ?? '',
                        $registro['fecha'] ?? '',
                        $registro['hora_inicio'] ?? '',
                        $registro['hora_fin'] ?? '',
                        $registro['volumen_inicial'] ?? '',
                        $registro['volumen_final'] ?? '',
                        $registro['volumen_operacion'] ?? '',
                        $registro['volumen_corregido'] ?? '',
                        $registro['temperatura_inicial'] ?? '',
                        $registro['temperatura_final'] ?? '',
                        $registro['densidad'] ?? '',
                        $registro['factor_correccion'] ?? '',
                        $registro['tipo_registro'] ?? '',
                        $registro['operacion'] ?? '',
                        $registro['estado'] ?? '',
                        $registro['observaciones'] ?? '',
                    ]);
                }

                fclose($file);
            };

            $this->logActivity(
                Session::get('user_id'),
                Bitacora::TIPO_EVENTO_OPERACIONES,
                'REGISTRO_VOLUMETRICO_EXPORTADO',
                'Registros Volumétricos',
                'Registros volumétricos exportados',
                'registros_volumetricos',
                null
            );

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error al exportar registros volumétricos', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Error al exportar registros');
        }
    }

    /**
     * Proxy para lectura del emulador
     */
    public function emuladorLectura($tanqueId)
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            $response = $this->apiGet("/api/emulador/lectura/{$tanqueId}");

            return response()->json([
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? [],
                'message' => $response['message'] ?? '',
            ], $response['status'] ?? 200);

        } catch (\Exception $e) {
            Log::error('Error en proxy emulador lectura', [
                'error' => $e->getMessage(),
                'tanque_id' => $tanqueId,
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error al conectar con el emulador: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para tanques del emulador
     */
    public function emuladorTanques($instalacionId)
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            $response = $this->apiGet("/api/emulador/tanques/instalacion/{$instalacionId}");

            return response()->json([
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? [],
                'message' => $response['message'] ?? '',
            ], $response['status'] ?? 200);

        } catch (\Exception $e) {
            Log::error('Error en proxy emulador tanques', [
                'error' => $e->getMessage(),
                'instalacion_id' => $instalacionId,
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error al conectar con el emulador: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para datos automáticos del emulador
     */
    public function emuladorDatosAutomaticos()
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            $response = $this->apiGet('/api/emulador/tanque/datos-automaticos');

            return response()->json([
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? [],
                'message' => $response['message'] ?? '',
            ], $response['status'] ?? 200);

        } catch (\Exception $e) {
            Log::error('Error en proxy emulador datos automáticos', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error al conectar con el emulador: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para serial del emulador
     */
    public function emuladorSerial($instalacionId)
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            $response = $this->apiGet("/api/emulador/tanque/serial/{$instalacionId}");

            return response()->json([
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? [],
                'message' => $response['message'] ?? '',
            ], $response['status'] ?? 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error al conectar con el emulador: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para simular llenado
     */
    public function emuladorSimularLlenado(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            $response = $this->apiPost('/api/emulador/tanque/simular-llenado', $request->all());

            return response()->json([
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? [],
                'message' => $response['message'] ?? '',
            ], $response['status'] ?? 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error al conectar con el emulador: '.$e->getMessage(),
            ], 500);
        }
    }

    public function emuladorDetenerLlenado($tanqueId)
    {
        try {
            $this->setApiToken(Session::get('api_token'));
            $response = $this->apiGet("/api/emulador/lectura/{$tanqueId}");

            if ($this->apiResponseSuccessful($response)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'volumen_final' => $response['data']['volumen'] ?? 0,
                        'estado' => 'DETENIDO',
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $response['message'] ?? 'Error al detener llenado',
            ], $response['status'] ?? 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error al conectar con el emulador: '.$e->getMessage(),
            ], 500);
        }
    }
}
