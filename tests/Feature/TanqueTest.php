<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TanqueTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_tanques_list()
    {
        $tanques = [
            [
                'id' => 1,
                'identificador' => 'TAN-001',
                'capacidad_total' => 10000,
                'estado' => 'OPERATIVO',
                'instalacion' => ['nombre' => 'Instalación 1'],
            ],
            [
                'id' => 2,
                'identificador' => 'TAN-002',
                'capacidad_total' => 5000,
                'estado' => 'MANTENIMIENTO',
                'instalacion' => ['nombre' => 'Instalación 2'],
            ],
        ];

        $this->mockPaginatedResponse('/api/tanques', $tanques, 2);

        $response = $this->get('/tanques');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.index');
        $response->assertViewHas('tanques');
    }

    /** @test */
    public function test_show_displays_tanque_details()
    {
        $tanque = [
            'id' => 1,
            'identificador' => 'TAN-001',
            'capacidad_total' => 10000,
            'estado' => 'OPERATIVO',
            'instalacion' => ['id' => 1, 'nombre' => 'Instalación 1'],
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);

        $response = $this->get('/tanques/1');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.show');
        $response->assertViewHas('tanque', $tanque);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $tanque = [
            'id' => 1,
            'identificador' => 'TAN-001',
            'capacidad_total' => 10000,
            'estado' => 'OPERATIVO',
            'instalacion_id' => 1,
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2'],
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/tanques/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.edit');
        $response->assertViewHas('tanque', $tanque);
        $response->assertViewHas('instalaciones', $instalaciones);
    }

    /** @test */
    public function test_filter_tanques_by_estado()
    {
        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001', 'capacidad_total' => 10000, 'estado' => 'OPERATIVO', 'instalacion' => ['nombre' => 'Inst 1']],
        ];

        $this->mockSuccessfulResponse('/api/tanques', ['data' => $tanques]);

        $response = $this->get('/tanques?estado=OPERATIVO');

        $response->assertStatus(200);
        $response->assertViewHas('tanques');

        $tanques = $response->viewData('tanques');
        $this->assertCount(1, $tanques);
        $this->assertEquals('OPERATIVO', $tanques[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_tanques_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/tanques/exportar*' => Http::response(
                'identificador,capacidad_total,estado\nTAN-001,10000,OPERATIVO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="tanques.csv"',
                ]
            ),
        ]);

        $response = $this->get('/tanques/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_show_displays_tanque_details()
    {
        $tanque = [
            'id' => 1,
            'identificador' => 'TAN-001',
            'capacidad_total' => 10000,
            'estado' => 'OPERATIVO',
            'instalacion' => ['id' => 1, 'nombre' => 'Instalación 1'],
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);

        $response = $this->get('/tanques/1');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.show');
        $response->assertViewHas('tanque', $tanque);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $tanque = [
            'id' => 1,
            'identificador' => 'TAN-001',
            'capacidad_total' => 10000,
            'estado' => 'OPERATIVO',
            'instalacion_id' => 1,
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2'],
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/tanques/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.edit');
        $response->assertViewHas('tanque', $tanque);
        $response->assertViewHas('instalaciones', $instalaciones);
    }

    /** @test */
    public function test_filter_tanques_by_estado()
    {
        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001', 'capacidad_total' => 10000, 'estado' => 'OPERATIVO', 'instalacion' => ['nombre' => 'Inst 1']],
        ];

        $this->mockSuccessfulResponse('/api/tanques', ['data' => $tanques]);

        $response = $this->get('/tanques?estado=OPERATIVO');

        $response->assertStatus(200);
        $response->assertViewHas('tanques');

        $tanques = $response->viewData('tanques');
        $this->assertCount(1, $tanques);
        $this->assertEquals('OPERATIVO', $tanques[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_tanques_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/tanques/exportar*' => Http::response(
                'identificador,capacidad_total,estado\nTAN-001,10000,OPERATIVO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="tanques.csv"',
                ]
            ),
        ]);

        $response = $this->get('/tanques/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_show_displays_tanque_details()
    {
        $tanque = [
            'id' => 1,
            'identificador' => 'TAN-001',
            'capacidad_total' => 10000,
            'estado' => 'OPERATIVO',
            'instalacion' => ['id' => 1, 'nombre' => 'Instalación 1'],
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);

        $response = $this->get('/tanques/1');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.show');
        $response->assertViewHas('tanque', $tanque);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $tanque = [
            'id' => 1,
            'identificador' => 'TAN-001',
            'capacidad_total' => 10000,
            'estado' => 'OPERATIVO',
            'instalacion_id' => 1,
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2'],
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/tanques/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.edit');
        $response->assertViewHas('tanque', $tanque);
        $response->assertViewHas('instalaciones', $instalaciones);
    }

    /** @test */
    public function test_filter_tanques_by_estado()
    {
        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001', 'capacidad_total' => 10000, 'estado' => 'OPERATIVO', 'instalacion' => ['nombre' => 'Inst 1']],
        ];

        $this->mockSuccessfulResponse('/api/tanques', ['data' => $tanques]);

        $response = $this->get('/tanques?estado=OPERATIVO');

        $response->assertStatus(200);
        $response->assertViewHas('tanques');

        $tanques = $response->viewData('tanques');
        $this->assertCount(1, $tanques);
        $this->assertEquals('OPERATIVO', $tanques[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_tanques_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/tanques/exportar*' => Http::response(
                'identificador,capacidad_total,estado\nTAN-001,10000,OPERATIVO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="tanques.csv"',
                ]
            ),
        ]);

        $response = $this->get('/tanques/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2'],
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina'],
            ['id' => 2, 'nombre' => 'Diesel'],
        ];

        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);
        $this->mockSuccessfulResponse('/api/productos', $productos);

        $response = $this->get('/tanques/create');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.create');
        $response->assertViewHas('instalaciones', $instalaciones);
        $response->assertViewHas('productos', $productos);
    }

    /** @test */
    public function test_store_creates_tanque_successfully()
    {
        $tanqueData = [
            'instalacion_id' => 1,
            'identificador' => 'TAN-001',
            'material' => 'Acero',
            'capacidad_total' => 10000,
            'capacidad_util' => 9500,
            'capacidad_operativa' => 9000,
            'capacidad_minima' => 500,
            'temperatura_referencia' => 20,
            'presion_referencia' => 1,
            'tipo_medicion' => 'estatica',
            'estado' => 'OPERATIVO',
        ];

        $createdTanque = array_merge($tanqueData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/tanques', $createdTanque, 'Tanque creado exitosamente', 201);

        $response = $this->post('/tanques', $tanqueData);

        $response->assertRedirect('/tanques/1');
        $response->assertSessionHas('success', 'Tanque creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors_for_invalid_capacities()
    {
        $invalidData = [
            'capacidad_util' => 12000, // Mayor que capacidad_total
            'capacidad_operativa' => 11000, // Mayor que capacidad_util
            'tipo_medicion' => 'INVALIDO',
        ];

        $this->mockValidationErrorResponse('/api/tanques', [
            'capacidad_util' => ['La capacidad util debe ser menor o igual a capacidad total'],
            'capacidad_operativa' => ['La capacidad operativa debe ser menor o igual a capacidad util'],
            'tipo_medicion' => ['El campo tipo medicion debe ser uno de: estatica, dinamica'],
        ]);

        $response = $this->post('/tanques', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['capacidad_util', 'capacidad_operativa', 'tipo_medicion']);
    }

    /** @test */
    public function test_update_modifies_tanque_successfully()
    {
        $updateData = [
            'producto_id' => 2,
            'estado' => 'MANTENIMIENTO',
            'observaciones' => 'Mantenimiento programado',
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', [], 'Tanque actualizado exitosamente');

        $response = $this->put('/tanques/1', $updateData);

        $response->assertRedirect('/tanques/1');
        $response->assertSessionHas('success', 'Tanque actualizado exitosamente');
    }

    /** @test */
    public function test_destroy_deletes_tanque_successfully()
    {
        $this->mockSuccessfulResponse('/api/tanques/1', [], 'Tanque eliminado exitosamente');

        $response = $this->delete('/tanques/1');

        $response->assertRedirect('/tanques');
        $response->assertSessionHas('success', 'Tanque eliminado exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_tanque_has_related_records()
    {
        $this->mockErrorResponse('/api/tanques/1', 'No se puede eliminar el tanque', 409);

        $response = $this->delete('/tanques/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar el tanque');
    }
}