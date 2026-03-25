<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProductoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_productos_list()
    {
        $productos = [
            [
                'id' => 1,
                'clave_sat' => '15101501',
                'nombre' => 'Gasolina',
                'tipo_hidrocarburo' => 'gasolina',
                'activo' => true
            ],
            [
                'id' => 2,
                'clave_sat' => '15101502',
                'nombre' => 'Diesel',
                'tipo_hidrocarburo' => 'diesel',
                'activo' => true
            ]
        ];

        $this->mockPaginatedResponse('/api/productos', $productos, 2);

        $response = $this->get('/productos');

        $response->assertStatus(200);
        $response->assertViewIs('productos.index');
        $response->assertViewHas('productos');
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $response = $this->get('/productos/create');

        $response->assertStatus(200);
        $response->assertViewIs('productos.create');
    }

    /** @test */
    public function test_store_creates_producto_successfully()
    {
        $productoData = [
            'clave_sat' => '15101501',
            'codigo' => 'GAS-001',
            'clave_identificacion' => 'GASOLINA',
            'nombre' => 'Gasolina Premium',
            'descripcion' => 'Gasolina de alto octanaje',
            'unidad_medida' => 'Litro',
            'tipo_hidrocarburo' => 'gasolina',
            'densidad_referencia' => 0.75,
            'temperatura_referencia' => 20,
            'octanaje' => 95
        ];

        $createdProducto = array_merge($productoData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/productos', $createdProducto, 'Producto creado exitosamente', 201);

        $response = $this->post('/productos', $productoData);

        $response->assertRedirect('/productos');
        $response->assertSessionHas('success', 'Producto creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors_for_duplicate_clave_sat()
    {
        $productoData = [
            'clave_sat' => '15101501',
            'codigo' => 'GAS-001',
            'clave_identificacion' => 'GASOLINA',
            'nombre' => 'Gasolina',
            'unidad_medida' => 'Litro',
            'tipo_hidrocarburo' => 'gasolina'
        ];

        $this->mockValidationErrorResponse('/api/productos', [
            'clave_sat' => ['La clave SAT ya está registrada']
        ]);

        $response = $this->post('/productos', $productoData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['clave_sat']);
    }

    /** @test */
    public function test_show_displays_producto_details()
    {
        $producto = [
            'id' => 1,
            'clave_sat' => '15101501',
            'codigo' => 'GAS-001',
            'nombre' => 'Gasolina Premium',
            'descripcion' => 'Gasolina de alto octanaje',
            'unidad_medida' => 'Litro',
            'tipo_hidrocarburo' => 'gasolina',
            'octanaje' => 95,
            'densidad_referencia' => 0.75,
            'activo' => true
        ];

        $this->mockSuccessfulResponse('/api/productos/1', $producto);

        $response = $this->get('/productos/1');

        $response->assertStatus(200);
        $response->assertViewIs('productos.show');
        $response->assertViewHas('producto', $producto);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $producto = [
            'id' => 1,
            'clave_sat' => '15101501',
            'nombre' => 'Gasolina',
            'tipo_hidrocarburo' => 'gasolina'
        ];

        $this->mockSuccessfulResponse('/api/productos/1', $producto);

        $response = $this->get('/productos/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('productos.edit');
        $response->assertViewHas('producto', $producto);
    }

    /** @test */
    public function test_update_modifies_producto_successfully()
    {
        $updateData = [
            'nombre' => 'Gasolina Magna',
            'octanaje' => 87,
            'descripcion' => 'Gasolina regular'
        ];

        $this->mockSuccessfulResponse('/api/productos/1', [], 'Producto actualizado exitosamente');

        $response = $this->put('/productos/1', $updateData);

        $response->assertRedirect('/productos/1');
        $response->assertSessionHas('success', 'Producto actualizado exitosamente');
    }

    /** @test */
    public function test_por_tipo_displays_products_by_type()
    {
        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina Premium', 'tipo_hidrocarburo' => 'gasolina'],
            ['id' => 2, 'nombre' => 'Gasolina Magna', 'tipo_hidrocarburo' => 'gasolina']
        ];

        $this->mockSuccessfulResponse('/api/productos/tipo/gasolina', $productos);

        $response = $this->get('/productos/tipo/gasolina');

        $response->assertStatus(200);
        $response->assertViewIs('productos.por-tipo');
        $response->assertViewHas('productos', $productos);
        $response->assertViewHas('tipo', 'gasolina');
    }

    /** @test */
    public function test_catalogo_returns_json_for_dropdowns()
    {
        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina', 'clave_sat' => '15101501'],
            ['id' => 2, 'nombre' => 'Diesel', 'clave_sat' => '15101502']
        ];

        $this->mockSuccessfulResponse('/api/productos/catalogo', $productos);

        $response = $this->get('/productos/catalogo/list');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function test_buscar_por_clave_sat_finds_product()
    {
        $producto = [
            'id' => 1,
            'clave_sat' => '15101501',
            'nombre' => 'Gasolina Premium',
            'unidad_medida' => 'Litro'
        ];

        $this->mockSuccessfulResponse('/api/productos/clave-sat/15101501', $producto);

        $response = $this->get('/productos/buscar/clave-sat/15101501');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.clave_sat', '15101501');
    }

    /** @test */
    public function test_buscar_por_clave_sat_returns_404_when_not_found()
    {
        $this->mockErrorResponse('/api/productos/clave-sat/99999999', 'Producto no encontrado', 404);

        $response = $this->get('/productos/buscar/clave-sat/99999999');

        $response->assertStatus(404);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function test_destroy_deletes_producto_successfully()
    {
        $this->mockSuccessfulResponse('/api/productos/1', [], 'Producto eliminado exitosamente');

        $response = $this->delete('/productos/1');

        $response->assertRedirect('/productos');
        $response->assertSessionHas('success', 'Producto eliminado exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_producto_has_related_records()
    {
        $this->mockErrorResponse('/api/productos/1', 'No se puede eliminar el producto', 409);

        $response = $this->delete('/productos/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar el producto');
    }

    /** @test */
    public function test_filter_productos_by_tipo()
    {
        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina', 'tipo_hidrocarburo' => 'gasolina']
        ];

        $this->mockSuccessfulResponse('/api/productos?tipo_hidrocarburo=gasolina', ['data' => $productos]);

        $response = $this->get('/productos?tipo_hidrocarburo=gasolina');

        $response->assertStatus(200);
        $response->assertViewHas('productos');
        
        $productos = $response->viewData('productos');
        $this->assertCount(1, $productos);
        $this->assertEquals('gasolina', $productos[0]['tipo_hidrocarburo']);
    }

    /** @test */
    public function test_exportar_downloads_productos_file()
    {
        Http::fake([
            $this->baseApiUrl . '/api/exportar/productos*' => Http::response(
                'csv content',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="productos.csv"'
                ]
            )
        ]);

        $response = $this->get('/productos/exportar');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="productos.csv"');
    }
}