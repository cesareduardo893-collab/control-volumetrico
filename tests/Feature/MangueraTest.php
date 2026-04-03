<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MangueraTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_mangueras_list()
    {
        $mangueras = [
            [
                'id' => 1,
                'clave' => 'MANG-001',
                'descripcion' => 'Manguera 1',
                'estado' => 'OPERATIVO',
                'dispensario' => ['clave' => 'DISP-001'],
                'medidor' => ['numero_serie' => 'MED-001'],
            ],
            [
                'id' => 2,
                'clave' => 'MANG-002',
                'descripcion' => 'Manguera 2',
                'estado' => 'MANTENIMIENTO',
                'dispensario' => ['clave' => 'DISP-002'],
                'medidor' => null,
            ],
        ];

        $this->mockPaginatedResponse('/api/mangueras', $mangueras, 2);

        $response = $this->get('/mangueras');

        $response->assertStatus(200);
        $response->assertViewIs('mangueras.index');
        $response->assertViewHas('mangueras');
    }

    /** @test */
    public function test_show_displays_manguera_details()
    {
        $manguera = [
            'id' => 1,
            'clave' => 'MANG-001',
            'descripcion' => 'Manguera de prueba',
            'estado' => 'OPERATIVO',
            'dispensario' => ['id' => 1, 'clave' => 'DISP-001'],
            'medidor' => ['id' => 1, 'numero_serie' => 'MED-001'],
        ];

        $this->mockSuccessfulResponse('/api/mangueras/1', $manguera);

        $response = $this->get('/mangueras/1');

        $response->assertStatus(200);
        $response->assertViewIs('mangueras.show');
        $response->assertViewHas('manguera', $manguera);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $manguera = [
            'id' => 1,
            'clave' => 'MANG-001',
            'descripcion' => 'Manguera de prueba',
            'dispensario_id' => 1,
            'medidor_id' => 1,
            'estado' => 'OPERATIVO',
        ];

        $medidores = [
            ['id' => 1, 'numero_serie' => 'MED-001'],
            ['id' => 2, 'numero_serie' => 'MED-002'],
        ];

        $this->mockSuccessfulResponse('/api/mangueras/1', $manguera);
        $this->mockSuccessfulResponse('/api/medidores', $medidores);

        $response = $this->get('/mangueras/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('mangueras.edit');
        $response->assertViewHas('manguera', $manguera);
        $response->assertViewHas('medidores', $medidores);
    }

    /** @test */
    public function test_filter_mangueras_by_estado()
    {
        $mangueras = [
            ['id' => 1, 'estado' => 'OPERATIVO', 'clave' => 'MANG-001', 'descripcion' => 'Manguera 1', 'dispensario' => ['clave' => 'DISP-001']],
        ];

        $this->mockSuccessfulResponse('/api/mangueras', ['data' => $mangueras]);

        $response = $this->get('/mangueras?estado=OPERATIVO');

        $response->assertStatus(200);
        $response->assertViewHas('mangueras');

        $mangueras = $response->viewData('mangueras');
        $this->assertCount(1, $mangueras);
        $this->assertEquals('OPERATIVO', $mangueras[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_mangueras_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/mangueras/exportar*' => Http::response(
                'clave,descripcion,estado\nMANG-001,Manguera 1,OPERATIVO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="mangueras.csv"',
                ]
            ),
        ]);

        $response = $this->get('/mangueras/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $dispensarios = [
            ['id' => 1, 'clave' => 'DISP-001'],
            ['id' => 2, 'clave' => 'DISP-002'],
        ];

        $medidores = [
            ['id' => 1, 'numero_serie' => 'MED-001'],
            ['id' => 2, 'numero_serie' => 'MED-002'],
        ];

        $this->mockSuccessfulResponse('/api/dispensarios', $dispensarios);
        $this->mockSuccessfulResponse('/api/medidores', $medidores);

        $response = $this->get('/mangueras/create');

        $response->assertStatus(200);
        $response->assertViewIs('mangueras.create');
        $response->assertViewHas('dispensarios', $dispensarios);
        $response->assertViewHas('medidores', $medidores);
    }

    /** @test */
    public function test_store_creates_manguera_successfully()
    {
        $mangueraData = [
            'dispensario_id' => 1,
            'clave' => 'MANG-001',
            'descripcion' => 'Manguera de prueba',
            'medidor_id' => 1,
            'estado' => 'OPERATIVO',
        ];

        $createdManguera = array_merge($mangueraData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/mangueras', $createdManguera, 'Manguera creada exitosamente', 201);

        $response = $this->post('/mangueras', $mangueraData);

        $response->assertRedirect('/mangueras/1');
        $response->assertSessionHas('success', 'Manguera creada exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'clave' => '',
            'estado' => 'INVALIDO',
        ];

        $this->mockValidationErrorResponse('/api/mangueras', [
            'clave' => ['El campo clave es obligatorio'],
            'estado' => ['El campo estado debe ser uno de: OPERATIVO, MANTENIMIENTO, FUERA_SERVICIO'],
        ]);

        $response = $this->post('/mangueras', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['clave', 'estado']);
    }

    /** @test */
    public function test_update_modifies_manguera_successfully()
    {
        $updateData = [
            'descripcion' => 'Manguera actualizada',
            'estado' => 'MANTENIMIENTO',
        ];

        $this->mockSuccessfulResponse('/api/mangueras/1', [], 'Manguera actualizada exitosamente');

        $response = $this->put('/mangueras/1', $updateData);

        $response->assertRedirect('/mangueras/1');
        $response->assertSessionHas('success', 'Manguera actualizada exitosamente');
    }

    /** @test */
    public function test_destroy_deletes_manguera_successfully()
    {
        $this->mockSuccessfulResponse('/api/mangueras/1', [], 'Manguera eliminada exitosamente');

        $response = $this->delete('/mangueras/1');

        $response->assertRedirect('/mangueras');
        $response->assertSessionHas('success', 'Manguera eliminada exitosamente');
    }

    /** @test */
    public function test_asignar_medidor_assigns_meter_successfully()
    {
        $asignarData = [
            'medidor_id' => 2,
        ];

        $this->mockSuccessfulResponse('/api/mangueras/1/asignar-medidor', [], 'Medidor asignado exitosamente');

        $response = $this->post('/mangueras/1/asignar-medidor', $asignarData);

        $response->assertRedirect('/mangueras/1');
        $response->assertSessionHas('success', 'Medidor asignado exitosamente');
    }

    /** @test */
    public function test_asignar_medidor_fails_if_already_assigned()
    {
        $asignarData = [
            'medidor_id' => 2,
        ];

        $this->mockErrorResponse('/api/mangueras/1/asignar-medidor', 'El medidor ya está asignado a otra manguera', 422);

        $response = $this->post('/mangueras/1/asignar-medidor', $asignarData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El medidor ya está asignado a otra manguera');
    }

    /** @test */
    public function test_quitar_medidor_removes_meter_successfully()
    {
        $this->mockSuccessfulResponse('/api/mangueras/1/quitar-medidor', [], 'Medidor quitado exitosamente');

        $response = $this->post('/mangueras/1/quitar-medidor');

        $response->assertRedirect('/mangueras/1');
        $response->assertSessionHas('success', 'Medidor quitado exitosamente');
    }
}