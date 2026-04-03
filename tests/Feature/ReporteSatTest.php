<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReporteSatTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_reportes_list()
    {
        $reportes = [
            [
                'id' => 1,
                'folio' => 'RSAT-001',
                'instalacion' => ['nombre' => 'Instalaciรณn 1'],
                'periodo' => '2024-01',
                'tipo_reporte' => 'MENSUAL',
                'estado' => 'GENERADO',
                'fecha_generacion' => '2024-02-01',
            ],
            [
                'id' => 2,
                'folio' => 'RSAT-002',
                'instalacion' => ['nombre' => 'Instalaciรณn 2'],
                'periodo' => '2024-02',
                'tipo_reporte' => 'MENSUAL',
                'estado' => 'FIRMADO',
                'fecha_generacion' => '2024-03-01',
            ],
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalaciรณn 1'],
            ['id' => 2, 'nombre' => 'Instalaciรณn 2'],
        ];

        $this->mockPaginatedResponse('/api/reportes-sat', $reportes, 2);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/reportes-sat');

        $response->assertStatus(200);
        $response->assertViewIs('reportes-sat.index');
        $response->assertViewHas('reportes');
        $response->assertViewHas('instalaciones', $instalaciones);
    }

    /** @test */
    public function test_show_displays_report_details()
    {
        $reporte = [
            'id' => 1,
            'folio' => 'RSAT-001',
            'instalacion' => ['id' => 1, 'nombre' => 'Instalaciรณn 1'],
            'periodo' => '2024-01',
            'tipo_reporte' => 'MENSUAL',
            'estado' => 'GENERADO',
            'fecha_generacion' => '2024-02-01',
        ];

        $this->mockSuccessfulResponse('/api/reportes-sat/1', $reporte);

        $response = $this->get('/reportes-sat/1');

        $response->assertStatus(200);
        $response->assertViewIs('reportes-sat.show');
        $response->assertViewHas('reporte', $reporte);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $reporte = [
            'id' => 1,
            'folio' => 'RSAT-001',
            'instalacion_id' => 1,
            'periodo' => '2024-01',
            'tipo_reporte' => 'MENSUAL',
            'estado' => 'GENERADO',
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalaciรณn 1'],
            ['id' => 2, 'nombre' => 'Instalaciรณn 2'],
        ];

        $this->mockSuccessfulResponse('/api/reportes-sat/1', $reporte);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/reportes-sat/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('reportes-sat.edit');
        $response->assertViewHas('reporte', $reporte);
        $response->assertViewHas('instalaciones', $instalaciones);
    }

    /** @test */
    public function test_filter_reportes_by_estado()
    {
        $reportes = [
            ['id' => 1, 'estado' => 'GENERADO', 'folio' => 'RSAT-001', 'instalacion' => ['nombre' => 'Inst 1'], 'periodo' => '2024-01', 'tipo_reporte' => 'MENSUAL', 'fecha_generacion' => '2024-02-01'],
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalaciรณn 1'],
        ];

        $this->mockSuccessfulResponse('/api/reportes-sat', ['data' => $reportes]);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/reportes-sat?estado=GENERADO');

        $response->assertStatus(200);
        $response->assertViewHas('reportes');

        $reportes = $response->viewData('reportes');
        $this->assertCount(1, $reportes);
        $this->assertEquals('GENERADO', $reportes[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_reportes_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/reportes-sat/exportar*' => Http::response(
                'folio,periodo,estado\nRSAT-001,2024-01,GENERADO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="reportes_sat.csv"',
                ]
            ),
        ]);

        $response = $this->get('/reportes-sat/exportar');

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
            ['id' => 1, 'nombre' => 'Instalaciรณn 1'],
            ['id' => 2, 'nombre' => 'Instalaciรณn 2'],
        ];

        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/reportes-sat/create');

        $response->assertStatus(200);
        $response->assertViewIs('reportes-sat.create');
        $response->assertViewHas('instalaciones', $instalaciones);
    }

    /** @test */
    public function test_store_generates_report_successfully()
    {
        $reporteData = [
            'instalacion_id' => 1,
            'periodo' => '2024-01',
            'tipo_reporte' => 'MENSUAL',
        ];

        $createdReporte = [
            'id' => 1,
            'folio' => 'RSAT-001',
            'instalacion_id' => 1,
            'periodo' => '2024-01',
            'tipo_reporte' => 'MENSUAL',
            'estado' => 'GENERADO',
        ];

        $this->mockSuccessfulResponse('/api/reportes-sat/generar', $createdReporte, 'Reporte SAT generado exitosamente', 201);

        $response = $this->post('/reportes-sat', $reporteData);

        $response->assertRedirect('/reportes-sat/1');
        $response->assertSessionHas('success', 'Reporte SAT generado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'instalacion_id' => '',
            'periodo' => 'invalid-format',
        ];

        $this->mockValidationErrorResponse('/api/reportes-sat/generar', [
            'instalacion_id' => ['El campo instalacion_id es obligatorio'],
            'periodo' => ['El campo periodo debe tener el formato YYYY-MM'],
        ]);

        $response = $this->post('/reportes-sat', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['instalacion_id', 'periodo']);
    }

    /** @test */
    public function test_firmar_signs_report_successfully()
    {
        $this->mockSuccessfulResponse('/api/reportes-sat/1/firmar', [], 'Reporte firmado exitosamente');

        $response = $this->post('/reportes-sat/1/firmar');

        $response->assertRedirect('/reportes-sat/1');
        $response->assertSessionHas('success', 'Reporte firmado exitosamente');
    }

    /** @test */
    public function test_enviar_sends_report_to_sat()
    {
        $this->mockSuccessfulResponse('/api/reportes-sat/1/enviar-sat', [], 'Reporte enviado al SAT exitosamente');

        $response = $this->post('/reportes-sat/1/enviar-sat');

        $response->assertRedirect('/reportes-sat/1');
        $response->assertSessionHas('success', 'Reporte enviado al SAT exitosamente');
    }

    /** @test */
    public function test_generar_anual_generates_annual_reports()
    {
        $anualData = [
            'instalacion_id' => 1,
            'anio' => 2024,
        ];

        $resultado = [
            'total_reportes' => 12,
            'instalacion' => 'Instalaciรณn 1',
            'anio' => 2024,
        ];

        $this->mockSuccessfulResponse('/api/reportes-sat/generar-anual', $resultado, 'Reportes anuales generados exitosamente');

        $response = $this->post('/reportes-sat/generar-anual', $anualData);

        $response->assertRedirect('/reportes-sat');
        $response->assertSessionHas('success', 'Se generaron 12 reportes para el aรฑo 2024');
    }

    /** @test */
    public function test_filter_reportes_by_estado()
    {
        $reportes = [
            ['id' => 1, 'estado' => 'GENERADO', 'folio' => 'RSAT-001', 'periodo' => '2024-01', 'tipo_reporte' => 'MENSUAL', 'instalacion' => ['nombre' => 'Inst 1']],
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
        ];

        $this->mockSuccessfulResponse('/api/reportes-sat', ['data' => $reportes]);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/reportes-sat?estado=GENERADO');

        $response->assertStatus(200);
        $response->assertViewHas('reportes');

        $reportes = $response->viewData('reportes');
        $this->assertCount(1, $reportes);
        $this->assertEquals('GENERADO', $reportes[0]['estado']);
    }

    /** @test */
    public function test_exportar_downloads_reportes_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/reportes-sat*' => Http::response(
                'folio,periodo,estado\nRSAT-001,2024-01,GENERADO',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="reportes_sat.csv"',
                ]
            ),
            '*' => Http::response(['success' => true, 'data' => []], 200),
        ]);

        $response = $this->get('/reportes-sat/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_filter_reportes_by_estado()
    {
        $reportes = [
            ['id' => 1, 'estado' => 'GENERADO', 'folio' => 'RSAT-001', 'periodo' => '2024-01', 'tipo_reporte' => 'MENSUAL', 'instalacion' => ['nombre' => 'Inst 1']],
        ];

        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalaciรณn 1'],
        ];

        $this->mockSuccessfulResponse('/api/reportes-sat', ['data' => $reportes]);
        $this->mockSuccessfulResponse('/api/instalaciones', $instalaciones);

        $response = $this->get('/reportes-sat?estado=GENERADO');

        $response->assertStatus(200);
        $response->assertViewHas('reportes');

        $reportes = $response->viewData('reportes');
        $this->assertCount(1, $reportes);
        $this->assertEquals('GENERADO', $reportes[0]['estado']);
    }
}