<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RegistroVolumetricoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_registros_list()
    {
        $registros = [
            [
                'id' => 1,
                'numero_registro' => 'RV-001',
                'instalacion' => ['nombre' => 'Instalación 1'],
                'producto' => ['nombre' => 'Gasolina'],
                'fecha' => '2024-01-15',
                'operacion' => 'recepcion',
                'volumen_operacion' => 5000,
                'estado' => 'PROCESADO'
            ],
            [
                'id' => 2,
                'numero_registro' => 'RV-002',
                'instalacion' => ['nombre' => 'Instalación 2'],
                'producto' => ['nombre' => 'Diesel'],
                'fecha' => '2024-01-16',
                'operacion' => 'entrega',
                'volumen_operacion' => 3000,
                'estado' => 'VALIDADO'
            ]
        ];

        $this->mockPaginatedResponse('/api/registros-volumetricos', $registros, 2);

        $response = $this->get('/registros-volumetricos');

        $response->assertStatus(200);
        $response->assertViewIs('registros-volumetricos.index');
        $response->assertViewHas('registros');
    }

    /** @test */
    public function test_create_form_displays_correctly_with_catalogs()
    {
        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2']
        ];

        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001'],
            ['id' => 2, 'identificador' => 'TAN-002']
        ];

        $medidores = [
            ['id' => 1, 'numero_serie' => 'MED-001'],
            ['id' => 2, 'numero_serie' => 'MED-002']
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina'],
            ['id' => 2, 'nombre' => 'Diesel']
        ];

        $usuarios = [
            ['id' => 1, 'name' => 'Juan Pérez'],
            ['id' => 2, 'name' => 'María López']
        ];

        $this->mockSuccessfulResponse('/api/instalaciones?activo=true', $instalaciones);
        $this->mockSuccessfulResponse('/api/tanques?activo=true', $tanques);
        $this->mockSuccessfulResponse('/api/medidores?activo=true', $medidores);
        $this->mockSuccessfulResponse('/api/productos?activo=true', $productos);
        $this->mockSuccessfulResponse('/api/users', $usuarios);

        $response = $this->get('/registros-volumetricos/create');

        $response->assertStatus(200);
        $response->assertViewIs('registros-volumetricos.create');
        $response->assertViewHas('instalaciones', $instalaciones);
        $response->assertViewHas('tanques', $tanques);
        $response->assertViewHas('medidores', $medidores);
        $response->assertViewHas('productos', $productos);
        $response->assertViewHas('usuarios', $usuarios);
    }

    /** @test */
    public function test_store_creates_registro_successfully()
    {
        $registroData = [
            'numero_registro' => 'RV-001',
            'instalacion_id' => 1,
            'tanque_id' => 1,
            'producto_id' => 1,
            'fecha' => '2024-01-15',
            'hora_inicio' => '08:00:00',
            'hora_fin' => '17:00:00',
            'volumen_inicial' => 10000,
            'volumen_final' => 5000,
            'volumen_operacion' => 5000,
            'temperatura_inicial' => 20.5,
            'temperatura_final' => 21.0,
            'densidad' => 0.75,
            'volumen_corregido' => 4995,
            'factor_correccion' => 0.999,
            'tipo_registro' => 'operacion',
            'operacion' => 'recepcion',
            'estado' => 'PENDIENTE',
            'usuario_registro_id' => 1,
            'observaciones' => 'Recepción de combustible'
        ];

        $createdRegistro = array_merge($registroData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/registros-volumetricos', $createdRegistro, 'Registro volumétrico creado exitosamente', 201);

        $response = $this->post('/registros-volumetricos', $registroData);

        $response->assertRedirect('/registros-volumetricos/1');
        $response->assertSessionHas('success', 'Registro volumétrico creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'hora_fin' => '07:00:00', // Menor que hora_inicio
            'operacion' => 'INVALIDO',
            'tipo_registro' => 'INVALIDO'
        ];

        $this->mockValidationErrorResponse('/api/registros-volumetricos', [
            'hora_fin' => ['La hora fin debe ser posterior a hora inicio'],
            'operacion' => ['El campo operacion debe ser uno de: recepcion, entrega, inventario_inicial, inventario_final, venta'],
            'tipo_registro' => ['El campo tipo registro debe ser uno de: operacion, acumulado, existencias']
        ]);

        $response = $this->post('/registros-volumetricos', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['hora_fin', 'operacion', 'tipo_registro']);
    }

    /** @test */
    public function test_show_displays_registro_details()
    {
        $registro = [
            'id' => 1,
            'numero_registro' => 'RV-001',
            'instalacion' => ['id' => 1, 'nombre' => 'Instalación 1'],
            'tanque' => ['id' => 1, 'identificador' => 'TAN-001'],
            'producto' => ['id' => 1, 'nombre' => 'Gasolina'],
            'fecha' => '2024-01-15',
            'hora_inicio' => '08:00:00',
            'hora_fin' => '17:00:00',
            'volumen_inicial' => 10000,
            'volumen_final' => 5000,
            'volumen_operacion' => 5000,
            'temperatura_inicial' => 20.5,
            'temperatura_final' => 21.0,
            'densidad' => 0.75,
            'volumen_corregido' => 4995,
            'operacion' => 'recepcion',
            'estado' => 'PROCESADO',
            'usuario_registro' => ['id' => 1, 'name' => 'Juan Pérez'],
            'documento_fiscal_uuid' => '12345678-1234-1234-1234-123456789012'
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos/1', $registro);

        $response = $this->get('/registros-volumetricos/1');

        $response->assertStatus(200);
        $response->assertViewIs('registros-volumetricos.show');
        $response->assertViewHas('registro', $registro);
    }

    /** @test */
    public function test_validar_validates_registro_successfully()
    {
        $validateData = [
            'observaciones_validacion' => 'Registro validado correctamente'
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos/1/validar', [], 'Registro validado exitosamente');

        $response = $this->post('/registros-volumetricos/1/validar', $validateData);

        $response->assertRedirect('/registros-volumetricos/1');
        $response->assertSessionHas('success', 'Registro validado exitosamente');
    }

    /** @test */
    public function test_validar_fails_if_already_validated()
    {
        $validateData = [
            'observaciones_validacion' => 'Intento de validar nuevamente'
        ];

        $this->mockErrorResponse('/api/registros-volumetricos/1/validar', 'El registro ya está validado', 403);

        $response = $this->post('/registros-volumetricos/1/validar', $validateData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El registro ya está validado');
    }

    /** @test */
    public function test_cancelar_cancels_registro_successfully()
    {
        $cancelData = [
            'motivo_cancelacion' => 'Error en el registro'
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos/1/cancelar', [], 'Registro cancelado exitosamente');

        $response = $this->post('/registros-volumetricos/1/cancelar', $cancelData);

        $response->assertRedirect('/registros-volumetricos/1');
        $response->assertSessionHas('success', 'Registro cancelado exitosamente');
    }

    /** @test */
    public function test_resumen_diario_displays_daily_summary()
    {
        $filters = [
            'instalacion_id' => 1,
            'fecha' => '2024-01-15'
        ];

        $resumen = [
            'instalacion' => 'Instalación 1',
            'fecha' => '2024-01-15',
            'recepciones' => [
                'total_volumen' => 10000,
                'registros' => [
                    ['producto' => 'Gasolina', 'volumen' => 6000],
                    ['producto' => 'Diesel', 'volumen' => 4000]
                ]
            ],
            'entregas' => [
                'total_volumen' => 5000,
                'registros' => [
                    ['producto' => 'Gasolina', 'volumen' => 3000],
                    ['producto' => 'Diesel', 'volumen' => 2000]
                ]
            ],
            'inventario_final' => [
                'total_volumen' => 5000,
                'por_producto' => [
                    ['producto' => 'Gasolina', 'volumen' => 3000],
                    ['producto' => 'Diesel', 'volumen' => 2000]
                ]
            ],
            'mermas' => 150
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos/resumen-diario', $resumen);

        $response = $this->get('/registros-volumetricos/resumen/diario?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('registros-volumetricos.resumen-diario');
        $response->assertViewHas('resumen', $resumen);
    }

    /** @test */
    public function test_estadisticas_mensuales_displays_monthly_statistics()
    {
        $filters = [
            'instalacion_id' => 1,
            'anio' => 2024,
            'mes' => 1
        ];

        $estadisticas = [
            'instalacion' => 'Instalación 1',
            'periodo' => 'Enero 2024',
            'resumen' => [
                'total_recepciones' => 50000,
                'total_entregas' => 45000,
                'mermas_totales' => 500,
                'volumen_promedio_diario' => 1612.90
            ],
            'tendencia_diaria' => [
                ['dia' => 1, 'recepciones' => 2000, 'entregas' => 1800],
                ['dia' => 2, 'recepciones' => 1500, 'entregas' => 1400],
                ['dia' => 3, 'recepciones' => 2500, 'entregas' => 2300]
            ],
            'por_producto' => [
                ['producto' => 'Gasolina', 'recepciones' => 30000, 'entregas' => 28000],
                ['producto' => 'Diesel', 'recepciones' => 20000, 'entregas' => 17000]
            ]
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos/estadisticas-mensuales', $estadisticas);

        $response = $this->get('/registros-volumetricos/estadisticas/mensuales?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('registros-volumetricos.estadisticas');
        $response->assertViewHas('estadisticas', $estadisticas);
    }

    /** @test */
    public function test_asociar_dictamen_associates_dictamen_to_registro()
    {
        $asociarData = [
            'dictamen_id' => 1
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos/1/asociar-dictamen', [], 'Dictamen asociado exitosamente');

        $response = $this->post('/registros-volumetricos/1/asociar-dictamen', $asociarData);

        $response->assertRedirect('/registros-volumetricos/1');
        $response->assertSessionHas('success', 'Dictamen asociado exitosamente');
    }

    /** @test */
    public function test_filter_registros_by_operacion()
    {
        $registros = [
            ['id' => 1, 'operacion' => 'recepcion', 'volumen_operacion' => 5000]
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos?operacion=recepcion', ['data' => $registros]);

        $response = $this->get('/registros-volumetricos?operacion=recepcion');

        $response->assertStatus(200);
        $response->assertViewHas('registros');
        
        $registros = $response->viewData('registros');
        $this->assertCount(1, $registros);
        $this->assertEquals('recepcion', $registros[0]['operacion']);
    }

    /** @test */
    public function test_filter_registros_by_estado()
    {
        $registros = [
            ['id' => 1, 'estado' => 'PROCESADO', 'numero_registro' => 'RV-001']
        ];

        $this->mockSuccessfulResponse('/api/registros-volumetricos?estado=PROCESADO', ['data' => $registros]);

        $response = $this->get('/registros-volumetricos?estado=PROCESADO');

        $response->assertStatus(200);
        $response->assertViewHas('registros');
        
        $registros = $response->viewData('registros');
        $this->assertCount(1, $registros);
        $this->assertEquals('PROCESADO', $registros[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_registros_file()
    {
        $filters = [
            'instalacion_id' => 1,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/registros-volumetricos/exportar*' => Http::response(
                "numero_registro,fecha,operacion,volumen,estado\nRV-001,2024-01-15,recepcion,5000,PROCESADO",
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="registros_volumetricos.csv"'
                ]
            )
        ]);

        $response = $this->get('/registros-volumetricos/exportar?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="registros_volumetricos.csv"');
    }
}