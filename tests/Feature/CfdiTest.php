<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CfdiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_resumen_fiscal_displays_fiscal_summary()
    {
        $filters = [
            'contribuyente_rfc' => 'XAXX010101XXX',
            'anio' => 2024
        ];

        $resumen = [
            'contribuyente' => 'Empresa Prueba SA de CV',
            'rfc' => 'XAXX010101XXX',
            'anio' => 2024,
            'ingresos' => [
                'total' => 150000.00,
                'mensual' => [
                    'Enero' => 12500.00,
                    'Febrero' => 15000.00,
                    'Marzo' => 13000.00
                ]
            ],
            'egresos' => [
                'total' => 95000.00,
                'mensual' => [
                    'Enero' => 8000.00,
                    'Febrero' => 9500.00,
                    'Marzo' => 8500.00
                ]
            ],
            'iva' => [
                'trasladado' => 24000.00,
                'acreditado' => 15200.00,
                'por_pagar' => 8800.00
            ],
            'ieps' => [
                'causado' => 5000.00,
                'acreditado' => 3000.00,
                'por_pagar' => 2000.00
            ]
        ];

        $this->mockSuccessfulResponse('/api/cfdi/resumen-fiscal', $resumen);

        $response = $this->get('/cfdi/resumen/fiscal?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('cfdi.resumen-fiscal');
        $response->assertViewHas('resumen', $resumen);
    }

    /** @test */
    public function test_exportar_downloads_cfdi_file()
    {
        $filters = [
            'uuid' => '12345678-1234-1234-1234-123456789012',
            'rfc_emisor' => 'XAXX010101XXX'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/cfdi/exportar*' => Http::response(
                "uuid,rfc_emisor,rfc_receptor,total\n12345678-1234-1234-1234-123456789012,XAXX010101XXX,XAXX010102XXX,1500.00",
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="cfdi.csv"'
                ]
            )
        ]);

        $response = $this->get('/cfdi/exportar?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="cfdi.csv"');
    }

    /** @test */
    public function test_filter_cfdi_by_estado()
    {
        $cfdis = [
            ['id' => 1, 'uuid' => '12345678-1234-1234-1234-123456789012', 'estado' => 'VIGENTE']
        ];

        $this->mockSuccessfulResponse('/api/cfdi?estado=VIGENTE', ['data' => $cfdis]);

        $response = $this->get('/cfdi?estado=VIGENTE');

        $response->assertStatus(200);
        $response->assertViewHas('cfdis');
        
        $cfdis = $response->viewData('cfdis');
        $this->assertCount(1, $cfdis);
        $this->assertEquals('VIGENTE', $cfdis[0]['estado']);
    }

    /** @test */
    public function test_filter_cfdi_by_date_range()
    {
        $cfdis = [
            ['id' => 1, 'fecha_emision' => '2024-01-15', 'total' => 1500.00]
        ];

        $this->mockSuccessfulResponse('/api/cfdi?fecha_inicio=2024-01-01&fecha_fin=2024-01-31', ['data' => $cfdis]);

        $response = $this->get('/cfdi?fecha_inicio=2024-01-01&fecha_fin=2024-01-31');

        $response->assertStatus(200);
        $response->assertViewHas('cfdis');
        
        $filters = $response->viewData('filters');
        $this->assertEquals('2024-01-01', $filters['fecha_inicio']);
        $this->assertEquals('2024-01-31', $filters['fecha_fin']);
    }
}