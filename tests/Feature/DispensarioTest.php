<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DispensarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_dispensarios_list()
    {
        $dispensarios = [
            [
                'id' => 1,
                'clave' => 'DISP-001',
                'modelo' => 'Modelo A',
                'fabricante' => 'Fabricante 1',
                'estado' => 'OPERATIVO',
                'instalacion' => ['nombre' => 'Instalación 1'],
            ],
            [
                'id' => 2,
                'clave' => 'DISP-002',
                'modelo' => 'Modelo B',
                'fabricante' => 'Fabricante 2',
                'estado' => 'MANTENIMIENTO',
                'instalacion' => ['nombre' => 'Instalación 2'],
            ],
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2'],
        ];

        $this->mockPaginatedResponse('/api/dispensarios', $dispensarios, 2);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/dispensarios');

        $response->assertStatus(200);
        $response->assertViewIs('dispensarios.index');
        $response->assertViewHas('dispensarios');
        $response->assertViewHas('instalaciones', $instalaciones);
    }

    /** @test */
    public function test_show_displays_dispensario_details()
    {
        $dispensario = [
            'id' => 1,
            'clave' => 'DISP-001',
            'modelo' => 'Modelo A',
            'fabricante' => 'Fabricante 1',
            'estado' => 'OPERATIVO',
            'instalacion' => ['id' => 1, 'nombre' => 'Instalación 1'],
            'numero_serie' => 'SN-001',
            'fecha_instalacion' => '2024-01-15',
        ];

        $this->mockSuccessfulResponse('/api/dispensarios/1', $dispensario);

        $response = $this->get('/dispensarios/1');

        $response->assertStatus(200);
        $response->assertViewIs('dispensarios.show');
        $response->assertViewHas('dispensario', $dispensario);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $dispensario = [
            'id' => 1,
            'clave' => 'DISP-001',
            'modelo' => 'Modelo A',
            'fabricante' => 'Fabricante 1',
            'instalacion_id' => 1,
            'estado' => 'OPERATIVO',
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2'],
        ];

        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001'],
            ['id' => 2, 'identificador' => 'TAN-002'],
        ];

        $this->mockSuccessfulResponse('/api/dispensarios/1', $dispensario);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);
        $this->mockSuccessfulResponse('/api/tanques', $tanques);

        $response = $this->get('/dispensarios/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('dispensarios.edit');
        $response->assertViewHas('dispensario', $dispensario);
        $response->assertViewHas('instalaciones', $instalaciones);
        $response->assertViewHas('tanques', $tanques);
    }

    /** @test */
    public function test_filter_dispensarios_by_estado()
    {
        $dispensarios = [
            ['id' => 1, 'estado' => 'OPERATIVO', 'clave' => 'DISP-001', 'modelo' => 'Modelo A', 'fabricante' => 'Fabricante 1', 'instalacion' => ['nombre' => 'Inst 1']],
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
        ];

        $this->mockSuccessfulResponse('/api/dispensarios', ['data' => $dispensarios]);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/dispensarios?estado=OPERATIVO');

        $response->assertStatus(200);
        $response->assertViewHas('dispensarios');

        $dispensarios = $response->viewData('dispensarios');
        $this->assertCount(1, $dispensarios);
        $this->assertEquals('OPERATIVO', $dispensarios[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_dispensarios_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/exportar/dispensarios*' => Http::response(
                'clave,modelo,estado\nDISP-001,Modelo A,OPERATIVO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="dispensarios.csv"',
                ]
            ),
            '*' => Http::response(['success' => true, 'data' => []], 200),
        ]);

        $response = $this->get('/dispensarios/exportar');

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

        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001'],
            ['id' => 2, 'identificador' => 'TAN-002'],
        ];

        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);
        $this->mockSuccessfulResponse('/api/tanques', $tanques);

        $response = $this->get('/dispensarios/create');

        $response->assertStatus(200);
        $response->assertViewIs('dispensarios.create');
        $response->assertViewHas('instalaciones', $instalaciones);
        $response->assertViewHas('tanques', $tanques);
    }

    /** @test */
    public function test_store_creates_dispensario_successfully()
    {
        $dispensarioData = [
            'clave' => 'DISP-001',
            'modelo' => 'Modelo A',
            'fabricante' => 'Fabricante 1',
            'instalacion_id' => 1,
            'estado' => 'OPERATIVO',
            'numero_serie' => 'SN-001',
            'fecha_instalacion' => '2024-01-15',
            'capacidad_maxima' => 1000,
            'presion_operacion' => 10,
        ];

        $createdDispensario = array_merge($dispensarioData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/dispensarios', $createdDispensario, 'Dispensario creado exitosamente', 201);

        $response = $this->post('/dispensarios', $dispensarioData);

        $response->assertRedirect('/dispensarios/1');
        $response->assertSessionHas('success', 'Dispensario creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'clave' => '',
            'estado' => 'INVALIDO',
        ];

        $this->mockValidationErrorResponse('/api/dispensarios', [
            'clave' => ['El campo clave es obligatorio'],
            'estado' => ['El campo estado debe ser uno de: OPERATIVO, MANTENIMIENTO, INACTIVO'],
        ]);

        $response = $this->post('/dispensarios', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['clave', 'estado']);
    }

    /** @test */
    public function test_update_modifies_dispensario_successfully()
    {
        $updateData = [
            'estado' => 'MANTENIMIENTO',
            'observaciones' => 'Mantenimiento preventivo',
        ];

        $this->mockSuccessfulResponse('/api/dispensarios/1', [], 'Dispensario actualizado exitosamente');

        $response = $this->put('/dispensarios/1', $updateData);

        $response->assertRedirect('/dispensarios/1');
        $response->assertSessionHas('success', 'Dispensario actualizado exitosamente');
    }

    /** @test */
    public function test_destroy_deletes_dispensario_successfully()
    {
        $this->mockSuccessfulResponse('/api/dispensarios/1', [], 'Dispensario eliminado exitosamente');

        $response = $this->delete('/dispensarios/1');

        $response->assertRedirect('/dispensarios');
        $response->assertSessionHas('success', 'Dispensario eliminado exitosamente');
    }
}