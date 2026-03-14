<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ContribuyenteController extends BaseController
{
    /**
     * Listar contribuyentes
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'rfc', 'razon_social', 'regimen_fiscal', 'numero_permiso',
                'activo', 'proxima_verificacion', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/contribuyentes', $params);

            return $this->renderView('contribuyentes.index', $response, ['key' => 'contribuyentes'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar contribuyentes', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar contribuyentes');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('contribuyentes.create');
    }

    /**
     * Exportar contribuyentes
     */
    public function exportar(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            // Obtener parámetros de filtro opcionales
            $params = $request->only([
                'rfc', 'razon_social', 'nombre_comercial', 'regimen_fiscal', 'status'
            ]);

            $modulo = 'contribuyentes';
            $response = $this->apiGet('/api/exportar/' . $modulo, $params);

            if ($response->successful()) {
                // Si la API devuelve un archivo, lo enviamos directamente
                $contentType = $response->headers->get('Content-Type');
                $contentDisposition = $response->headers->get('Content-Disposition');

                return response($response->body(), $response->status())
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', $contentDisposition);
            }

            // Si no es exitoso, manejamos el error
            $json = $response->json();
            return $this->jsonError(
                $json['message'] ?? 'Error al exportar contribuyentes',
                $response->status(),
                $json['errors'] ?? null
            );
        } catch (\Exception $e) {
            Log::error('Error al exportar contribuyentes', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al exportar contribuyentes');
        }
    }

    /**
     * Crear contribuyente
     */
    public function store(Request $request)
    {
        $request->validate([
            'rfc' => 'required|string|size:13',
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'regimen_fiscal' => 'required|string|max:255',
            'domicilio_fiscal' => 'required|string|max:255',
            'codigo_postal' => 'required|string|size:5',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'representante_legal' => 'nullable|string|max:255',
            'representante_rfc' => 'nullable|string|size:13',
            'numero_permiso' => 'nullable|string|max:255',
            'tipo_permiso' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/contribuyentes', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $contribuyenteData = $this->apiResponseData($response, []);
                $contribuyenteId = $contribuyenteData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'CONTRIBUYENTE_CREADO',
                    'Contribuyentes',
                    "Contribuyente creado: {$request->rfc}",
                    'contribuyentes',
                    $contribuyenteId
                );

                return redirect()->route('contribuyentes.index')
                    ->with('success', 'Contribuyente creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear contribuyente'));

        } catch (\Exception $e) {
            Log::error('Error al crear contribuyente', [
                'error' => $e->getMessage(),
                'data' => $request->except('_token')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear contribuyente');
        }
    }

    /**
     * Mostrar contribuyente
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/contribuyentes/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('contribuyentes.index')
                    ->with('error', $this->apiResponseMessage($response, 'Contribuyente no encontrado'));
            }

            $contribuyente = $this->apiResponseData($response, []);

            return view('contribuyentes.show', [
                'contribuyente' => $contribuyente
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar contribuyente', [
                'error' => $e->getMessage(),
                'contribuyente_id' => $id
            ]);

            return redirect()->route('contribuyentes.index')
                ->with('error', 'Error al cargar contribuyente');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/contribuyentes/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('contribuyentes.index')
                    ->with('error', $this->apiResponseMessage($response, 'Contribuyente no encontrado'));
            }

            $contribuyente = $this->apiResponseData($response, []);

            return view('contribuyentes.edit', [
                'contribuyente' => $contribuyente
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'contribuyente_id' => $id
            ]);

            return redirect()->route('contribuyentes.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar contribuyente
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rfc' => 'sometimes|string|size:13',
            'razon_social' => 'sometimes|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'regimen_fiscal' => 'sometimes|string|max:255',
            'domicilio_fiscal' => 'sometimes|string|max:255',
            'codigo_postal' => 'sometimes|string|size:5',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'representante_legal' => 'nullable|string|max:255',
            'representante_rfc' => 'nullable|string|size:13',
            'numero_permiso' => 'nullable|string|max:255',
            'tipo_permiso' => 'nullable|string|max:255',
            'estatus_verificacion' => 'nullable|string|max:50',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/contribuyentes/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'CONTRIBUYENTE_ACTUALIZADO',
                    'Contribuyentes',
                    "Contribuyente actualizado ID: {$id}",
                    'contribuyentes',
                    $id
                );

                return redirect()->route('contribuyentes.show', $id)
                    ->with('success', 'Contribuyente actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar contribuyente'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar contribuyente', [
                'error' => $e->getMessage(),
                'contribuyente_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar contribuyente');
        }
    }

    /**
     * Eliminar contribuyente (soft delete)
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/contribuyentes/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'CONTRIBUYENTE_ELIMINADO',
                    'Contribuyentes',
                    "Contribuyente eliminado ID: {$id}",
                    'contribuyentes',
                    $id
                );

                return redirect()->route('contribuyentes.index')
                    ->with('success', 'Contribuyente eliminado exitosamente');
            }

            if ($response['status'] === 409) {
                return redirect()->back()
                    ->with('error', $this->apiResponseData($response, 'No se puede eliminar el contribuyente'));
            }

            return redirect()->route('contribuyentes.index')
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar contribuyente'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar contribuyente', [
                'error' => $e->getMessage(),
                'contribuyente_id' => $id
            ]);

            return redirect()->route('contribuyentes.index')
                ->with('error', 'Error al eliminar contribuyente');
        }
    }

    /**
     * Obtener instalaciones del contribuyente
     */
    public function instalaciones(Request $request, $id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only(['estatus', 'tipo', 'per_page']);

            $response = $this->apiGet("/api/contribuyentes/{$id}/instalaciones", $params);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('contribuyentes.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar instalaciones'));
            }

            $instalaciones = $this->apiResponseData($response, []);

            return view('contribuyentes.instalaciones', [
                'instalaciones' => $instalaciones['data'] ?? $instalaciones,
                'contribuyente_id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar instalaciones del contribuyente', [
                'error' => $e->getMessage(),
                'contribuyente_id' => $id
            ]);

            return redirect()->route('contribuyentes.show', $id)
                ->with('error', 'Error al cargar instalaciones');
        }
    }

    /**
     * Obtener resumen de cumplimiento
     */
    public function cumplimiento($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/contribuyentes/{$id}/cumplimiento");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('contribuyentes.show', $id)
                    ->with('error', $this->apiResponseMessage($response, 'Error al cargar cumplimiento'));
            }

            $cumplimiento = $this->apiResponseData($response, []);

            return view('contribuyentes.cumplimiento', [
                'cumplimiento' => $cumplimiento
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar cumplimiento del contribuyente', [
                'error' => $e->getMessage(),
                'contribuyente_id' => $id
            ]);

            return redirect()->route('contribuyentes.show', $id)
                ->with('error', 'Error al cargar cumplimiento');
        }
    }

    /**
     * Obtener catálogo para dropdowns
     */
    public function catalogo()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/contribuyentes/catalogo');

            if (!$this->apiResponseSuccessful($response)) {
                return $this->jsonError($this->apiResponseMessage($response, 'Error al cargar catálogo'), 400);
            }

            return $this->jsonSuccess($this->apiResponseData($response, []), 'Catálogo cargado');

        } catch (\Exception $e) {
            Log::error('Error al cargar catálogo de contribuyentes', [
                'error' => $e->getMessage()
            ]);

            return $this->jsonError('Error al cargar catálogo', 500);
        }
    }
}