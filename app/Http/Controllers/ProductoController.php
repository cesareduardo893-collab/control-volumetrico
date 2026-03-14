<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ProductoController extends BaseController
{
    /**
     * Listar productos
     */
    public function index(Request $request)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $params = $request->only([
                'clave_sat', 'codigo', 'nombre', 'tipo_hidrocarburo',
                'activo', 'per_page', 'page'
            ]);

            $response = $this->apiGet('/api/productos', $params);

            return $this->renderView('productos.index', $response, ['key' => 'productos'], $request->all());

        } catch (\Exception $e) {
            Log::error('Error al listar productos', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar productos');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Crear producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'clave_sat' => 'required|string|size:10',
            'codigo' => 'required|string|max:20',
            'clave_identificacion' => 'required|string|size:10',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'tipo_hidrocarburo' => 'required|in:petroleo,gas_natural,condensados,gasolina,diesel,turbosina,gas_lp,propano,otro',
            'activo' => 'sometimes|boolean',
            'densidad_referencia' => 'nullable|numeric|min:0',
            'temperatura_referencia' => 'nullable|numeric',
            'factor_conversion' => 'nullable|numeric|min:0',
            'octanaje' => 'nullable|numeric',
            'numero_octano' => 'nullable|numeric',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPost('/api/productos', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $productoData = $this->apiResponseData($response, []);
                $productoId = $productoData['id'] ?? null;

                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'PRODUCTO_CREADO',
                    'Productos',
                    "Producto creado: {$request->clave_sat}",
                    'productos',
                    $productoId
                );

                return redirect()->route('productos.index')
                    ->with('success', 'Producto creado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al crear producto'));

        } catch (\Exception $e) {
            Log::error('Error al crear producto', [
                'error' => $e->getMessage(),
                'data' => $request->except('_token')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear producto');
        }
    }

    /**
     * Mostrar producto
     */
    public function show($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/productos/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('productos.index')
                    ->with('error', $this->apiResponseMessage($response, 'Producto no encontrado'));
            }

            $producto = $this->apiResponseData($response, []);

            return view('productos.show', [
                'producto' => $producto
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar producto', [
                'error' => $e->getMessage(),
                'producto_id' => $id
            ]);

            return redirect()->route('productos.index')
                ->with('error', 'Error al cargar producto');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/productos/{$id}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('productos.index')
                    ->with('error', $this->apiResponseMessage($response, 'Producto no encontrado'));
            }

            $producto = $this->apiResponseData($response, []);

            return view('productos.edit', [
                'producto' => $producto
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'error' => $e->getMessage(),
                'producto_id' => $id
            ]);

            return redirect()->route('productos.index')
                ->with('error', 'Error al cargar formulario');
        }
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'clave_sat' => 'sometimes|string|size:10',
            'codigo' => 'sometimes|string|max:20',
            'clave_identificacion' => 'sometimes|string|size:10',
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'sometimes|string|max:50',
            'tipo_hidrocarburo' => 'sometimes|in:petroleo,gas_natural,condensados,gasolina,diesel,turbosina,gas_lp,propano,otro',
            'activo' => 'sometimes|boolean',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiPut("/api/productos/{$id}", $request->all());

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'PRODUCTO_ACTUALIZADO',
                    'Productos',
                    "Producto actualizado ID: {$id}",
                    'productos',
                    $id
                );

                return redirect()->route('productos.show', $id)
                    ->with('success', 'Producto actualizado exitosamente');
            }

            if ($response['status'] === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al actualizar producto'));

        } catch (\Exception $e) {
            Log::error('Error al actualizar producto', [
                'error' => $e->getMessage(),
                'producto_id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar producto');
        }
    }

    /**
     * Eliminar producto (soft delete)
     */
    public function destroy($id)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiDelete("/api/productos/{$id}");

            if ($this->apiResponseSuccessful($response)) {
                $this->logActivity(
                    Session::get('user_id'),
                    Bitacora::TIPO_EVENTO_ADMINISTRACION,
                    'PRODUCTO_ELIMINADO',
                    'Productos',
                    "Producto eliminado ID: {$id}",
                    'productos',
                    $id
                );

                return redirect()->route('productos.index')
                    ->with('success', 'Producto eliminado exitosamente');
            }

            if ($response['status'] === 409) {
                return redirect()->back()
                    ->with('error', $this->apiResponseData($response, 'No se puede eliminar el producto'));
            }

            return redirect()->route('productos.index')
                ->with('error', $this->apiResponseMessage($response, 'Error al eliminar producto'));

        } catch (\Exception $e) {
            Log::error('Error al eliminar producto', [
                'error' => $e->getMessage(),
                'producto_id' => $id
            ]);

            return redirect()->route('productos.index')
                ->with('error', 'Error al eliminar producto');
        }
    }

    /**
     * Obtener productos por tipo
     */
    public function porTipo($tipo)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/productos/tipo/{$tipo}");

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()->with('error', $this->apiResponseMessage($response, 'Error al cargar productos'));
            }

            $productos = $this->apiResponseData($response, []);

            if (request()->expectsJson()) {
                return $this->jsonSuccess($productos, 'Productos obtenidos');
            }

            return view('productos.por-tipo', [
                'productos' => $productos,
                'tipo' => $tipo
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar productos por tipo', [
                'error' => $e->getMessage(),
                'tipo' => $tipo
            ]);

            return redirect()->back()->with('error', 'Error al cargar productos');
        }
    }

    /**
     * Obtener catálogo para dropdowns
     */
    public function catalogo()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/productos/catalogo');

            if (!$this->apiResponseSuccessful($response)) {
                return $this->jsonError($this->apiResponseMessage($response, 'Error al cargar catálogo'), 400);
            }

            return $this->jsonSuccess($this->apiResponseData($response, []), 'Catálogo cargado');

        } catch (\Exception $e) {
            Log::error('Error al cargar catálogo de productos', [
                'error' => $e->getMessage()
            ]);

            return $this->jsonError('Error al cargar catálogo', 500);
        }
    }

    /**
     * Buscar por clave SAT
     */
    public function buscarPorClaveSat($claveSat)
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet("/api/productos/clave-sat/{$claveSat}");

            if (!$this->apiResponseSuccessful($response)) {
                return $this->jsonError($this->apiResponseMessage($response, 'Producto no encontrado'), 404);
            }

            return $this->jsonSuccess($this->apiResponseData($response, []), 'Producto encontrado');

        } catch (\Exception $e) {
            Log::error('Error al buscar producto por clave SAT', [
                'error' => $e->getMessage(),
                'clave_sat' => $claveSat
            ]);

            return $this->jsonError('Error al buscar producto', 500);
        }
    }
}