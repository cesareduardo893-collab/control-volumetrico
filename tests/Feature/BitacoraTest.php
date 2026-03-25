<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BitacoraTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_bitacora_entries()
    {
        $eventos = [
            [
                'id' => 1,
                'usuario' => ['nombre' => 'Juan Pérez'],
                'tipo_evento' => 'LOGIN',
                'descripcion' => 'Inicio de sesión',
                'fecha_creacion' => '2024-01-20 10:00:00',
                'ip_address' => '192.168.1.1'
            ],
            [
                'id' => 2,
                'usuario' => ['nombre' => 'María López'],
                'tipo_evento' => 'ALARMA_ATENDIDA',
                'descripcion' => 'Atendió alarma #123',
                'fecha_creacion' => '2024-01-20 11:30:00',
                'ip_address' => '192.168.1.2'
            ]
        ];

        $this->mockSuccessfulResponse('/api/bitacora', [
            'data' => $eventos,
            'current_page' => 1,
            'from' => 1,
            'to' => 2,
            'per_page' => 10,
            'last_page' => 1,
            'total' => 2,
            'links' => []
        ]);

        $response = $this->get('/bitacora');

        $response->assertStatus(200);
        $response->assertViewIs('bitacora.index');
        $response->assertViewHas('eventos', $eventos);
        $response->assertViewHas('meta');
        $response->assertViewHas('links');
    }

    /** @test */
    public function test_show_returns_bitacora_entry_json()
    {
        $evento = [
            'id' => 1,
            'usuario_id' => 1,
            'tipo_evento' => 'LOGIN',
            'subtipo_evento' => null,
            'descripcion' => 'Inicio de sesión',
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
            'modulo' => 'Auth',
            'tabla' => null,
            'registro_id' => null,
            'datos_previos' => null,
            'datos_nuevos' => null,
            'created_at' => '2024-01-20 10:00:00'
        ];

        $this->mockSuccessfulResponse('/api/bitacora/1', $evento);

        $response = $this->getJson('/bitacora/1');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.id', 1);
        $response->assertJsonPath('data.tipo_evento', 'LOGIN');
    }

    /** @test */
    public function test_resumen_actividad_returns_activity_summary()
    {
        $filters = [
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        $resumen = [
            'total_eventos' => 150,
            'por_tipo' => [
                'LOGIN' => 50,
                'ALARMA_ATENDIDA' => 30,
                'CONTRIBUYENTE_CREADO' => 20,
                'USUARIO_ACTUALIZADO' => 50
            ],
            'por_dia' => [
                '2024-01-01' => 10,
                '2024-01-02' => 15,
                // ... más días
            ],
            'usuarios_activos' => [
                ['usuario_id' => 1, 'nombre' => 'Juan Pérez', 'actividades' => 45],
                ['usuario_id' => 2, 'nombre' => 'María López', 'actividades' => 30]
            ]
        ];

        $this->mockSuccessfulResponse('/api/bitacora/resumen-actividad', $resumen);

        $response = $this->getJson('/bitacora/resumen-actividad?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.total_eventos', 150);
    }

    /** @test */
    public function test_actividad_usuario_returns_user_activity()
    {
        $usuarioId = 1;
        $filters = [
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        $actividad = [
            'usuario' => [
                'id' => 1,
                'nombre' => 'Juan Pérez'
            ],
            'total_actividades' => 45,
            'actividades' => [
                [
                    'fecha' => '2024-01-20',
                    'tipo_evento' => 'LOGIN',
                    'descripcion' => 'Inicio de sesión',
                    'ip_address' => '192.168.1.1'
                ]
            ]
        ];

        $this->mockSuccessfulResponse("/api/bitacora/actividad-usuario/{$usuarioId}", $actividad);

        $response = $this->getJson("/bitacora/actividad-usuario/{$usuarioId}?" . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.usuario.id', $usuarioId);
        $response->assertJsonPath('data.total_actividades', 45);
    }

    /** @test */
    public function test_actividad_modulo_returns_module_activity()
    {
        $modulo = 'Alarmas';
        $filters = [
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        $actividad = [
            'modulo' => 'Alarmas',
            'total_actividades' => 30,
            'por_tipo' => [
                'ALARMA_CREADA' => 10,
                'ALARMA_ATENDIDA' => 15,
                'ALARMA_ACTUALIZADA' => 5
            ],
            'actividades' => [
                [
                    'fecha' => '2024-01-20',
                    'tipo_evento' => 'ALARMA_ATENDIDA',
                    'usuario' => 'Juan Pérez',
                    'descripcion' => 'Atendió alarma #123'
                ]
            ]
        ];

        $this->mockSuccessfulResponse("/api/bitacora/actividad-modulo/{$modulo}", $actividad);

        $response = $this->getJson("/bitacora/actividad-modulo/{$modulo}?" . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.modulo', 'Alarmas');
        $response->assertJsonPath('data.total_actividades', 30);
    }

    /** @test */
    public function test_actividad_tabla_returns_table_activity()
    {
        $tabla = 'contribuyentes';
        $registroId = 1;
        $filters = [
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        $actividad = [
            'tabla' => 'contribuyentes',
            'registro_id' => 1,
            'total_actividades' => 5,
            'actividades' => [
                [
                    'fecha' => '2024-01-15',
                    'tipo_evento' => 'CONTRIBUYENTE_ACTUALIZADO',
                    'usuario' => 'Juan Pérez',
                    'descripcion' => 'Actualizó información del contribuyente',
                    'datos_previos' => ['rfc' => 'XAXX010101XXX'],
                    'datos_nuevos' => ['rfc' => 'XAXX010101XXX', 'telefono' => '1234567890']
                ]
            ]
        ];

        $this->mockSuccessfulResponse("/api/bitacora/actividad-tabla/{$tabla}/{$registroId}", $actividad);

        $response = $this->getJson("/bitacora/actividad-tabla/{$tabla}/{$registroId}?" . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.tabla', 'contribuyentes');
        $response->assertJsonPath('data.registro_id', 1);
    }

    /** @test */
    public function test_actividad_tabla_returns_all_records_activity()
    {
        $tabla = 'contribuyentes';
        $filters = [
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        $actividad = [
            'tabla' => 'contribuyentes',
            'total_registros_afectados' => 3,
            'actividades' => [
                [
                    'registro_id' => 1,
                    'tipo_evento' => 'CONTRIBUYENTE_CREADO',
                    'fecha' => '2024-01-10',
                    'usuario' => 'Juan Pérez'
                ],
                [
                    'registro_id' => 2,
                    'tipo_evento' => 'CONTRIBUYENTE_ACTUALIZADO',
                    'fecha' => '2024-01-15',
                    'usuario' => 'María López'
                ]
            ]
        ];

        $this->mockSuccessfulResponse("/api/bitacora/actividad-tabla/{$tabla}", $actividad);

        $response = $this->getJson("/bitacora/actividad-tabla/{$tabla}?" . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.tabla', 'contribuyentes');
        $response->assertJsonPath('data.total_registros_afectados', 3);
    }

    /** @test */
    public function test_exportar_returns_csv_file()
    {
        $filters = [
            'formato' => 'CSV',
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/bitacora/exportar*' => Http::response(
                "fecha,tipo_evento,usuario,descripcion\n2024-01-20,LOGIN,Juan Pérez,Inicio de sesión",
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="bitacora.csv"'
                ]
            )
        ]);

        $response = $this->get('/bitacora/exportar?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="bitacora.csv"');
    }

    /** @test */
    public function test_exportar_returns_pdf_file()
    {
        $filters = [
            'formato' => 'PDF',
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-31'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/bitacora/exportar*' => Http::response(
                '%PDF-1.4...',
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="bitacora.pdf"'
                ]
            )
        ]);

        $response = $this->get('/bitacora/exportar?' . http_build_query($filters));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="bitacora.pdf"');
    }

    /** @test */
    public function test_exportar_validation_errors()
    {
        $response = $this->get('/bitacora/exportar');

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $response->assertJsonStructure(['errors' => ['formato', 'fecha_inicio', 'fecha_fin']]);
    }

    /** @test */
    public function test_filter_bitacora_by_tipo_evento()
    {
        $eventos = [
            [
                'id' => 1,
                'tipo_evento' => 'LOGIN',
                'descripcion' => 'Inicio de sesión',
                'fecha_creacion' => '2024-01-20 10:00:00'
            ]
        ];

        $this->mockSuccessfulResponse('/api/bitacora?tipo_evento=LOGIN', [
            'data' => $eventos,
            'total' => 1
        ]);

        $response = $this->get('/bitacora?tipo_evento=LOGIN');

        $response->assertStatus(200);
        $response->assertViewHas('eventos');
        
        $eventos = $response->viewData('eventos');
        $this->assertCount(1, $eventos);
        $this->assertEquals('LOGIN', $eventos[0]['tipo_evento']);
    }

    /** @test */
    public function test_filter_bitacora_by_date_range()
    {
        $eventos = [
            [
                'id' => 1,
                'fecha_creacion' => '2024-01-15',
                'descripcion' => 'Evento en rango'
            ]
        ];

        $this->mockSuccessfulResponse('/api/bitacora?fecha_inicio=2024-01-01&fecha_fin=2024-01-31', [
            'data' => $eventos,
            'total' => 1
        ]);

        $response = $this->get('/bitacora?fecha_inicio=2024-01-01&fecha_fin=2024-01-31');

        $response->assertStatus(200);
        $response->assertViewHas('eventos');
        
        $filters = $response->viewData('filters');
        $this->assertEquals('2024-01-01', $filters['fecha_inicio']);
        $this->assertEquals('2024-01-31', $filters['fecha_fin']);
    }
}