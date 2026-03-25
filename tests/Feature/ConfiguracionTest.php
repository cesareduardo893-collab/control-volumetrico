<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ConfiguracionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_configuration()
    {
        $configuracion = [
            'nombre_sistema' => 'Sistema de Control Volumétrico',
            'version_sistema' => '1.0.0',
            'empresa' => 'Empresa Prueba SA de CV',
            'direccion' => 'Calle Principal 123',
            'telefono' => '1234567890',
            'email' => 'contacto@empresa.com',
            'maximo_registros' => 100,
            'tiempo_sesion' => 60,
            'auditoria_activa' => true,
            'backup_automatico' => true,
            'notificaciones_activas' => true
        ];

        $this->mockSuccessfulResponse('/api/configuracion', $configuracion);

        $response = $this->get('/configuracion');

        $response->assertStatus(200);
        $response->assertViewIs('configuracion.index');
        $response->assertViewHas('configuracion', $configuracion);
    }

    /** @test */
    public function test_update_modifies_configuration_successfully()
    {
        $updateData = [
            'nombre_sistema' => 'Sistema Actualizado',
            'version_sistema' => '1.1.0',
            'maximo_registros' => 200,
            'tiempo_sesion' => 120,
            'auditoria_activa' => true,
            'backup_automatico' => false,
            'notificaciones_activas' => true,
            'email_notificaciones' => 'admin@empresa.com',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls'
        ];

        $this->mockSuccessfulResponse('/api/configuracion', [], 'Configuración actualizada exitosamente');

        $response = $this->put('/configuracion', $updateData);

        $response->assertRedirect('/configuracion');
        $response->assertSessionHas('success', 'Configuración actualizada exitosamente');
    }

    /** @test */
    public function test_update_validation_errors()
    {
        $invalidData = [
            'maximo_registros' => 0,
            'tiempo_sesion' => 0,
            'smtp_port' => 100000
        ];

        $this->mockValidationErrorResponse('/api/configuracion', [
            'maximo_registros' => ['El maximo registros debe ser al menos 1'],
            'tiempo_sesion' => ['El tiempo sesion debe ser al menos 1'],
            'smtp_port' => ['El smtp port debe ser un número entre 1 y 65535']
        ]);

        $response = $this->put('/configuracion', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['maximo_registros', 'tiempo_sesion', 'smtp_port']);
    }

    /** @test */
    public function test_backup_manual_triggers_backup()
    {
        $this->mockSuccessfulResponse('/api/configuracion/backup-manual', [
            'archivo' => 'backup_20240120_103000.sql',
            'tamaño' => '2.5 MB',
            'ruta' => '/backups/backup_20240120_103000.sql'
        ], 'Backup manual realizado exitosamente');

        $response = $this->post('/configuracion/backup-manual');

        $response->assertRedirect('/configuracion');
        $response->assertSessionHas('success', 'Backup manual realizado exitosamente');
    }

    /** @test */
    public function test_limpiar_cache_clears_cache()
    {
        $this->mockSuccessfulResponse('/api/configuracion/limpiar-cache', [], 'Cache limpiada exitosamente');

        $response = $this->post('/configuracion/limpiar-cache');

        $response->assertRedirect('/configuracion');
        $response->assertSessionHas('success', 'Cache limpiada exitosamente');
    }

    /** @test */
    public function test_logs_displays_system_logs()
    {
        $logs = [
            [
                'fecha' => '2024-01-20 10:00:00',
                'nivel' => 'INFO',
                'mensaje' => 'Usuario inició sesión',
                'contexto' => ['user_id' => 1]
            ],
            [
                'fecha' => '2024-01-20 09:30:00',
                'nivel' => 'ERROR',
                'mensaje' => 'Error al conectar con API',
                'contexto' => ['endpoint' => '/api/alarmas']
            ]
        ];

        $this->mockSuccessfulResponse('/api/configuracion/logs', $logs);

        $response = $this->get('/configuracion/logs');

        $response->assertStatus(200);
        $response->assertViewIs('configuracion.logs');
        $response->assertViewHas('logs', $logs);
    }

    /** @test */
    public function test_exportar_downloads_configuration_file()
    {
        Http::fake([
            $this->baseApiUrl . '/api/configuracion/exportar' => Http::response(
                json_encode([
                    'nombre_sistema' => 'Sistema de Control',
                    'version' => '1.0.0',
                    'configuraciones' => []
                ]),
                200,
                [
                    'Content-Type' => 'application/json',
                    'Content-Disposition' => 'attachment; filename="configuracion-2024-01-20.json"'
                ]
            )
        ]);

        $response = $this->get('/configuracion/exportar');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition', 'attachment; filename="configuracion-2024-01-20.json"');
    }

    /** @test */
    public function test_importar_uploads_configuration_file()
    {
        Storage::fake('local');

        $configFile = UploadedFile::fake()->createWithContent(
            'configuracion.json',
            json_encode([
                'nombre_sistema' => 'Sistema Importado',
                'version_sistema' => '2.0.0',
                'maximo_registros' => 500
            ])
        );

        $this->mockSuccessfulResponse('/api/configuracion/importar', [], 'Configuración importada exitosamente');

        $response = $this->post('/configuracion/importar', [
            'config_file' => $configFile
        ]);

        $response->assertRedirect('/configuracion');
        $response->assertSessionHas('success', 'Configuración importada exitosamente');
    }

    /** @test */
    public function test_importar_validation_error_for_invalid_file()
    {
        $invalidFile = UploadedFile::fake()->create('documento.pdf', 100);

        $response = $this->post('/configuracion/importar', [
            'config_file' => $invalidFile
        ]);

        $response->assertSessionHasErrors(['config_file']);
    }

    /** @test */
    public function test_importar_validation_error_for_invalid_json()
    {
        $invalidJsonFile = UploadedFile::fake()->createWithContent(
            'configuracion.json',
            '{invalid json content'
        );

        $this->mockErrorResponse('/api/configuracion/importar', 'Archivo JSON inválido', 422);

        $response = $this->post('/configuracion/importar', [
            'config_file' => $invalidJsonFile
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Archivo JSON inválido');
    }

    /** @test */
    public function test_configuration_has_sensitive_fields_encrypted()
    {
        $configuracion = [
            'nombre_sistema' => 'Sistema de Control',
            'smtp_user' => 'admin@empresa.com',
            'smtp_pass' => '********', // Debería estar oculto
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587
        ];

        $this->mockSuccessfulResponse('/api/configuracion', $configuracion);

        $response = $this->get('/configuracion');
        
        $config = $response->viewData('configuracion');
        $this->assertStringContainsString('********', $config['smtp_pass']);
        $this->assertNotEquals('password_real', $config['smtp_pass']);
    }
}