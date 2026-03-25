<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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
                    'fecha' => '2024-01-20',
                    'tipo' => 'recepcion',
                    'volumen' => 5000,
                    'producto' => 'Gasolina'
                ],
                [
                    'fecha' => '2024-01-19',
                    'tipo' => 'entrega',
                    'volumen' => 3000,
                    'producto' => 'Diesel'
                ]
            ]
        ];

        $tiempoReal = [
            'volumen_actual' => 12500,
            'flujo' => 150,
            'temperatura' => 22.5,
            'presion' => 1.2
        ];

        $this->mockSuccessfulResponse('/api/dashboard/resumen', $resumen);
        $this->mockSuccessfulResponse('/api/dashboard/tiempo-real', $tiempoReal);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
        $response->assertViewHas('resumen', $resumen);
        $response->assertViewHas('tiempoReal', $tiempoReal);
    }

    /** @test */
    public function test_index_handles_api_errors_gracefully()
    {
        $this->mockErrorResponse('/api/dashboard/resumen', 'Error al cargar resumen', 500);
        $this->mockErrorResponse('/api/dashboard/tiempo-real', 'Error al cargar datos', 500);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
        
        $resumen = $response->viewData('resumen');
        $this->assertEquals(0, $resumen['contribuyentes_activos']);
        $this->assertEquals(0, $resumen['instalaciones_activas']);
        $this->assertEquals(0, $resumen['alarmas_activas']);
        
        $tiempoReal = $response->viewData('tiempoReal');
        $this->assertEquals(0, $tiempoReal['volumen_actual']);
        $this->assertEquals(0, $tiempoReal['flujo']);
    }

    /** @test */
    public function test_grafica_movimientos_returns_chart_data()
    {
        $dias = 7;
        $graficaData = [
            'labels' => ['2024-01-14', '2024-01-15', '2024-01-16', '2024-01-17', '2024-01-18', '2024-01-19', '2024-01-20'],
            'entradas' => [5000, 4500, 6000, 5500, 4800, 5200, 5800],
            'salidas' => [3000, 2800, 3500, 3200, 3100, 3400, 3600]
        ];

        $this->mockSuccessfulResponse('/api/dashboard/grafica-movimientos', $graficaData);

        $response = $this->getJson('/api/dashboard/grafica-movimientos?dias=' . $dias);

        $response->assertStatus(200);
        $response->assertJson($graficaData);
        $response->assertJsonCount(7, 'labels');
        $response->assertJsonCount(7, 'entradas');
        $response->assertJsonCount(7, 'salidas');
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
            'salidas' => []
        ]);
    }

    /** @test */
    public function test_grafica_productos_returns_product_distribution()
    {
        $graficaData = [
            'labels' => ['Gasolina', 'Diesel', 'Turbosina', 'Gas LP'],
            'valores' => [45, 30, 15, 10]
        ];

        $this->mockSuccessfulResponse('/api/dashboard/grafica-productos', $graficaData);

        $response = $this->getJson('/api/dashboard/grafica-productos');

        $response->assertStatus(200);
        $response->assertJson($graficaData);
        $response->assertJsonCount(4, 'labels');
        $response->assertJsonCount(4, 'valores');
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
                'fecha' => '2024-01-20 10:30:00'
            ],
            [
                'id' => 2,
                'tipo' => 'MANTENIMIENTO',
                'titulo' => 'Calibración próxima',
                'mensaje' => 'El medidor MED-001 requiere calibración en 5 días',
                'leida' => true,
                'fecha' => '2024-01-19 15:00:00'
            ]
        ];

        $this->mockSuccessfulResponse('/api/notificaciones', $notificaciones);

        $response = $this->getJson('/api/notificaciones');

        $response->assertStatus(200);
        $response->assertJson($notificaciones);
        $response->assertJsonCount(2);
    }

    /** @test */
    public function test_exportar_downloads_dashboard_report()
    {
        $filters = [
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31',
            'tipo_reporte' => 'resumen'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/exportar/dashboard*' => Http::response(
                "fecha,tipo,volumen,producto\n2024-01-15,recepcion,5000,Gasolina\n2024-01-16,entrega,3000,Diesel",
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="dashboard_report.csv"'
                ]
            )
        ]);

        $response = $this->get('/dashboard/exportar?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="dashboard_report.csv"');
    }

    /** @test */
    public function test_dashboard_refreshes_data_periodically()
    {
        // Simular datos en tiempo real que cambian
        $this->mockSuccessfulResponse('/api/dashboard/tiempo-real', [
            'volumen_actual' => 12500,
            'flujo' => 150,
            'temperatura' => 22.5,
            'presion' => 1.2
        ]);

        $response1 = $this->get('/dashboard');
        $tiempoReal1 = $response1->viewData('tiempoReal');
        $this->assertEquals(12500, $tiempoReal1['volumen_actual']);

        // Cambiar datos para simular actualización
        $this->mockSuccessfulResponse('/api/dashboard/tiempo-real', [
            'volumen_actual' => 12300,
            'flujo' => 145,
            'temperatura' => 22.8,
            'presion' => 1.21
        ]);

        $response2 = $this->get('/dashboard');
        $tiempoReal2 = $response2->viewData('tiempoReal');
        $this->assertEquals(12300, $tiempoReal2['volumen_actual']);
    }

    /** @test */
    public function test_dashboard_shows_alerts_when_thresholds_exceeded()
    {
        $resumen = [
            'contribuyentes_activos' => 25,
            'instalaciones_activas' => 12,
            'alarmas_activas' => 5, // Umbral alto
            'volumen_total' => 150000,
            'alertas' => [
                'alarmas_criticas' => 2,
                'nivel_bajo_inventario' => 1,
                'mantenimiento_pendiente' => 3
            ],
            'ultimos_movimientos' => []
        ];

        $this->mockSuccessfulResponse('/api/dashboard/resumen', $resumen);

        $response = $this->get('/dashboard');
        
        $resumenData = $response->viewData('resumen');
        $this->assertArrayHasKey('alertas', $resumenData);
        $this->assertEquals(5, $resumenData['alarmas_activas']);
        $this->assertEquals(2, $resumenData['alertas']['alarmas_criticas']);
    }
}