<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CertificadoVerificacionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_certificates_list()
    {
        $certificados = [
            [
                'id' => 1,
                'folio' => 'CERT-001',
                'contribuyente' => ['razon_social' => 'Empresa Prueba'],
                'proveedor_rfc' => 'PROV123456ABC',
                'fecha_emision' => '2024-01-15',
                'resultado' => 'acreditado',
                'vigente' => true,
            ],
            [
                'id' => 2,
                'folio' => 'CERT-002',
                'contribuyente' => ['razon_social' => 'Empresa Test'],
                'proveedor_rfc' => 'PROV789XYZ123',
                'fecha_emision' => '2024-02-20',
                'resultado' => 'no_acreditado',
                'vigente' => false,
            ],
        ];

        $this->mockPaginatedResponse('/api/certificados-verificacion', $certificados, 2);

        $response = $this->get('/certificados-verificacion');

        $response->assertStatus(200);
        $response->assertViewIs('certificados-verificacion.index');
        $response->assertViewHas('certificados');
    }

    /** @test */
    public function test_show_displays_certificate_details()
    {
        $certificado = [
            'id' => 1,
            'folio' => 'CERT-001',
            'contribuyente' => ['id' => 1, 'razon_social' => 'Empresa Prueba'],
            'proveedor_rfc' => 'PROV123456ABC',
            'proveedor_nombre' => 'Proveedor Test',
            'fecha_emision' => '2024-01-15',
            'resultado' => 'acreditado',
            'vigente' => true,
            'fecha_caducidad' => '2025-01-15',
        ];

        $this->mockSuccessfulResponse('/api/certificados-verificacion/1', $certificado);

        $response = $this->get('/certificados-verificacion/1');

        $response->assertStatus(200);
        $response->assertViewIs('certificados-verificacion.show');
        $response->assertViewHas('certificado', $certificado);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $certificado = [
            'id' => 1,
            'folio' => 'CERT-001',
            'contribuyente_id' => 1,
            'proveedor_rfc' => 'PROV123456ABC',
            'resultado' => 'acreditado',
        ];

        $this->mockSuccessfulResponse('/api/certificados-verificacion/1', $certificado);

        $response = $this->get('/certificados-verificacion/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('certificados-verificacion.edit');
        $response->assertViewHas('certificado', $certificado);
    }

    /** @test */
    public function test_filter_certificates_by_resultado()
    {
        $certificados = [
            ['id' => 1, 'resultado' => 'acreditado', 'folio' => 'CERT-001', 'contribuyente' => ['razon_social' => 'Empresa 1'], 'proveedor_rfc' => 'PROV123456ABC', 'fecha_emision' => '2024-01-15', 'vigente' => true],
        ];

        $this->mockSuccessfulResponse('/api/certificados-verificacion', ['data' => $certificados]);

        $response = $this->get('/certificados-verificacion?resultado=acreditado');

        $response->assertStatus(200);
        $response->assertViewHas('certificados');

        $certificados = $response->viewData('certificados');
        $this->assertCount(1, $certificados);
        $this->assertEquals('acreditado', $certificados[0]['resultado']);
    }

    /** @test */
    public function test_exportar_downloads_certificates_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/certificados-verificacion/exportar*' => Http::response(
                'folio,proveedor_rfc,resultado\nCERT-001,PROV123456ABC,acreditado',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="certificados.csv"',
                ]
            ),
        ]);

        $response = $this->get('/certificados-verificacion/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_verificar_vigencia_checks_certificate_validity()
    {
        $resultado = [
            'certificado_id' => 1,
            'folio' => 'CERT-001',
            'vigente' => true,
            'dias_restantes' => 180,
            'fecha_caducidad' => '2025-01-15',
            'mensaje' => 'El certificado se encuentra vigente',
        ];

        $this->mockSuccessfulResponse('/api/certificados-verificacion/1/verificar-vigencia', $resultado);

        $response = $this->get('/certificados-verificacion/1/verificar-vigencia');

        $response->assertStatus(200);
        $response->assertViewIs('certificados-verificacion.vigencia');
        $response->assertViewHas('resultado', $resultado);
    }

    /** @test */
    public function test_estadisticas_displays_certificate_statistics()
    {
        $filters = [
            'contribuyente_id' => 1,
            'anio' => 2024,
        ];

        $estadisticas = [
            'total_certificados' => 12,
            'por_resultado' => [
                'acreditado' => 10,
                'no_acreditado' => 2,
            ],
            'vigentes' => 8,
            'vencidos' => 4,
        ];

        $this->mockSuccessfulResponse('/api/certificados-verificacion/estadisticas', $estadisticas);

        $response = $this->get('/certificados-verificacion/estadisticas?'.http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('certificados-verificacion.estadisticas');
        $response->assertViewHas('estadisticas', $estadisticas);
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $contribuyentes = [
            ['id' => 1, 'razon_social' => 'Empresa 1'],
            ['id' => 2, 'razon_social' => 'Empresa 2'],
        ];

        $this->mockSuccessfulResponse('/api/catalogo/contribuyentes', $contribuyentes);

        $response = $this->get('/certificados-verificacion/create');

        $response->assertStatus(200);
        $response->assertViewIs('certificados-verificacion.create');
        $response->assertViewHas('contribuyentes', $contribuyentes);
    }

    /** @test */
    public function test_store_creates_certificate_successfully()
    {
        $certificadoData = [
            'folio' => 'CERT-001',
            'contribuyente_id' => 1,
            'proveedor_rfc' => 'PROV123456ABC',
            'proveedor_nombre' => 'Proveedor Test',
            'proveedor_numero_acreditacion' => 'ACR-001',
            'fecha_emision' => '2024-01-15',
            'fecha_inicio_verificacion' => '2024-01-10',
            'fecha_fin_verificacion' => '2024-01-14',
            'resultado' => 'acreditado',
            'tabla_cumplimiento' => ['item1' => true, 'item2' => false],
            'observaciones' => 'Certificado de prueba',
            'vigente' => true,
            'fecha_caducidad' => '2025-01-15',
            'requiere_verificacion_extraordinaria' => false,
        ];

        $createdCertificado = array_merge($certificadoData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/certificados-verificacion', $createdCertificado, 'Certificado creado exitosamente', 201);

        $response = $this->post('/certificados-verificacion', $certificadoData);

        $response->assertRedirect('/certificados-verificacion/1');
        $response->assertSessionHas('success', 'Certificado creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'folio' => '',
            'resultado' => 'INVALIDO',
        ];

        $this->mockValidationErrorResponse('/api/certificados-verificacion', [
            'folio' => ['El campo folio es obligatorio'],
            'resultado' => ['El campo resultado debe ser uno de: acreditado, no_acreditado'],
        ]);

        $response = $this->post('/certificados-verificacion', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['folio', 'resultado']);
    }

    /** @test */
    public function test_update_modifies_certificate_successfully()
    {
        $updateData = [
            'observaciones' => 'Certificado actualizado',
            'resultado' => 'acreditado',
        ];

        $this->mockSuccessfulResponse('/api/certificados-verificacion/1', [], 'Certificado actualizado exitosamente');

        $response = $this->put('/certificados-verificacion/1', $updateData);

        $response->assertRedirect('/certificados-verificacion/1');
        $response->assertSessionHas('success', 'Certificado actualizado exitosamente');
    }
}