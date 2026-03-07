<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DictamenController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar dictámenes
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'instalacion_id', 'tipo_dictamen', 'estado', 'fecha_inicio', 'fecha_fin', 'per_page'
        ]);

        $response = $this->apiGet('/api/dictamenes', $params);

        if ($this->apiResponseSuccessful($response)) {
            $dictamenes = $this->apiResponseData($response);
            return view('dictamenes.index', compact('dictamenes'));
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

        return view('dictamenes.create', compact('instalaciones'));
    }

    /**
     * Crear dictamen
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'required|integer|exists:instalaciones,id',
            'numero_dictamen' => 'required|string|max:50|unique:dictamenes,numero_dictamen',
            'tipo_dictamen' => 'required|in:inicial,periodico,extraordinario',
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

        $response = $this->apiPost('/api/dictamenes', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('dictamenes.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar dictamen
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/dictamenes/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $dictamen = $this->apiResponseData($response);
            return view('dictamenes.show', compact('dictamen'));
        }

        return redirect()->route('dictamenes.index')
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

        $response = $this->apiGet("/api/dictamenes/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $dictamen = $this->apiResponseData($response);
            return view('dictamenes.edit', compact('dictamen', 'instalaciones'));
        }

        return redirect()->route('dictamenes.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar dictamen
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'instalacion_id' => 'sometimes|integer|exists:instalaciones,id',
            'numero_dictamen' => 'sometimes|string|max:50|unique:dictamenes,numero_dictamen,' . $id,
            'tipo_dictamen' => 'sometimes|in:inicial,periodico,extraordinario',
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

        $response = $this->apiPut("/api/dictamenes/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('dictamenes.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar dictamen
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/dictamenes/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('dictamenes.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('dictamenes.index')
            ->with('error', $this->apiResponseMessage($response));
    }
}