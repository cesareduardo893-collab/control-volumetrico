<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ContribuyenteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_contribuyentes_list()
    {
        $contribuyentes = [
            $this->createTestContribuyenteData(['id' => 1, 'rfc' => 'XAXX010101XXX']),
            $this->createTestContribuyenteData(['id' => 2, 'rfc' => 'XAXX010102XXX'])
        ];

        $this->mockPaginatedResponse('/api/contribuyentes', $contribuyentes, 2);

        $response = $this->get('/contribuyentes');

        $response->assertStatus(200);
        $response->assertViewIs('contribuyentes.index');
        $response->assertViewHas('contribuyentes');
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $response = $this->get('/contribuyentes/create');

        $response->assertStatus(200);
        $response->assertViewIs('contribuyentes.create');
    }

    /** @test */
    public function test_store_creates_contribuyente_successfully()
    {
        $contribuyenteData = [
            'rfc' => 'XAXX010101XXX',
            'razon_social' => 'Empresa Prueba SA de CV',
            'nombre_comercial' => 'Prueba',
            'regimen_fiscal' => 'General',
            'domicilio_fiscal' => 'Calle Principal 123',
            'codigo_postal' => '12345',
            'telefono' => '1234567890',
            'email' => 'empresa@test.com',
            'representante_legal' => 'Juan Pérez',
            'representante_rfc' => 'JUAP123456XXX',
            'numero_permiso' => 'PERM-001',
            'tipo_permiso' => 'Venta',
            'activo' => true
        ];

        $createdContribuyente = array_merge($contribuyenteData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/contribuyentes', $createdContribuyente, 'Contribuyente creado exitosamente', 201);

        $response = $this->post('/contribuyentes', $contribuyenteData);

        $response->assertRedirect('/contribuyentes');
        $response->assertSessionHas('success', 'Contribuyente creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors_for_duplicate_rfc()
    {
        $contribuyenteData = $this->createTestContribuyenteData();

        $this->mockValidationErrorResponse('/api/contribuyentes', [
            'rfc' => ['El RFC ya está registrado']
        ]);

        $response = $this->post('/contribuyentes', $contribuyenteData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['rfc']);
    }

    /** @test */
    public function test_show_displays_contribuyente_details()
    {
        $contribuyente = $this->createTestContribuyenteData();

        $this->mockSuccessfulResponse('/api/contribuyentes/1', $contribuyente);

        $response = $this->get('/contribuyentes/1');

        $response->assertStatus(200);
        $response->assertViewIs('contribuyentes.show');
        $response->assertViewHas('contribuyente', $contribuyente);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $contribuyente = $this->createTestContribuyenteData();

        $this->mockSuccessfulResponse('/api/contribuyentes/1', $contribuyente);

        $response = $this->get('/contribuyentes/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('contribuyentes.edit');
        $response->assertViewHas('contribuyente', $contribuyente);
    }

    /** @test */
    public function test_update_modifies_contribuyente_successfully()
    {
        $updateData = [
            'nombre_comercial' => 'Nuevo Nombre Comercial',
            'telefono' => '9876543210',
            'email' => 'nuevo@test.com'
        ];

        $this->mockSuccessfulResponse('/api/contribuyentes/1', [], 'Contribuyente actualizado exitosamente');

        $response = $this->put('/contribuyentes/1', $updateData);

        $response->assertRedirect('/contribuyentes/1');
        $response->assertSessionHas('success', 'Contribuyente actualizado exitosamente');
    }

    /** @test */
    public function test_instalaciones_displays_contribuyente_instalaciones()
    {
        $instalaciones = [
            $this->createTestInstalacionData(['id' => 1, 'nombre' => 'Instalación 1']),
            $this->createTestInstalacionData(['id' => 2, 'nombre' => 'Instalación 2'])
        ];

        $this->mockSuccessfulResponse('/api/contribuyentes/1/instalaciones', ['data' => $instalaciones]);

        $response = $this->get('/contribuyentes/1/instalaciones');

        $response->assertStatus(200);
        $response->assertViewIs('contribuyentes.instalaciones');
        $response->assertViewHas('instalaciones');
    }

    /** @test */
    public function test_cumplimiento_displays_compliance_summary()
    {
        $cumplimiento = [
            'nivel_cumplimiento' => 'ALTO',
            'documentacion_completa' => true,
            'verificaciones_pendientes' => 0,
            'dictamenes_vigentes' => 2,
            'certificados_vigentes' => 1,
            'observaciones' => 'Contribuyente en cumplimiento'
        ];

        $this->mockSuccessfulResponse('/api/contribuyentes/1/cumplimiento', $cumplimiento);

        $response = $this->get('/contribuyentes/1/cumplimiento');

        $response->assertStatus(200);
        $response->assertViewIs('contribuyentes.cumplimiento');
        $response->assertViewHas('cumplimiento', $cumplimiento);
    }

    /** @test */
    public function test_destroy_deletes_contribuyente_successfully()
    {
        $this->mockSuccessfulResponse('/api/contribuyentes/1', [], 'Contribuyente eliminado exitosamente');

        $response = $this->delete('/contribuyentes/1');

        $response->assertRedirect('/contribuyentes');
        $response->assertSessionHas('success', 'Contribuyente eliminado exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_contribuyente_has_related_records()
    {
        $this->mockErrorResponse('/api/contribuyentes/1', 'No se puede eliminar el contribuyente', 409);

        $response = $this->delete('/contribuyentes/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar el contribuyente');
    }

    /** @test */
    public function test_catalogo_returns_json_for_dropdowns()
    {
        $contribuyentes = [
            ['id' => 1, 'rfc' => 'XAXX010101XXX', 'razon_social' => 'Empresa 1'],
            ['id' => 2, 'rfc' => 'XAXX010102XXX', 'razon_social' => 'Empresa 2']
        ];

        $this->mockSuccessfulResponse('/api/contribuyentes/catalogo', $contribuyentes);

        $response = $this->get('/contribuyentes/catalogo/list');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function test_exportar_downloads_contribuyentes_file()
    {
        Http::fake([
            $this->baseApiUrl . '/api/exportar/contribuyentes*' => Http::response(
                'csv content',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="contribuyentes.csv"'
                ]
            )
        ]);

        $response = $this->get('/contribuyentes/exportar');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="contribuyentes.csv"');
    }

    /** @test */
    public function test_search_contribuyentes_by_rfc()
    {
        $contribuyente = $this->createTestContribuyenteData();

        $this->mockSuccessfulResponse('/api/contribuyentes?rfc=XAXX010101XXX', ['data' => [$contribuyente]]);

        $response = $this->get('/contribuyentes?rfc=XAXX010101XXX');

        $response->assertStatus(200);
        $response->assertViewHas('contribuyentes');
        
        $contribuyentes = $response->viewData('contribuyentes');
        $this->assertCount(1, $contribuyentes);
        $this->assertEquals('XAXX010101XXX', $contribuyentes[0]['rfc']);
    }
}