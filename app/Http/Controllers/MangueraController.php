<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class MangueraController extends BaseController
{
    /**
     * Listar mangueras
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'dispensario_id', 'medidor_id', 'clave', 'estado', 'activo', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/mangueras', $params);

            return $this->renderView('mangueras.index', $response, ['key' => 'mangueras'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar mangueras', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar mangueras');
        }
    }

    /**
     * Mostrar manguera
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/mangueras/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('mangueras.index')
                    ->with('error', $this->apiResponseMessage($response, 'Manguera no encontrada'));
            }

            $manguera = $this->apiResponseData($response, []);

            return view('mangueras.show', [
                'manguera' => $manguera
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar manguera', [
                'error' => $e->getMessage(),
                'manguera_id' => $id
            ]);

            return redirect()->route('mangueras.index')
                ->with('error', 'Error al cargar manguera');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/mangueras/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('mangueras.index')
                    ->with('error', $this->apiResponseMessage($response, 'Manguera no encontrada'));
            }

            $manguera = $this->apiResponseData($response, []);

            // Obtener medidores disponibles para asignar
            $medidores = $this->getCatalog('/api/medidores', ['activo' => true]);

            return view('mangueras.edit', [
                'manguera' => $manguera,
                'medidores' => $medidores
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'manguera_id' => $id
            ]);

            return redirect()->route('mangueras.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar manguera
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'nullable|string',
            'medidor_id' => 'nullable|integer',
            'estado' => 'sometimes|in:OPERATIVO,MANTENIMIENTO,FUERA_SERVICIO',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/mangueras/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'configuracion',
                    'MANGUERA_ACTUALIZADA',
                    'Mangueras',
                    "Manguera actualizada ID: {$id}",
                    'mangueras',
                    $id
                );

                return redirect()->route('mangueras.show', $id)
                    ->with('success', 'Manguera actualizada exitosamente');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar manguera'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar manguera', [
                'error' => $e->getMessage(),
                'manguera_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar manguera');
        }
    }

    /**
     * Eliminar manguera
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/mangueras/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'configuracion',
                    'MANGUERA_ELIMINADA',
                    'Mangueras',
                    "Manguera eliminada ID: {$id}",
                    'mangueras',
                    $id
                );

                return redirect()->route('mangueras.index')
                    ->with('success', 'Manguera eliminada exitosamente');
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar manguera'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar manguera', [
                'error' => $e->getMessage(),
                'manguera_id' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar manguera');
        }
    }

    /**
     * Asignar medidor a manguera
     */
    public function asignarMedidor(Request $request, $id)
    {
        $request->validate([
            'medidor_id' => 'required|integer',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/mangueras/{$id}/asignar-medidor", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'configuracion',
                    'MEDIDOR_ASIGNADO_MANGUERA',
                    'Mangueras',
                    "Medidor asignado a manguera ID: {$id}",
                    'mangueras',
                    $id
                );

                return redirect()->route('mangueras.show', $id)
                    ->with('success', 'Medidor asignado exitosamente');
            }

            if ($response->status === 422) {
                return redirect()->back()
                    ->with('error', 'El medidor ya está asignado a otra manguera');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al asignar medidor'));

        } catch (\Exception $e) {
            Log::error('Error al asignar medidor a manguera', [
                'error' => $e->getMessage(),
                'manguera_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al asignar medidor');
        }
    }

    /**
     * Quitar medidor de manguera
     */
    public function quitarMedidor($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost("/api/mangueras/{$id}/quitar-medidor");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    'configuracion',
                    'MEDIDOR_QUITADO_MANGUERA',
                    'Mangueras',
                    "Medidor quitado de manguera ID: {$id}",
                    'mangueras',
                    $id
                );

                return redirect()->route('mangueras.show', $id)
                    ->with('success', 'Medidor quitado exitosamente');
            }

            return redirect()->back()
                ->with('error', $this->apiResponseMessage($response, 'Error al quitar medidor'));

        } catch (\Exception $e) {
            Log::error('Error al quitar medidor de manguera', [
                'error' => $e->getMessage(),
                'manguera_id' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Error al quitar medidor');
        }
    }
}