<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PedimentoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_pedimentos_list()
    {
        $pedimentos = [
            [
                'id' => 1,
                'numero_pedimento' => 'PED-001',
                'contribuyente' => ['razon_social' => 'Empresa 1'],
                'producto' => ['nombre' => 'Gasolina'],
                'pais_origen' => 'USA',
                'pais_destino' => 'MEX',
                'fecha_pedimento' => '2024-01-15',
                'estado' => 'ACTIVO',
            ],
            [
                'id' => 2,
                'numero_pedimento' => 'PED-002',
                'contribuyente' => ['razon_social' => 'Empresa 2'],
                'producto' => ['nombre' => 'Diesel'],
                'pais_origen' => 'CAN',
                'pais_destino' => 'MEX',
                'fecha_pedimento' => '2024-01-16',
                'estado' => 'UTILIZADO',
            ],
        ];

        $this->mockPaginatedResponse('/api/pedimentos', $pedimentos, 2);

        $response = $this->get('/pedimentos');

        $response->assertStatus(200);
        $response->assertViewIs('pedimentos.index');
        $response->assertViewHas('pedimentos');
    }

    /** @test */
    public function test_show_displays_pedimento_details()
    {
        $pedimento = [
            'id' => 1,
            'numero_pedimento' => 'PED-001',
            'contribuyente' => ['id' => 1, 'razon_social' => 'Empresa 1'],
            'producto' => ['id' => 1, 'nombre' => 'Gasolina'],
            'pais_origen' => 'USA',
            'pais_destino' => 'MEX',
            'fecha_pedimento' => '2024-01-15',
            'estado' => 'ACTIVO',
        ];

        $this->mockSuccessfulResponse('/api/pedimentos/1', $pedimento);

        $response = $this->get('/pedimentos/1');

        $response->assertStatus(200);
        $response->assertViewIs('pedimentos.show');
        $response->assertViewHas('pedimento', $pedimento);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $pedimento = [
            'id' => 1,
            'numero_pedimento' => 'PED-001',
            'contribuyente_id' => 1,
            'producto_id' => 1,
            'estado' => 'ACTIVO',
        ];

        $contribuyentes = [
            ['id' => 1, 'razon_social' => 'Empresa 1'],
            ['id' => 2, 'razon_social' => 'Empresa 2'],
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina', 'clave_sat' => '15101501'],
            ['id' => 2, 'nombre' => 'Diesel', 'clave_sat' => '15101502'],
        ];

        $this->mockSuccessfulResponse('/api/pedimentos/1', $pedimento);
        $this->mockSuccessfulResponse('/api/catalogo/contribuyentes', $contribuyentes);
        $this->mockSuccessfulResponse('/api/catalogo/productos', $productos);

        $response = $this->get('/pedimentos/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('pedimentos.edit');
        $response->assertViewHas('pedimento', $pedimento);
        $response->assertViewHas('contribuyentes', $contribuyentes);
        $response->assertViewHas('productos', $productos);
    }

    /** @test */
    public function test_filter_pedimentos_by_estado()
    {
        $pedimentos = [
            ['id' => 1, 'estado' => 'ACTIVO', 'numero_pedimento' => 'PED-001', 'contribuyente' => ['razon_social' => 'Empresa 1'], 'producto' => ['nombre' => 'Gasolina'], 'pais_origen' => 'USA', 'pais_destino' => 'MEX', 'fecha_pedimento' => '2024-01-15'],
        ];

        $this->mockSuccessfulResponse('/api/pedimentos', ['data' => $pedimentos]);

        $response = $this->get('/pedimentos?estado=ACTIVO');

        $response->assertStatus(200);
        $response->assertViewHas('pedimentos');

        $pedimentos = $response->viewData('pedimentos');
        $this->assertCount(1, $pedimentos);
        $this->assertEquals('ACTIVO', $pedimentos[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_pedimentos_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/pedimentos/exportar*' => Http::response(
                'numero_pedimento,estado,fecha_pedimento\nPED-001,ACTIVO,2024-01-15',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="pedimentos.csv"',
                ]
            ),
        ]);

        $response = $this->get('/pedimentos/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_resumen_comercio_exterior_displays_summary()
    {
        $filters = [
            'contribuyente_id' => 1,
            'anio' => 2024,
            'mes' => 1,
        ];

        $resumen = [
            'contribuyente' => 'Empresa 1',
            'anio' => 2024,
            'mes' => 'Enero',
            'total_pedimentos' => 10,
            'volumen_total' => 100000,
            'valor_total' => 500000,
            'por_producto' => [
                ['producto' => 'Gasolina', 'volumen' => 60000, 'valor' => 300000],
                ['producto' => 'Diesel', 'volumen' => 40000, 'valor' => 200000],
            ],
        ];

        $this->mockSuccessfulResponse('/api/pedimentos/resumen-comercio-exterior', $resumen);

        $response = $this->get('/pedimentos/resumen/comercio-exterior?'.http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('pedimentos.resumen');
        $response->assertViewHas('resumen', $resumen);
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $contribuyentes = [
            ['id' => 1, 'razon_social' => 'Empresa 1'],
            ['id' => 2, 'razon_social' => 'Empresa 2'],
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina', 'clave_sat' => '15101501'],
            ['id' => 2, 'nombre' => 'Diesel', 'clave_sat' => '15101502'],
        ];

        $this->mockSuccessfulResponse('/api/catalogo/contribuyentes', $contribuyentes);
        $this->mockSuccessfulResponse('/api/catalogo/productos', $productos);

        $response = $this->get('/pedimentos/create');

        $response->assertStatus(200);
        $response->assertViewIs('pedimentos.create');
        $response->assertViewHas('contribuyentes', $contribuyentes);
        $response->assertViewHas('productos', $productos);
    }

    /** @test */
    public function test_store_creates_pedimento_successfully()
    {
        $pedimentoData = [
            'numero_pedimento' => 'PED-001',
            'contribuyente_id' => 1,
            'producto_id' => 1,
            'pais_destino' => 'MEX',
            'pais_origen' => 'USA',
            'medio_transporte_entrada' => 'Barco',
            'incoterms' => 'FOB',
            'volumen' => 10000,
            'unidad_medida' => 'L',
            'valor_comercial' => 50000,
            'moneda' => 'USD',
            'fecha_pedimento' => '2024-01-15',
            'estado' => 'ACTIVO',
        ];

        $createdPedimento = array_merge($pedimentoData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/pedimentos', $createdPedimento, 'Pedimento creado exitosamente', 201);

        $response = $this->post('/pedimentos', $pedimentoData);

        $response->assertRedirect('/pedimentos');
        $response->assertSessionHas('success', 'Pedimento creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'numero_pedimento' => '',
            'estado' => 'INVALIDO',
        ];

        $this->mockValidationErrorResponse('/api/pedimentos', [
            'numero_pedimento' => ['El campo numero_pedimento es obligatorio'],
            'estado' => ['El campo estado debe ser uno de: ACTIVO, UTILIZADO, CANCELADO'],
        ]);

        $response = $this->post('/pedimentos', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['numero_pedimento', 'estado']);
    }

    /** @test */
    public function test_update_modifies_pedimento_successfully()
    {
        $updateData = [
            'fecha_arribo' => '2024-01-20',
            'fecha_pago' => '2024-01-25',
        ];

        $this->mockSuccessfulResponse('/api/pedimentos/1', [], 'Pedimento actualizado exitosamente');

        $response = $this->put('/pedimentos/1', $updateData);

        $response->assertRedirect('/pedimentos/1');
        $response->assertSessionHas('success', 'Pedimento actualizado exitosamente');
    }

    /** @test */
    public function test_cancelar_cancels_pedimento_successfully()
    {
        $cancelData = [
            'motivo_cancelacion' => 'Error en el pedimento',
        ];

        $this->mockSuccessfulResponse('/api/pedimentos/1/cancelar', [], 'Pedimento cancelado exitosamente');

        $response = $this->post('/pedimentos/1/cancelar', $cancelData);

        $response->assertRedirect('/pedimentos/1');
        $response->assertSessionHas('success', 'Pedimento cancelado exitosamente');
    }

    /** @test */
    public function test_cancelar_fails_if_already_cancelled()
    {
        $cancelData = [
            'motivo_cancelacion' => 'Error en el pedimento',
        ];

        $this->mockErrorResponse('/api/pedimentos/1/cancelar', 'El pedimento ya está cancelado', 403);

        $response = $this->post('/pedimentos/1/cancelar', $cancelData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El pedimento ya está cancelado');
    }

    /** @test */
    public function test_marcar_utilizado_marks_pedimento_successfully()
    {
        $utilizarData = [
            'registro_volumetrico_id' => 1,
        ];

        $this->mockSuccessfulResponse('/api/pedimentos/1/utilizado', [], 'Pedimento marcado como utilizado exitosamente');

        $response = $this->post('/pedimentos/1/utilizado', $utilizarData);

        $response->assertRedirect('/pedimentos/1');
        $response->assertSessionHas('success', 'Pedimento marcado como utilizado exitosamente');
    }

    /** @test */
    public function test_marcar_utilizado_fails_if_not_active()
    {
        $utilizarData = [
            'registro_volumetrico_id' => 1,
        ];

        $this->mockErrorResponse('/api/pedimentos/1/utilizado', 'El pedimento no está en estado ACTIVO', 403);

        $response = $this->post('/pedimentos/1/utilizado', $utilizarData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El pedimento no está en estado ACTIVO');
    }

    /** @test */
    public function test_destroy_deletes_pedimento_successfully()
    {
        $this->mockSuccessfulResponse('/api/pedimentos/1', [], 'Pedimento eliminado exitosamente');

        $response = $this->delete('/pedimentos/1');

        $response->assertRedirect('/pedimentos');
        $response->assertSessionHas('success', 'Pedimento eliminado exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_pedimento_in_use()
    {
        $this->mockErrorResponse('/api/pedimentos/1', 'No se puede eliminar el pedimento', 403);

        $response = $this->delete('/pedimentos/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar el pedimento');
    }
}