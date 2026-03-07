<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CfdiController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar CFDI
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'contribuyente_id', 'tipo_cfdi', 'estado', 'fecha_inicio', 'fecha_fin', 'per_page'
        ]);

        $response = $this->apiGet('/api/cfdi', $params);

        if ($this->apiResponseSuccessful($response)) {
            $cfdi = $this->apiResponseData($response);
            return view('cfdi.index', compact('cfdi'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $this->setApiToken(session('api_token'));

        // Obtener contribuyentes para el select
        $contribuyentesResponse = $this->apiGet('/api/contribuyentes');
        $contribuyentes = $this->apiResponseSuccessful($contribuyentesResponse) 
            ? $this->apiResponseData($contribuyentesResponse) 
            : [];

        return view('cfdi.create', compact('contribuyentes'));
    }

    /**
     * Crear CFDI
     */
    public function store(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'contribuyente_id' => 'required|integer|exists:contribuyentes,id',
            'uuid' => 'required|string|max:36|unique:cfdi,uuid',
            'rfc_emisor' => 'required|string|size:13',
            'rfc_receptor' => 'required|string|size:13',
            'fecha_emision' => 'required|date',
            'fecha_certificacion' => 'required|date',
            'total' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'iva' => 'required|numeric|min:0',
            'tipo_cfdi' => 'required|in:ingreso,egreso,traslado,pago',
            'estado' => 'required|in:vigente,cancelado',
            'xml_content' => 'required|string',
            'pdf_content' => 'nullable|string',
            'sello_sat' => 'required|string',
            'sello_cfdi' => 'required|string',
            'certificado_sat' => 'required|string',
            'certificado_cfdi' => 'required|string',
            'cadena_original' => 'required|string',
            'no_certificado_sat' => 'required|string|max:20',
            'no_certificado_cfdi' => 'required|string|max:20',
            'observaciones' => 'nullable|string|max:500',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/cfdi', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('cfdi.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar CFDI
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/cfdi/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $cfdi = $this->apiResponseData($response);
            return view('cfdi.show', compact('cfdi'));
        }

        return redirect()->route('cfdi.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        // Obtener contribuyentes para el select
        $contribuyentesResponse = $this->apiGet('/api/contribuyentes');
        $contribuyentes = $this->apiResponseSuccessful($contribuyentesResponse) 
            ? $this->apiResponseData($contribuyentesResponse) 
            : [];

        $response = $this->apiGet("/api/cfdi/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $cfdi = $this->apiResponseData($response);
            return view('cfdi.edit', compact('cfdi', 'contribuyentes'));
        }

        return redirect()->route('cfdi.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar CFDI
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'contribuyente_id' => 'sometimes|integer|exists:contribuyentes,id',
            'uuid' => 'sometimes|string|max:36',
            'rfc_emisor' => 'sometimes|string|size:13',
            'rfc_receptor' => 'sometimes|string|size:13',
            'fecha_emision' => 'sometimes|date',
            'fecha_certificacion' => 'sometimes|date',
            'total' => 'sometimes|numeric|min:0',
            'subtotal' => 'sometimes|numeric|min:0',
            'iva' => 'sometimes|numeric|min:0',
            'tipo_cfdi' => 'sometimes|in:ingreso,egreso,traslado,pago',
            'estado' => 'sometimes|in:vigente,cancelado',
            'xml_content' => 'sometimes|string',
            'pdf_content' => 'nullable|string',
            'sello_sat' => 'sometimes|string',
            'sello_cfdi' => 'sometimes|string',
            'certificado_sat' => 'sometimes|string',
            'certificado_cfdi' => 'sometimes|string',
            'cadena_original' => 'sometimes|string',
            'no_certificado_sat' => 'sometimes|string|max:20',
            'no_certificado_cfdi' => 'sometimes|string|max:20',
            'observaciones' => 'nullable|string|max:500',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/cfdi/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('cfdi.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar CFDI
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/cfdi/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('cfdi.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('cfdi.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Cancelar CFDI
     */
    public function cancelar($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost("/api/cfdi/{$id}/cancelar");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('cfdi.show', $id)
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('cfdi.show', $id)
            ->with('error', $this->apiResponseMessage($response));
    }
}