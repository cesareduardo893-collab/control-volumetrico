<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

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
        $resumen = [
            'contribuyente' => 'Empresa Prueba SA de CV',
            'rfc' => 'XAXX010101XXX',
            'anio' => 2024,
            'ingresos' => ['total' => 150000.00],
            'egresos' => ['total' => 95000.00],
        ];

        $this->mockSuccessfulResponse('/api/cfdi/resumen-fiscal', $resumen);

        $response = $this->get('/cfdi/resumen/fiscal?contribuyente_rfc=XAXX010101XXX&anio=2024');

        $response->assertStatus(200);
        $response->assertViewHas('resumen');
    }

    /** @test */
    public function test_exportar_downloads_cfdi_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/cfdi/exportar*' => Http::response(
                "uuid,rfc_emisor,total\n12345678-1234-1234-1234-123456789012,XAXX010101XXX,1500.00",
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="cfdi.csv"',
                ]
            ),
            '*' => Http::response(['success' => true, 'data' => []], 200),
        ]);

        // Check what the controller returns
        $response = $this->get('/cfdi/exportar?uuid=12345678-1234-1234-1234-123456789012');

        if ($response->status() === 302) {
            $this->fail('Redirected to: '.$response->headers->get('Location').' with session error: '.session('error'));
        }

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_filter_cfdi_by_estado()
    {
        $cfdis = [
            [
                'id' => 1,
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'fecha_emision' => '2024-01-15',
                'rfc_emisor' => 'AAA010101AAA',
                'nombre_emisor' => 'Empresa Emisora',
                'rfc_receptor' => 'BBB010101BBB',
                'nombre_receptor' => 'Empresa Receptora',
                'tipo_operacion' => 'adquisicion',
                'subtotal' => 1000.00,
                'iva' => 160.00,
                'total' => 1160.00,
                'estado' => 'VIGENTE',
            ],
        ];

        $this->mockPaginatedResponse('/api/cfdi', $cfdis, 1);

        $response = $this->get('/cfdi?estado=VIGENTE');

        $response->assertStatus(200);
        $response->assertViewHas('cfdis');
    }

    /** @test */
    public function test_filter_cfdi_by_date_range()
    {
        $cfdis = [
            [
                'id' => 1,
                'uuid' => '12345678-1234-1234-1234-123456789012',
                'fecha_emision' => '2024-01-15',
                'rfc_emisor' => 'AAA010101AAA',
                'nombre_emisor' => 'Empresa Emisora',
                'rfc_receptor' => 'BBB010101BBB',
                'nombre_receptor' => 'Empresa Receptora',
                'tipo_operacion' => 'adquisicion',
                'subtotal' => 1000.00,
                'iva' => 160.00,
                'total' => 1500.00,
                'estado' => 'VIGENTE',
            ],
        ];

        $this->mockPaginatedResponse('/api/cfdi', $cfdis, 1);

        $response = $this->get('/cfdi?fecha_inicio=2024-01-01&fecha_fin=2024-01-31');

        $response->assertStatus(200);
        $response->assertViewHas('cfdis');
    }
}
