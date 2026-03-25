<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DictamenTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_dictamenes_list()
    {
        $dictamenes = [
            [
                'id' => 1,
                'folio' => 'DICT-001',
                'numero_lote' => 'LOTE-001',
                'contribuyente' => ['razon_social' => 'Empresa Prueba'],
                'producto' => ['nombre' => 'Gasolina'],
                'fecha_emision' => '2024-01-15',
                'estado' => 'VIGENTE'
            ],
            [
                'id' => 2,
                'folio' => 'DICT-002',
                'numero_lote' => 'LOTE-002',
                'contribuyente' => ['razon_social' => 'Empresa Test'],
                'producto' => ['nombre' => 'Diesel'],
                'fecha_emision' => '2024-01-20',
                'estado' => 'VIGENTE'
            ]
        ];

        $this->mockPaginatedResponse('/api/dictamenes', $dictamenes, 2);

        $response = $this->get('/dictamenes');

        $response->assertStatus(200);
        $response->assertViewIs('dictamenes.index');
        $response->assertViewHas('dictamenes');
    }

    /** @test */
    public function test_create_form_displays_correctly_with_catalogs()
    {
        $contribuyentes = [
            ['id' => 1, 'razon_social' => 'Empresa Prueba'],
            ['id' => 2, 'razon_social' => 'Empresa Test']
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2']
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina'],
            ['id' => 2, 'nombre' => 'Diesel']
        ];

        $this->mockSuccessfulResponse('/api/catalogo/contribuyentes', $contribuyentes);
        $this->mockSuccessfulResponse('/api/instalaciones?activo=true', $instalaciones);
        $this->mockSuccessfulResponse('/api/catalogo/productos', $productos);

        $response = $this->get('/dictamenes/create');

        $response->assertStatus(200);
        $response->assertViewIs('dictamenes.create');
        $response->assertViewHas('contribuyentes', $contribuyentes);
        $response->assertViewHas('instalaciones', $instalaciones);
        $response->assertViewHas('productos', $productos);
    }

    /** @test */
    public function test_store_creates_dictamen_successfully()
    {
        $dictamenData = [
            'folio' => 'DICT-001',
            'numero_lote' => 'LOTE-001',
            'contribuyente_id' => 1,
            'laboratorio_rfc' => 'LAB123456XXX',
            'laboratorio_nombre' => 'Laboratorio Test',
            'laboratorio_numero_acreditacion' => 'ACR-001',
            'fecha_emision' => '2024-01-15',
            'fecha_toma_muestra' => '2024-01-10',
            'fecha_pruebas' => '2024-01-12',
            'fecha_resultados' => '2024-01-14',
            'producto_id' => 1,
            'volumen_muestra' => 100,
            'unidad_medida_muestra' => 'L',
            'metodo_muestreo' => 'Método estándar',
            'metodo_ensayo' => 'Método de prueba',
            'estado' => 'VIGENTE'
        ];

        $createdDictamen = array_merge($dictamenData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/dictamenes', $createdDictamen, 'Dictamen creado exitosamente', 201);

        $response = $this->post('/dictamenes', $dictamenData);

        $response->assertRedirect('/dictamenes/1');
        $response->assertSessionHas('success', 'Dictamen creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'folio' => '',
            'estado' => 'INVALIDO'
        ];

        $this->mockValidationErrorResponse('/api/dictamenes', [
            'folio' => ['El campo folio es obligatorio'],
            'estado' => ['El campo estado debe ser uno de: VIGENTE, CADUCADO, CANCELADO']
        ]);

        $response = $this->post('/dictamenes', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['folio', 'estado']);
    }

    /** @test */
    public function test_show_displays_dictamen_details()
    {
        $dictamen = [
            'id' => 1,
            'folio' => 'DICT-001',
            'numero_lote' => 'LOTE-001',
            'contribuyente' => [
                'id' => 1,
                'razon_social' => 'Empresa Prueba',
                'rfc' => 'XAXX010101XXX'
            ],
            'laboratorio' => [
                'rfc' => 'LAB123456XXX',
                'nombre' => 'Laboratorio Test',
                'numero_acreditacion' => 'ACR-001'
            ],
            'producto' => [
                'id' => 1,
                'nombre' => 'Gasolina',
                'clave_sat' => '15101501'
            ],
            'fecha_emision' => '2024-01-15',
            'resultados' => [
                'densidad' => 0.75,
                'viscosidad' => 0.5,
                'azufre' => 10,
                'octanaje' => 95
            ],
            'estado' => 'VIGENTE'
        ];

        $this->mockSuccessfulResponse('/api/dictamenes/1', $dictamen);

        $response = $this->get('/dictamenes/1');

        $response->assertStatus(200);
        $response->assertViewIs('dictamenes.show');
        $response->assertViewHas('dictamen', $dictamen);
    }

    /** @test */
    public function test_cancelar_cancels_dictamen_successfully()
    {
        $cancelData = [
            'motivo_cancelacion' => 'Error en el dictamen'
        ];

        $this->mockSuccessfulResponse('/api/dictamenes/1/cancelar', [], 'Dictamen cancelado exitosamente');

        $response = $this->post('/dictamenes/1/cancelar', $cancelData);

        $response->assertRedirect('/dictamenes/1');
        $response->assertSessionHas('success', 'Dictamen cancelado exitosamente');
    }

    /** @test */
    public function test_cancelar_fails_if_dictamen_already_cancelled()
    {
        $cancelData = [
            'motivo_cancelacion' => 'Error en el dictamen'
        ];

        $this->mockErrorResponse('/api/dictamenes/1/cancelar', 'El dictamen ya está cancelado', 403);

        $response = $this->post('/dictamenes/1/cancelar', $cancelData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El dictamen ya está cancelado');
    }

    /** @test */
    public function test_verificar_vigencia_checks_dictamen_validity()
    {
        $resultado = [
            'dictamen_id' => 1,
            'folio' => 'DICT-001',
            'fecha_emision' => '2024-01-15',
            'vigente' => true,
            'dias_restantes' => 180,
            'fecha_caducidad' => '2025-01-15',
            'mensaje' => 'El dictamen se encuentra vigente'
        ];

        $this->mockSuccessfulResponse('/api/dictamenes/1/verificar-vigencia', $resultado);

        $response = $this->get('/dictamenes/1/verificar-vigencia');

        $response->assertStatus(200);
        $response->assertViewIs('dictamenes.vigencia');
        $response->assertViewHas('resultado', $resultado);
    }

    /** @test */
    public function test_estadisticas_displays_dictamen_statistics()
    {
        $filters = [
            'contribuyente_id' => 1,
            'anio' => 2024
        ];

        $estadisticas = [
            'contribuyente' => 'Empresa Prueba',
            'anio' => 2024,
            'total_dictamenes' => 12,
            'por_estado' => [
                'VIGENTE' => 10,
                'CADUCADO' => 1,
                'CANCELADO' => 1
            ],
            'por_producto' => [
                ['producto' => 'Gasolina', 'cantidad' => 8],
                ['producto' => 'Diesel', 'cantidad' => 4]
            ],
            'tendencia_mensual' => [
                'Enero' => 1,
                'Febrero' => 2,
                'Marzo' => 1
            ]
        ];

        $this->mockSuccessfulResponse('/api/dictamenes/estadisticas', $estadisticas);

        $response = $this->get('/dictamenes/estadisticas?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('dictamenes.estadisticas');
        $response->assertViewHas('estadisticas', $estadisticas);
    }

    /** @test */
    public function test_por_producto_displays_dictamenes_by_product()
    {
        $productoId = 1;
        $resultado = [
            'producto' => [
                'id' => 1,
                'nombre' => 'Gasolina',
                'clave_sat' => '15101501'
            ],
            'total_dictamenes' => 5,
            'dictamenes' => [
                [
                    'id' => 1,
                    'folio' => 'DICT-001',
                    'fecha_emision' => '2024-01-15',
                    'estado' => 'VIGENTE',
                    'contribuyente' => 'Empresa Prueba'
                ],
                [
                    'id' => 2,
                    'folio' => 'DICT-002',
                    'fecha_emision' => '2024-02-10',
                    'estado' => 'VIGENTE',
                    'contribuyente' => 'Empresa Test'
                ]
            ]
        ];

        $this->mockSuccessfulResponse("/api/dictamenes/producto/{$productoId}", $resultado);

        $response = $this->get("/dictamenes/producto/{$productoId}");

        $response->assertStatus(200);
        $response->assertViewIs('dictamenes.por-producto');
        $response->assertViewHas('resultado', $resultado);
    }

    /** @test */
    public function test_update_modifies_dictamen_successfully()
    {
        $updateData = [
            'observaciones' => 'Se actualizó la información del dictamen',
            'estado' => 'CADUCADO'
        ];

        $this->mockSuccessfulResponse('/api/dictamenes/1', [], 'Dictamen actualizado exitosamente');

        $response = $this->put('/dictamenes/1', $updateData);

        $response->assertRedirect('/dictamenes/1');
        $response->assertSessionHas('success', 'Dictamen actualizado exitosamente');
    }

    /** @test */
    public function test_exportar_downloads_dictamenes_file()
    {
        $filters = [
            'contribuyente_id' => 1,
            'fecha_emision_inicio' => '2024-01-01',
            'fecha_emision_fin' => '2024-12-31'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/dictamenes/exportar*' => Http::response(
                "folio,numero_lote,contribuyente,producto,fecha_emision,estado\nDICT-001,LOTE-001,Empresa Prueba,Gasolina,2024-01-15,VIGENTE",
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="dictamenes.csv"'
                ]
            )
        ]);

        $response = $this->get('/dictamenes/exportar?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="dictamenes.csv"');
    }
}