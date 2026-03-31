<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_dashboard_with_data()
    {
        $resumen = [
            'contribuyentes_activos' => 25,
            'instalaciones_activas' => 12,
            'alarmas_activas' => 3,
            'volumen_total' => 150000,
            'ultimos_movimientos' => [
                [
                    'fecha_movimiento' => '2024-01-20',
                    'instalacion' => 'Estación Centro',
                    'producto' => 'Gasolina',
                    'tipo_movimiento' => 'entrada',
                    'volumen_neto' => 5000.00,
                    'estado' => 'validado',
                ],
            ],
        ];

        $this->mockSuccessfulResponse('/api/dashboard/resumen', $resumen);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
        $response->assertViewHas('resumen');
    }

    /** @test */
    public function test_index_handles_api_errors_gracefully()
    {
        $this->mockErrorResponse('/api/dashboard/resumen', 'Error al cargar resumen', 500);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');

        $resumen = $response->viewData('resumen');
        $this->assertEquals(0, $resumen['contribuyentes_activos']);
        $this->assertEquals(0, $resumen['instalaciones_activas']);
        $this->assertEquals(0, $resumen['alarmas_activas']);
    }

    /** @test */
    public function test_grafica_movimientos_returns_chart_data()
    {
        $dias = 7;
        $graficaData = [
            'labels' => ['2024-01-14', '2024-01-15', '2024-01-16'],
            'entradas' => [5000, 4500, 6000],
            'salidas' => [3000, 2800, 3500],
        ];

        $this->mockSuccessfulResponse('/api/dashboard/grafica-movimientos', $graficaData);

        $response = $this->getJson('/api/dashboard/grafica-movimientos?dias='.$dias);

        $response->assertStatus(200);
        $response->assertJsonStructure(['labels', 'entradas', 'salidas']);
    }

    /** @test */
    public function test_grafica_movimientos_returns_empty_data_on_error()
    {
        $this->mockErrorResponse('/api/dashboard/grafica-movimientos', 'Error', 500);

        $response = $this->getJson('/api/dashboard/grafica-movimientos');

        $response->assertStatus(200);
        $response->assertJson([
            'labels' => [],
            'entradas' => [],
            'salidas' => [],
        ]);
    }

    /** @test */
    public function test_grafica_productos_returns_product_distribution()
    {
        $graficaData = [
            'labels' => ['Gasolina', 'Diesel', 'Turbosina', 'Gas LP'],
            'valores' => [45, 30, 15, 10],
        ];

        $this->mockSuccessfulResponse('/api/dashboard/grafica-productos', $graficaData);

        $response = $this->getJson('/api/dashboard/grafica-productos');

        $response->assertStatus(200);
        $response->assertJsonStructure(['labels', 'valores']);
    }

    /** @test */
    public function test_notificaciones_returns_notifications()
    {
        $notificaciones = [
            [
                'id' => 1,
                'tipo' => 'ALARMA',
                'titulo' => 'Alarma de nivel alto',
                'mensaje' => 'El tanque TAN-001 ha alcanzado nivel crítico',
                'leida' => false,
                'fecha' => '2024-01-20 10:30:00',
            ],
        ];

        $this->mockSuccessfulResponse('/api/notificaciones', $notificaciones);

        $response = $this->getJson('/api/notificaciones');

        $response->assertStatus(200);
        $response->assertJsonStructure([['id', 'tipo', 'titulo', 'mensaje']]);
    }

    /** @test */
    public function test_exportar_downloads_dashboard_report()
    {
        Http::fake([
            $this->baseApiUrl.'/api/exportar/dashboard*' => Http::response(
                "fecha,tipo,volumen,producto\n2024-01-15,recepcion,5000,Gasolina",
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="dashboard_report.csv"',
                ]
            ),
            '*' => Http::response(['success' => true, 'data' => []], 200),
        ]);

        $response = $this->get('/dashboard/exportar?fecha_inicio=2024-01-01&fecha_fin=2024-01-31&tipo_reporte=resumen');

        $response->assertStatus(200);
        $response->assertHeaderContains('Content-Type', 'text/csv');
    }

    /** @test */
    public function test_dashboard_refreshes_data_periodically()
    {
        $this->mockSuccessfulResponse('/api/dashboard/resumen', [
            'contribuyentes_activos' => 25,
            'instalaciones_activas' => 12,
            'alarmas_activas' => 3,
            'volumen_total' => 150000,
        ]);

        $response1 = $this->get('/dashboard');
        $resumen1 = $response1->viewData('resumen');
        $this->assertEquals(25, $resumen1['contribuyentes_activos']);

        $this->mockSuccessfulResponse('/api/dashboard/resumen', [
            'contribuyentes_activos' => 30,
            'instalaciones_activas' => 15,
            'alarmas_activas' => 5,
            'volumen_total' => 180000,
        ]);

        $response2 = $this->get('/dashboard');
        $resumen2 = $response2->viewData('resumen');
        $this->assertEquals(30, $resumen2['contribuyentes_activos']);
    }

    /** @test */
    public function test_dashboard_shows_alerts_when_thresholds_exceeded()
    {
        $resumen = [
            'contribuyentes_activos' => 25,
            'instalaciones_activas' => 12,
            'alarmas_activas' => 5,
            'volumen_total' => 150000,
            'ultimos_movimientos' => [],
        ];

        $this->mockSuccessfulResponse('/api/dashboard/resumen', $resumen);

        $response = $this->get('/dashboard');

        $resumenData = $response->viewData('resumen');
        $this->assertEquals(5, $resumenData['alarmas_activas']);
    }
}
