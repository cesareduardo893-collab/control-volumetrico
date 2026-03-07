<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductoController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Listar productos
     */
    public function index(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $params = $request->only([
            'tipo', 'activo', 'per_page'
        ]);

        $response = $this->apiGet('/api/productos', $params);

        if ($this->apiResponseSuccessful($response)) {
            $productos = $this->apiResponseData($response);
            return view('productos.index', compact('productos'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
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
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'clave_producto' => 'required|string|max:50|unique:productos,clave_producto',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'tipo' => 'required|in:gasolina,diesel,combustoleo,petroleo,queroseno,otros',
            'clave_sat' => 'required|string|max:20',
            'clave_unidad' => 'required|string|max:10',
            'densidad_referencia' => 'required|numeric|min:0',
            'temperatura_referencia' => 'required|numeric',
            'factor_correccion' => 'required|numeric|min:0',
            'rango_temperatura_min' => 'required|numeric',
            'rango_temperatura_max' => 'required|numeric',
            'rango_presion_min' => 'required|numeric',
            'rango_presion_max' => 'required|numeric',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPost('/api/productos', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('productos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar producto
     */
    public function show($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/productos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $producto = $this->apiResponseData($response);
            return view('productos.show', compact('producto'));
        }

        return redirect()->route('productos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/productos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            $producto = $this->apiResponseData($response);
            return view('productos.edit', compact('producto'));
        }

        return redirect()->route('productos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'clave_producto' => 'sometimes|string|max:50|unique:productos,clave_producto,' . $id,
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'tipo' => 'sometimes|in:gasolina,diesel,combustoleo,petroleo,queroseno,otros',
            'clave_sat' => 'sometimes|string|max:20',
            'clave_unidad' => 'sometimes|string|max:10',
            'densidad_referencia' => 'sometimes|numeric|min:0',
            'temperatura_referencia' => 'sometimes|numeric',
            'factor_correccion' => 'sometimes|numeric|min:0',
            'rango_temperatura_min' => 'sometimes|numeric',
            'rango_temperatura_max' => 'sometimes|numeric',
            'rango_presion_min' => 'sometimes|numeric',
            'rango_presion_max' => 'sometimes|numeric',
            'activo' => 'sometimes|boolean'
        ]);

        $response = $this->apiPut("/api/productos/{$id}", $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('productos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiDelete("/api/productos/{$id}");

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('productos.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('productos.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Obtener productos por tipo
     */
    public function byTipo($tipo)
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet("/api/productos/tipo/{$tipo}");

        if ($this->apiResponseSuccessful($response)) {
            $productos = $this->apiResponseData($response);
            return view('productos.by-tipo', compact('productos', 'tipo'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }
}