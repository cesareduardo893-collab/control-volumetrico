<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CertificadoVerificacionController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar certificados de verificación
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'instalacion_id', 'tipo_certificado', 'estado', 'fecha_inicio', 'fecha_fin', 'per_page'
        ]);

        $response = $this->apiGet('/api/certificados-verificacion', $params);

        if ($this->apiResponseSuccessful($response)) {
            $certificados = $this->apiResponseData($response);
            return view('certificados-verificacion.index', compact('certificados'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones para el select
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        return view('certificados-verificacion.create', compact('instalaciones'));
    }

    /**
     * Crear certificado de verificación
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'required|integer|exists:instalaciones,id',
            'numero_certificado' => 'required|string|max:50|unique:certificados_verificacion,numero_certificado',
            'tipo_certificado' => 'required|in:inicial,periodico,extraordinario',
            'fecha_emision' => 'required|date',
            'fecha_vigencia' => 'required|date|after:fecha_emision',
            'estatus' => 'required|in:aprobado,rechazado,pendiente',
            'observaciones' => 'nullable|string|max:500',
            'resultado' => 'required|in:aprobado,rechazado,observaciones',
            'puntos_atencion' => 'nullable|integer|min:0',
            'puntos_criticos' => 'nullable|integer|min:0',
            'puntos_leves' => 'nullable|integer|min:0',
            'usuario_elaboracion' => 'required|integer|exists:users,id',
            'usuario_autorizacion' => 'nullable|integer|exists:users,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/certificados-verificacion', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('certificados-verificacion.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar certificado de verificación
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/certificados-verificacion/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $certificado = $this->apiResponseData($response);
            return view('certificados-verificacion.show', compact('certificado'));
        }

        return redirect()->route('certificados-verificacion.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener instalaciones para el select
        $instalacionesResponse = $this->apiGet('/api/instalaciones');
        $instalaciones = $this->apiResponseSuccessful($instalacionesResponse) 
            ? $this->apiResponseData($instalacionesResponse) 
            : [];

        $response = $this->apiGet("/api/certificados-verificacion/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $certificado = $this->apiResponseData($response);
            return view('certificados-verificacion.edit', compact('certificado', 'instalaciones'));
        }

        return redirect()->route('certificados-verificacion.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar certificado de verificación
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'sometimes|integer|exists:instalaciones,id',
            'numero_certificado' => 'sometimes|string|max:50',
            'tipo_certificado' => 'sometimes|in:inicial,periodico,extraordinario',
            'fecha_emision' => 'sometimes|date',
            'fecha_vigencia' => 'sometimes|date|after:fecha_emision',
            'estatus' => 'sometimes|in:aprobado,rechazado,pendiente',
            'observaciones' => 'nullable|string|max:500',
            'resultado' => 'sometimes|in:aprobado,rechazado,observaciones',
            'puntos_atencion' => 'nullable|integer|min:0',
            'puntos_criticos' => 'nullable|integer|min:0',
            'puntos_leves' => 'nullable|integer|min:0',
            'usuario_elaboracion' => 'sometimes|integer|exists:users,id',
            'usuario_autorizacion' => 'nullable|integer|exists:users,id',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/certificados-verificacion/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('certificados-verificacion.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar certificado de verificación
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/certificados-verificacion/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('certificados-verificacion.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('certificados-verificacion.index')
            ->with('error', $this->apiResponseMessage($response));
    }
}