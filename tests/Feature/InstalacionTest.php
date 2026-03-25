<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class InstalacionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_instalaciones_list()
    {
        $instalaciones = [
            $this->createTestInstalacionData(['id' => 1, 'clave_instalacion' => 'INST-001']),
            $this->createTestInstalacionData(['id' => 2, 'clave_instalacion' => 'INST-002'])
        ];

        $this->mockPaginatedResponse('/api/instalaciones', $instalaciones, 2);

        $response = $this->get('/instalaciones');

        $response->assertStatus(200);
        $response->assertViewIs('instalaciones.index');
        $response->assertViewHas('instalaciones');
    }

    /** @test */
    public function test_create_form_displays_correctly_with_contribuyentes()
    {
        $contribuyentes = [
            ['id' => 1, 'rfc' => 'XAXX010101XXX', 'razon_social' => 'Empresa 1'],
            ['id' => 2, 'rfc' => 'XAXX010102XXX', 'razon_social' => 'Empresa 2']
        ];

        $this->mockSuccessfulResponse('/api/catalogo/contribuyentes', $contribuyentes);

        $response = $this->get('/instalaciones/create');

        $response->assertStatus(200);
        $response->assertViewIs('instalaciones.create');
        $response->assertViewHas('contribuyentes', $contribuyentes);
    }

    /** @test */
    public function test_store_creates_instalacion_successfully()
    {
        $instalacionData = [
            'contribuyente_id' => 1,
            'clave_instalacion' => 'INST-001',
            'nombre' => 'Instalación de Prueba',
            'tipo_instalacion' => 'Almacenamiento',
            'domicilio' => 'Calle Principal 123',
            'codigo_postal' => '12345',
            'municipio' => 'Municipio Test',
            'estado' => 'Estado Test',
            'estatus' => 'OPERACION',
            'telefono' => '1234567890',
            'email' => 'instalacion@test.com',
            'fecha_apertura' => '2024-01-01'
        ];

        $createdInstalacion = array_merge($instalacionData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/instalaciones', $createdInstalacion, 'Instalación creada exitosamente', 201);

        $response = $this->post('/instalaciones', $instalacionData);

        $response->assertRedirect('/instalaciones/1');
        $response->assertSessionHas('success', 'Instalación creada exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'clave_instalacion' => '',
            'estatus' => 'INVALIDO'
        ];

        $this->mockValidationErrorResponse('/api/instalaciones', [
            'clave_instalacion' => ['El campo clave_instalacion es obligatorio'],
            'estatus' => ['El campo estatus debe ser uno de: OPERACION, SUSPENDIDA, CANCELADA']
        ]);

        $response = $this->post('/instalaciones', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['clave_instalacion', 'estatus']);
    }

    /** @test */
    public function test_show_displays_instalacion_details()
    {
        $instalacion = $this->createTestInstalacionData();

        $this->mockSuccessfulResponse('/api/instalaciones/1', $instalacion);

        $response = $this->get('/instalaciones/1');

        $response->assertStatus(200);
        $response->assertViewIs('instalaciones.show');
        $response->assertViewHas('instalacion', $instalacion);
    }

    /** @test */
    public function test_tanques_displays_instalacion_tanques()
    {
        $tanques = [
            $this->createTestTanqueData(['id' => 1, 'identificador' => 'TAN-001']),
            $this->createTestTanqueData(['id' => 2, 'identificador' => 'TAN-002'])
        ];

        $this->mockSuccessfulResponse('/api/instalaciones/1/tanques', ['data' => $tanques]);

        $response = $this->get('/instalaciones/1/tanques');

        $response->assertStatus(200);
        $response->assertViewIs('instalaciones.tanques');
        $response->assertViewHas('tanques');
        $response->assertViewHas('instalacion_id', 1);
    }

    /** @test */
    public function test_medidores_displays_instalacion_medidores()
    {
        $medidores = [
            ['id' => 1, 'numero_serie' => 'MED-001', 'tipo_medicion' => 'estatica'],
            ['id' => 2, 'numero_serie' => 'MED-002', 'tipo_medicion' => 'dinamica']
        ];

        $this->mockSuccessfulResponse('/api/instalaciones/1/medidores', ['data' => $medidores]);

        $response = $this->get('/instalaciones/1/medidores');

        $response->assertStatus(200);
        $response->assertViewIs('instalaciones.medidores');
        $response->assertViewHas('medidores');
    }

    /** @test */
    public function test_dispensarios_displays_instalacion_dispensarios()
    {
        $dispensarios = [
            ['id' => 1, 'clave' => 'DIS-001', 'modelo' => 'Modelo A'],
            ['id' => 2, 'clave' => 'DIS-002', 'modelo' => 'Modelo B']
        ];

        $this->mockSuccessfulResponse('/api/instalaciones/1/dispensarios', ['data' => $dispensarios]);

        $response = $this->get('/instalaciones/1/dispensarios');

        $response->assertStatus(200);
        $response->assertViewIs('instalaciones.dispensarios');
        $response->assertViewHas('dispensarios');
    }

    /** @test */
    public function test_resumen_operativo_displays_operational_summary()
    {
        $resumen = [
            'total_tanques' => 5,
            'tanques_operativos' => 4,
            'total_medidores' => 10,
            'medidores_operativos' => 9,
            'volumen_almacenado' => 25000,
            'capacidad_total' => 50000,
            'alertas_activas' => 2
        ];

        $this->mockSuccessfulResponse('/api/instalaciones/1/resumen-operativo', $resumen);

        $response = $this->get('/instalaciones/1/resumen-operativo');

        $response->assertStatus(200);
        $response->assertViewIs('instalaciones.resumen-operativo');
        $response->assertViewHas('resumen', $resumen);
    }

    /** @test */
    public function test_update_modifies_instalacion_successfully()
    {
        $updateData = [
            'nombre' => 'Instalación Actualizada',
            'telefono' => '9876543210',
            'estatus' => 'SUSPENDIDA'
        ];

        $this->mockSuccessfulResponse('/api/instalaciones/1', [], 'Instalación actualizada exitosamente');

        $response = $this->put('/instalaciones/1', $updateData);

        $response->assertRedirect('/instalaciones/1');
        $response->assertSessionHas('success', 'Instalación actualizada exitosamente');
    }

    /** @test */
    public function test_destroy_deletes_instalacion_successfully()
    {
        $this->mockSuccessfulResponse('/api/instalaciones/1', [], 'Instalación eliminada exitosamente');

        $response = $this->delete('/instalaciones/1');

        $response->assertRedirect('/instalaciones');
        $response->assertSessionHas('success', 'Instalación eliminada exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_instalacion_has_related_records()
    {
        $this->mockErrorResponse('/api/instalaciones/1', 'No se puede eliminar la instalación', 409);

        $response = $this->delete('/instalaciones/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar la instalación');
    }
}