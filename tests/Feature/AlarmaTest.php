<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AlarmaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_alarm_list()
    {
        $alarmas = [
            [
                'id' => 1,
                'numero_registro' => 'ALM-001',
                'gravedad' => 'ALTA',
                'estado_atencion' => 'PENDIENTE',
                'descripcion' => 'Alarma de prueba'
            ],
            [
                'id' => 2,
                'numero_registro' => 'ALM-002',
                'gravedad' => 'MEDIA',
                'estado_atencion' => 'EN_PROCESO',
                'descripcion' => 'Alarma de prueba 2'
            ]
        ];

        $this->mockPaginatedResponse('/api/alarmas', $alarmas, 2);

        $response = $this->get('/alarmas');

        $response->assertStatus(200);
        $response->assertViewIs('alarmas.index');
        $response->assertViewHas('alarmas');
    }

    /** @test */
    public function test_show_displays_alarm_details()
    {
        $alarma = $this->createTestAlarmData();

        $this->mockSuccessfulResponse('/api/alarmas/1', $alarma);

        $response = $this->get('/alarmas/1');

        $response->assertStatus(200);
        $response->assertViewIs('alarmas.show');
        $response->assertViewHas('alarma', $alarma);
    }

    /** @test */
    public function test_show_returns_404_for_nonexistent_alarm()
    {
        $this->mockErrorResponse('/api/alarmas/999', 'Alarma no encontrada', 404);

        $response = $this->get('/alarmas/999');

        $response->assertRedirect('/alarmas');
        $response->assertSessionHas('error', 'Alarma no encontrada');
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $tiposAlarma = [
            ['id' => 1, 'nombre' => 'Nivel Alto'],
            ['id' => 2, 'nombre' => 'Nivel Bajo']
        ];

        $this->mockSuccessfulResponse('/api/catalogos?tipo=tipo_alarma', $tiposAlarma);

        $response = $this->get('/alarmas/create');

        $response->assertStatus(200);
        $response->assertViewIs('alarmas.create');
        $response->assertViewHas('tiposAlarma', $tiposAlarma);
    }

    /** @test */
    public function test_create_uses_default_tipos_when_catalog_fails()
    {
        $this->mockErrorResponse('/api/catalogos?tipo=tipo_alarma', 'Error', 500);

        $response = $this->get('/alarmas/create');

        $response->assertStatus(200);
        $response->assertViewIs('alarmas.create');
        
        $tiposAlarma = $response->viewData('tiposAlarma');
        $this->assertNotEmpty($tiposAlarma);
        $this->assertEquals('Nivel Alto', $tiposAlarma[0]['nombre']);
    }

    /** @test */
    public function test_store_creates_alarm_successfully()
    {
        $alarmData = [
            'numero_registro' => 'ALM-001',
            'fecha_hora' => now()->toDateTimeString(),
            'componente_tipo' => 'Tanque',
            'componente_id' => 1,
            'componente_identificador' => 'TAN-001',
            'tipo_alarma_id' => 1,
            'gravedad' => 'ALTA',
            'descripcion' => 'Alarma de prueba',
            'estado_atencion' => 'PENDIENTE',
            'requiere_atencion_inmediata' => true,
        ];

        $createdAlarm = array_merge($alarmData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/alarmas', $createdAlarm, 'Alarma creada exitosamente', 201);

        $response = $this->post('/alarmas', $alarmData);

        $response->assertRedirect('/alarmas/1');
        $response->assertSessionHas('success', 'Alarma creada exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'numero_registro' => '',
            'gravedad' => 'INVALIDO'
        ];

        $this->mockValidationErrorResponse('/api/alarmas', [
            'numero_registro' => ['El campo numero_registro es obligatorio'],
            'gravedad' => ['El campo gravedad debe ser uno de: BAJA, MEDIA, ALTA, CRITICA']
        ]);

        $response = $this->post('/alarmas', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['numero_registro', 'gravedad']);
    }

    /** @test */
    public function test_atender_updates_alarm_successfully()
    {
        $atenderData = [
            'acciones_tomadas' => 'Se verificó el tanque y se corrigió la fuga',
            'estado_atencion' => 'RESUELTA',
            'observaciones' => 'Todo en orden'
        ];

        $this->mockSuccessfulResponse('/api/alarmas/1/atender', [], 'Alarma atendida exitosamente');

        $response = $this->post('/alarmas/1/atender', $atenderData);

        $response->assertRedirect('/alarmas/1');
        $response->assertSessionHas('success', 'Alarma atendida exitosamente');
    }

    /** @test */
    public function test_atender_fails_when_alarm_already_attended()
    {
        $atenderData = [
            'acciones_tomadas' => 'Prueba',
            'estado_atencion' => 'RESUELTA'
        ];

        $this->mockErrorResponse('/api/alarmas/1/atender', 'La alarma ya ha sido atendida', 403);

        $response = $this->post('/alarmas/1/atender', $atenderData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'La alarma ya ha sido atendida');
    }

    /** @test */
    public function test_estadisticas_displays_statistics()
    {
        $filters = [
            'instalacion_id' => 1,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31'
        ];

        $estadisticas = [
            'total_alarmas' => 100,
            'por_gravedad' => [
                'ALTA' => 30,
                'MEDIA' => 40,
                'BAJA' => 30
            ],
            'por_estado' => [
                'PENDIENTE' => 20,
                'EN_PROCESO' => 30,
                'RESUELTA' => 50
            ]
        ];

        $this->mockSuccessfulResponse('/api/alarmas/estadisticas', $estadisticas);

        $response = $this->get('/alarmas/estadisticas?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertViewIs('alarmas.estadisticas');
        $response->assertViewHas('estadisticas', $estadisticas);
    }

    /** @test */
    public function test_activas_displays_active_alarms()
    {
        $activeAlarms = [
            ['id' => 1, 'numero_registro' => 'ALM-001', 'gravedad' => 'ALTA'],
            ['id' => 2, 'numero_registro' => 'ALM-002', 'gravedad' => 'CRITICA']
        ];

        $this->mockSuccessfulResponse('/api/alarmas/activas', $activeAlarms);

        $response = $this->get('/alarmas/activas/list');

        $response->assertStatus(200);
        $response->assertViewIs('alarmas.activas');
        $response->assertViewHas('alarmas', $activeAlarms);
    }

    /** @test */
    public function test_update_modifies_alarm_successfully()
    {
        $updateData = [
            'descripcion' => 'Descripción actualizada',
            'gravedad' => 'CRITICA'
        ];

        $this->mockSuccessfulResponse('/api/alarmas/1', [], 'Alarma actualizada exitosamente');

        $response = $this->put('/alarmas/1', $updateData);

        $response->assertRedirect('/alarmas/1');
        $response->assertSessionHas('success', 'Alarma actualizada exitosamente');
    }

    /** @test */
    public function test_destroy_deletes_alarm_successfully()
    {
        $this->mockSuccessfulResponse('/api/alarmas/1', [], 'Alarma eliminada exitosamente');

        $response = $this->delete('/alarmas/1');

        $response->assertRedirect('/alarmas');
        $response->assertSessionHas('success', 'Alarma eliminada exitosamente');
    }

    /** @test */
    public function test_exportar_downloads_file()
    {
        $this->mockSuccessfulResponse('/api/exportar/alarmas', [], 'Exportación exitosa');

        Http::fake([
            $this->baseApiUrl . '/api/exportar/alarmas*' => Http::response(
                'csv content here',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="alarmas.csv"'
                ]
            )
        ]);

        $response = $this->get('/alarmas/exportar');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="alarmas.csv"');
    }
}