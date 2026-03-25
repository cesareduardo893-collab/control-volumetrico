<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MedidorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_medidores_list()
    {
        $medidores = [
            [
                'id' => 1,
                'numero_serie' => 'MED-001',
                'clave' => 'M-001',
                'tipo_medicion' => 'estatica',
                'estado' => 'OPERATIVO'
            ],
            [
                'id' => 2,
                'numero_serie' => 'MED-002',
                'clave' => 'M-002',
                'tipo_medicion' => 'dinamica',
                'estado' => 'CALIBRACION'
            ]
        ];

        $this->mockPaginatedResponse('/api/medidores', $medidores, 2);

        $response = $this->get('/medidores');

        $response->assertStatus(200);
        $response->assertViewIs('medidores.index');
        $response->assertViewHas('medidores');
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2']
        ];

        $tanques = [
            ['id' => 1, 'identificador' => 'TAN-001'],
            ['id' => 2, 'identificador' => 'TAN-002']
        ];

        $this->mockSuccessfulResponse('/api/instalaciones?activo=true', $instalaciones);
        $this->mockSuccessfulResponse('/api/tanques?activo=true', $tanques);

        $response = $this->get('/medidores/create');

        $response->assertStatus(200);
        $response->assertViewIs('medidores.create');
        $response->assertViewHas('instalaciones', $instalaciones);
        $response->assertViewHas('tanques', $tanques);
    }

    /** @test */
    public function test_store_creates_medidor_successfully()
    {
        $medidorData = [
            'tanque_id' => 1,
            'instalacion_id' => 1,
            'numero_serie' => 'MED-001',
            'clave' => 'M-001',
            'modelo' => 'Modelo X',
            'fabricante' => 'Fabricante Test',
            'elemento_tipo' => 'primario',
            'tipo_medicion' => 'estatica',
            'precision' => 0.995,
            'capacidad_maxima' => 1000,
            'estado' => 'OPERATIVO',
            'tecnologia_id' => 'TECH-001',
            'protocolo_comunicacion' => 'Modbus',
            'presion_maxima' => 10,
            'temperatura_maxima' => 50
        ];

        $createdMedidor = array_merge($medidorData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/medidores', $createdMedidor, 'Medidor creado exitosamente', 201);

        $response = $this->post('/medidores', $medidorData);

        $response->assertRedirect('/medidores');
        $response->assertSessionHas('success', 'Medidor creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'elemento_tipo' => 'INVALIDO',
            'tipo_medicion' => 'INVALIDO',
            'estado' => 'INVALIDO'
        ];

        $this->mockValidationErrorResponse('/api/medidores', [
            'elemento_tipo' => ['El campo elemento tipo debe ser uno de: primario, secundario, terciario'],
            'tipo_medicion' => ['El campo tipo medicion debe ser uno de: estatica, dinamica'],
            'estado' => ['El campo estado debe ser uno de: OPERATIVO, CALIBRACION, MANTENIMIENTO, FUERA_SERVICIO, FALLA_COMUNICACION']
        ]);

        $response = $this->post('/medidores', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['elemento_tipo', 'tipo_medicion', 'estado']);
    }

    /** @test */
    public function test_show_displays_medidor_details()
    {
        $medidor = [
            'id' => 1,
            'numero_serie' => 'MED-001',
            'clave' => 'M-001',
            'instalacion' => ['nombre' => 'Instalación 1'],
            'tanque' => ['identificador' => 'TAN-001'],
            'estado' => 'OPERATIVO',
            'ultima_calibracion' => '2024-01-15',
            'proxima_calibracion' => '2024-07-15'
        ];

        $this->mockSuccessfulResponse('/api/medidores/1', $medidor);

        $response = $this->get('/medidores/1');

        $response->assertStatus(200);
        $response->assertViewIs('medidores.show');
        $response->assertViewHas('medidor', $medidor);
    }

    /** @test */
    public function test_registrar_calibracion_registers_calibration()
    {
        $calibrationData = [
            'fecha_calibracion' => '2024-01-15',
            'fecha_proxima_calibracion' => '2024-07-15',
            'certificado_calibracion' => 'CERT-001',
            'laboratorio_calibracion' => 'Laboratorio Test',
            'precision' => 0.998
        ];

        $this->mockSuccessfulResponse('/api/medidores/1/calibrar', [], 'Calibración registrada exitosamente');

        $response = $this->post('/medidores/1/calibrar', $calibrationData);

        $response->assertRedirect('/medidores/1');
        $response->assertSessionHas('success', 'Calibración registrada exitosamente');
    }

    /** @test */
    public function test_probar_comunicacion_tests_communication()
    {
        $resultado = [
            'success' => true,
            'tiempo_respuesta' => 150,
            'mensaje' => 'Comunicación exitosa'
        ];

        $this->mockSuccessfulResponse('/api/medidores/1/probar-comunicacion', $resultado);

        $response = $this->get('/medidores/1/probar-comunicacion');

        $response->assertRedirect('/medidores/1');
        $response->assertSessionHas('success', 'Prueba de comunicación realizada');
    }

    /** @test */
    public function test_probar_comunicacion_fails_when_offline()
    {
        $this->mockErrorResponse('/api/medidores/1/probar-comunicacion', 'Error de comunicación', 500);

        $response = $this->get('/medidores/1/probar-comunicacion');

        $response->assertRedirect('/medidores/1');
        $response->assertSessionHas('error', 'Error de comunicación');
    }

    /** @test */
    public function test_verificar_estado_checks_meter_status()
    {
        $estado = [
            'estado' => 'OPERATIVO',
            'conectado' => true,
            'ultima_lectura' => '2024-01-20 10:30:00',
            'lectura_actual' => 1234.56,
            'precision_actual' => 0.997,
            'alertas' => []
        ];

        $this->mockSuccessfulResponse('/api/medidores/1/verificar-estado', $estado);

        $response = $this->get('/medidores/1/verificar-estado');

        $response->assertStatus(200);
        $response->assertViewIs('medidores.estado');
        $response->assertViewHas('estado', $estado);
    }

    /** @test */
    public function test_historial_calibraciones_displays_calibration_history()
    {
        $historial = [
            [
                'fecha_calibracion' => '2024-01-15',
                'certificado' => 'CERT-001',
                'laboratorio' => 'Lab A',
                'precision' => 0.998
            ],
            [
                'fecha_calibracion' => '2023-07-15',
                'certificado' => 'CERT-002',
                'laboratorio' => 'Lab B',
                'precision' => 0.997
            ]
        ];

        $this->mockSuccessfulResponse('/api/medidores/1/historial-calibraciones', $historial);

        $response = $this->get('/medidores/1/historial-calibraciones');

        $response->assertStatus(200);
        $response->assertViewIs('medidores.historial-calibraciones');
        $response->assertViewHas('historial', $historial);
    }

    /** @test */
    public function test_update_modifies_medidor_successfully()
    {
        $updateData = [
            'estado' => 'MANTENIMIENTO',
            'observaciones' => 'Mantenimiento preventivo'
        ];

        $this->mockSuccessfulResponse('/api/medidores/1', [], 'Medidor actualizado exitosamente');

        $response = $this->put('/medidores/1', $updateData);

        $response->assertRedirect('/medidores/1');
        $response->assertSessionHas('success', 'Medidor actualizado exitosamente');
    }

    /** @test */
    public function test_destroy_deletes_medidor_successfully()
    {
        $this->mockSuccessfulResponse('/api/medidores/1', [], 'Medidor eliminado exitosamente');

        $response = $this->delete('/medidores/1');

        $response->assertRedirect('/medidores');
        $response->assertSessionHas('success', 'Medidor eliminado exitosamente');
    }

    /** @test */
    public function test_filter_medidores_by_estado()
    {
        $medidores = [
            ['id' => 1, 'estado' => 'OPERATIVO', 'numero_serie' => 'MED-001']
        ];

        $this->mockSuccessfulResponse('/api/medidores?estado=OPERATIVO', ['data' => $medidores]);

        $response = $this->get('/medidores?estado=OPERATIVO');

        $response->assertStatus(200);
        $response->assertViewHas('medidores');
        
        $medidores = $response->viewData('medidores');
        $this->assertCount(1, $medidores);
        $this->assertEquals('OPERATIVO', $medidores[0]['estado']);
    }
}