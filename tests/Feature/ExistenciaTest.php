<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExistenciaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_existencias_list()
    {
        $existencias = [
            [
                'id' => 1,
                'numero_registro' => 'EX-001',
                'tanque' => ['identificador' => 'TAN-001'],
                'producto' => ['nombre' => 'Gasolina'],
                'fecha' => '2024-01-15',
                'volumen_medido' => 5000,
                'estado' => 'VALIDADO',
            ],
            [
                'id' => 2,
                'numero_registro' => 'EX-002',
                'tanque' => ['identificador' => 'TAN-002'],
                'producto' => ['nombre' => 'Diesel'],
                'fecha' => '2024-01-16',
                'volumen_medido' => 3000,
                'estado' => 'PENDIENTE',
            ],
        ];

        $this->mockPaginatedResponse('/api/existencias', $existencias, 2);

        $response = $this->get('/existencias');

        $response->assertStatus(200);
        $response->assertViewIs('existencias.index');
        $response->assertViewHas('existencias');
    }

    /** @test */
    public function test_show_displays_existencia_details()
    {
        $existencia = [
            'id' => 1,
            'numero_registro' => 'EX-001',
            'tanque' => ['id' => 1, 'identificador' => 'TAN-001'],
            'producto' => ['id' => 1, 'nombre' => 'Gasolina'],
            'fecha' => '2024-01-15',
            'hora' => '10:00:00',
            'volumen_medido' => 5000,
            'estado' => 'VALIDADO',
        ];

        $this->mockSuccessfulResponse('/api/existencias/1', $existencia);

        $response = $this->get('/existencias/1');

        $response->assertStatus(200);
        $response->assertViewIs('existencias.show');
        $response->assertViewHas('existencia', $existencia);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $existencia = [
            'id' => 1,
            'numero_registro' => 'EX-001',
            'tanque_id' => 1,
            'producto_id' => 1,
            'fecha' => '2024-01-15',
            'hora' => '10:00:00',
            'volumen_medido' => 5000,
            'estado' => 'VALIDADO',
        ];

        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001'],
            ['id' => 2, 'identificador' => 'TAN-002'],
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina'],
            ['id' => 2, 'nombre' => 'Diesel'],
        ];

        $this->mockSuccessfulResponse('/api/existencias/1', $existencia);
        $this->mockSuccessfulResponse('/api/tanques', $tanques);
        $this->mockSuccessfulResponse('/api/productos', $productos);

        $response = $this->get('/existencias/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('existencias.edit');
        $response->assertViewHas('existencia', $existencia);
        $response->assertViewHas('tanques', $tanques);
        $response->assertViewHas('productos', $productos);
    }

    /** @test */
    public function test_filter_existencias_by_estado()
    {
        $existencias = [
            ['id' => 1, 'numero_registro' => 'EX-001', 'tanque' => ['identificador' => 'TAN-001'], 'producto' => ['nombre' => 'Gasolina'], 'fecha' => '2024-01-15', 'volumen_medido' => 5000, 'estado' => 'VALIDADO'],
        ];

        $this->mockSuccessfulResponse('/api/existencias', ['data' => $existencias]);

        $response = $this->get('/existencias?estado=VALIDADO');

        $response->assertStatus(200);
        $response->assertViewHas('existencias');

        $existencias = $response->viewData('existencias');
        $this->assertCount(1, $existencias);
        $this->assertEquals('VALIDADO', $existencias[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_existencias_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/existencias/exportar*' => Http::response(
                'numero_registro,fecha,volumen_medido,estado\nEX-001,2024-01-15,5000,VALIDADO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="existencias.csv"',
                ]
            ),
        ]);

        $response = $this->get('/existencias/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_inventario_actual_displays_current_inventory()
    {
        $tanqueId = 1;
        $inventario = [
            'tanque_id' => 1,
            'tanque_identificador' => 'TAN-001',
            'producto' => 'Gasolina',
            'volumen_actual' => 5000,
            'volumen_disponible' => 4990,
            'volumen_agua' => 5,
            'volumen_sedimentos' => 5,
            'ultima_actualizacion' => '2024-01-15 10:00:00',
        ];

        $this->mockSuccessfulResponse("/api/existencias/inventario-actual/{$tanqueId}", $inventario);

        $response = $this->get("/existencias/inventario-actual/{$tanqueId}");

        $response->assertStatus(200);
        $response->assertViewIs('existencias.inventario-actual');
        $response->assertViewHas('inventario', $inventario);
    }

    /** @test */
    public function test_reporte_mermas_displays_loss_report()
    {
        $filters = [
            'instalacion_id' => 1,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31',
        ];

        $mermas = [
            'instalacion' => 'Instalación 1',
            'periodo' => 'Enero 2024',
            'total_mermas' => 150,
            'por_producto' => [
                ['producto' => 'Gasolina', 'merma' => 100],
                ['producto' => 'Diesel', 'merma' => 50],
            ],
        ];

        $this->mockSuccessfulResponse('/api/existencias/reporte-mermas', $mermas);

        $response = $this->get('/existencias/reporte-mermas?'.http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('existencias.reporte-mermas');
        $response->assertViewHas('mermas', $mermas);
    }

    /** @test */
    public function test_por_fecha_displays_records_by_date()
    {
        $fecha = '2024-01-15';

        $existencias = [
            ['id' => 1, 'numero_registro' => 'EX-001', 'fecha' => $fecha, 'volumen_medido' => 5000],
            ['id' => 2, 'numero_registro' => 'EX-002', 'fecha' => $fecha, 'volumen_medido' => 3000],
        ];

        $this->mockSuccessfulResponse('/api/existencias/por-fecha', $existencias);

        $response = $this->get("/existencias/por-fecha?fecha={$fecha}");

        $response->assertStatus(200);
        $response->assertViewIs('existencias.por-fecha');
        $response->assertViewHas('existencias', $existencias);
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001', 'instalacion' => ['nombre' => 'Inst 1'], 'producto' => ['nombre' => 'Gasolina']],
            ['id' => 2, 'identificador' => 'TAN-002', 'instalacion' => ['nombre' => 'Inst 2'], 'producto' => ['nombre' => 'Diesel']],
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina', 'clave_sat' => '15101501'],
            ['id' => 2, 'nombre' => 'Diesel', 'clave_sat' => '15101502'],
        ];

        $this->mockSuccessfulResponse('/api/tanques', $tanques);
        $this->mockSuccessfulResponse('/api/productos', $productos);

        $response = $this->get('/existencias/create');

        $response->assertStatus(200);
        $response->assertViewIs('existencias.create');
        $response->assertViewHas('tanques');
        $response->assertViewHas('productos');
    }

    /** @test */
    public function test_store_creates_existencia_successfully()
    {
        $existenciaData = [
            'numero_registro' => 'EX-001',
            'tanque_id' => 1,
            'producto_id' => 1,
            'fecha' => '2024-01-15',
            'hora' => '10:00:00',
            'volumen_medido' => 5000,
            'temperatura' => 20.5,
            'densidad' => 0.75,
            'volumen_corregido' => 4995,
            'volumen_disponible' => 4990,
            'volumen_agua' => 5,
            'volumen_sedimentos' => 0,
            'tipo_registro' => 'operacion',
            'tipo_movimiento' => 'RECEPCION',
            'estado' => 'PENDIENTE',
        ];

        $createdExistencia = array_merge($existenciaData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/existencias', $createdExistencia, 'Existencia creada exitosamente', 201);

        $response = $this->post('/existencias', $existenciaData);

        $response->assertRedirect('/existencias/1');
        $response->assertSessionHas('success', 'Existencia creada exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'numero_registro' => '',
            'tipo_movimiento' => 'INVALIDO',
        ];

        $this->mockValidationErrorResponse('/api/existencias', [
            'numero_registro' => ['El campo numero_registro es obligatorio'],
            'tipo_movimiento' => ['El campo tipo_movimiento debe ser uno de: INICIAL, RECEPCION, ENTREGA, VENTA, TRASPASO, AJUSTE, INVENTARIO'],
        ]);

        $response = $this->post('/existencias', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['numero_registro', 'tipo_movimiento']);
    }

    /** @test */
    public function test_validar_validates_existencia_successfully()
    {
        $validateData = [
            'observaciones_validacion' => 'Existencia validada correctamente',
        ];

        $this->mockSuccessfulResponse('/api/existencias/1/validar', [], 'Existencia validada exitosamente');

        $response = $this->post('/existencias/1/validar', $validateData);

        $response->assertRedirect('/existencias/1');
        $response->assertSessionHas('success', 'Existencia validada exitosamente');
    }

    /** @test */
    public function test_validar_fails_if_already_validated()
    {
        $validateData = [
            'observaciones_validacion' => 'Intento de validar nuevamente',
        ];

        $this->mockErrorResponse('/api/existencias/1/validar', 'La existencia ya está validada', 403);

        $response = $this->post('/existencias/1/validar', $validateData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'La existencia ya está validada');
    }

    /** @test */
    public function test_historico_displays_historical_records()
    {
        $tanqueId = 1;
        $filters = [
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31',
        ];

        $historico = [
            'tanque_id' => 1,
            'registros' => [
                ['fecha' => '2024-01-15', 'volumen_medido' => 5000, 'tipo_movimiento' => 'RECEPCION'],
                ['fecha' => '2024-01-16', 'volumen_medido' => 4500, 'tipo_movimiento' => 'ENTREGA'],
            ],
        ];

        $this->mockSuccessfulResponse("/api/existencias/historico/{$tanqueId}", $historico);

        $response = $this->get("/existencias/historico/{$tanqueId}?".http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('existencias.historico');
        $response->assertViewHas('historico', $historico);
    }
}