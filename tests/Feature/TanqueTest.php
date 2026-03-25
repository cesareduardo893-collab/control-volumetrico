<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TanqueTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_tanques_list()
    {
        $tanques = [
            $this->createTestTanqueData(['id' => 1, 'identificador' => 'TAN-001']),
            $this->createTestTanqueData(['id' => 2, 'identificador' => 'TAN-002'])
        ];

        $this->mockPaginatedResponse('/api/tanques', $tanques, 2);

        $response = $this->get('/tanques');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.index');
        $response->assertViewHas('tanques');
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $instalaciones = [
            ['id' => 1, 'nombre' => 'Instalación 1'],
            ['id' => 2, 'nombre' => 'Instalación 2']
        ];

        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina'],
            ['id' => 2, 'nombre' => 'Diesel']
        ];

        $this->mockSuccessfulResponse('/api/instalaciones?activo=true', $instalaciones);
        $this->mockSuccessfulResponse('/api/productos?activo=true', $productos);

        $response = $this->get('/tanques/create');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.create');
        $response->assertViewHas('instalaciones', $instalaciones);
        $response->assertViewHas('productos', $productos);
    }

    /** @test */
    public function test_store_creates_tanque_successfully()
    {
        $tanqueData = [
            'instalacion_id' => 1,
            'identificador' => 'TAN-001',
            'material' => 'Acero',
            'capacidad_total' => 10000,
            'capacidad_util' => 9500,
            'capacidad_operativa' => 9000,
            'capacidad_minima' => 500,
            'temperatura_referencia' => 20,
            'presion_referencia' => 1,
            'tipo_medicion' => 'estatica',
            'estado' => 'OPERATIVO'
        ];

        $createdTanque = array_merge($tanqueData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/tanques', $createdTanque, 'Tanque creado exitosamente', 201);

        $response = $this->post('/tanques', $tanqueData);

        $response->assertRedirect('/tanques/1');
        $response->assertSessionHas('success', 'Tanque creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors_for_invalid_capacities()
    {
        $invalidData = [
            'capacidad_util' => 12000, // Mayor que capacidad_total
            'capacidad_operativa' => 11000, // Mayor que capacidad_util
            'tipo_medicion' => 'INVALIDO'
        ];

        $this->mockValidationErrorResponse('/api/tanques', [
            'capacidad_util' => ['La capacidad util debe ser menor o igual a capacidad total'],
            'capacidad_operativa' => ['La capacidad operativa debe ser menor o igual a capacidad util'],
            'tipo_medicion' => ['El campo tipo medicion debe ser uno de: estatica, dinamica']
        ]);

        $response = $this->post('/tanques', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['capacidad_util', 'capacidad_operativa', 'tipo_medicion']);
    }

    /** @test */
    public function test_show_displays_tanque_details()
    {
        $tanque = $this->createTestTanqueData();

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);

        $response = $this->get('/tanques/1');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.show');
        $response->assertViewHas('tanque', $tanque);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $tanque = $this->createTestTanqueData();
        $productos = [
            ['id' => 1, 'nombre' => 'Gasolina'],
            ['id' => 2, 'nombre' => 'Diesel']
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', $tanque);
        $this->mockSuccessfulResponse('/api/productos?activo=true', $productos);

        $response = $this->get('/tanques/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.edit');
        $response->assertViewHas('tanque', $tanque);
        $response->assertViewHas('productos', $productos);
    }

    /** @test */
    public function test_update_modifies_tanque_successfully()
    {
        $updateData = [
            'producto_id' => 2,
            'estado' => 'MANTENIMIENTO',
            'observaciones' => 'Mantenimiento programado'
        ];

        $this->mockSuccessfulResponse('/api/tanques/1', [], 'Tanque actualizado exitosamente');

        $response = $this->put('/tanques/1', $updateData);

        $response->assertRedirect('/tanques/1');
        $response->assertSessionHas('success', 'Tanque actualizado exitosamente');
    }

    /** @test */
    public function test_registrar_calibracion_registers_calibration_successfully()
    {
        $calibrationData = [
            'fecha_calibracion' => '2024-01-15',
            'fecha_proxima_calibracion' => '2024-07-15',
            'certificado_calibracion' => 'CERT-001',
            'entidad_calibracion' => 'Laboratorio Test',
            'tabla_aforo' => [
                ['nivel' => 10, 'volumen' => 500],
                ['nivel' => 20, 'volumen' => 1000]
            ]
        ];

        $this->mockSuccessfulResponse('/api/tanques/1/calibrar', [], 'Calibración registrada exitosamente');

        $response = $this->post('/tanques/1/calibrar', $calibrationData);

        $response->assertRedirect('/tanques/1');
        $response->assertSessionHas('success', 'Calibración registrada exitosamente');
    }

    /** @test */
    public function test_verificar_estado_checks_tank_status()
    {
        $estado = [
            'estado' => 'OPERATIVO',
            'nivel_actual' => 4500,
            'capacidad_disponible' => 5000,
            'temperatura' => 22.5,
            'presion' => 1.2,
            'ultima_calibracion' => '2024-01-15',
            'proxima_calibracion' => '2024-07-15'
        ];

        $this->mockSuccessfulResponse('/api/tanques/1/verificar-estado', $estado);

        $response = $this->get('/tanques/1/verificar-estado');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.estado');
        $response->assertViewHas('estado', $estado);
    }

    /** @test */
    public function test_cambiar_producto_changes_tank_product()
    {
        $changeData = [
            'producto_id' => 2,
            'motivo' => 'Cambio de producto por nueva recepción',
            'fecha_cambio' => '2024-01-20'
        ];

        $this->mockSuccessfulResponse('/api/tanques/1/cambiar-producto', [], 'Producto cambiado exitosamente');

        $response = $this->post('/tanques/1/cambiar-producto', $changeData);

        $response->assertRedirect('/tanques/1');
        $response->assertSessionHas('success', 'Producto cambiado exitosamente');
    }

    /** @test */
    public function test_curva_calibracion_displays_calibration_curve()
    {
        $curva = [
            'tanque_id' => 1,
            'puntos' => [
                ['nivel' => 0, 'volumen' => 0],
                ['nivel' => 100, 'volumen' => 5000],
                ['nivel' => 200, 'volumen' => 10000]
            ],
            'ultima_actualizacion' => '2024-01-15'
        ];

        $this->mockSuccessfulResponse('/api/tanques/1/curva-calibracion', $curva);

        $response = $this->get('/tanques/1/curva-calibracion');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.curva-calibracion');
        $response->assertViewHas('curva', $curva);
    }

    /** @test */
    public function test_historial_calibraciones_displays_calibration_history()
    {
        $historial = [
            [
                'fecha_calibracion' => '2024-01-15',
                'certificado' => 'CERT-001',
                'entidad' => 'Laboratorio A',
                'precision' => 0.99
            ],
            [
                'fecha_calibracion' => '2023-07-15',
                'certificado' => 'CERT-002',
                'entidad' => 'Laboratorio B',
                'precision' => 0.98
            ]
        ];

        $this->mockSuccessfulResponse('/api/tanques/1/historial-calibraciones', $historial);

        $response = $this->get('/tanques/1/historial-calibraciones');

        $response->assertStatus(200);
        $response->assertViewIs('tanques.historial-calibraciones');
        $response->assertViewHas('historial', $historial);
    }

    /** @test */
    public function test_destroy_deletes_tanque_successfully()
    {
        $this->mockSuccessfulResponse('/api/tanques/1', [], 'Tanque eliminado exitosamente');

        $response = $this->delete('/tanques/1');

        $response->assertRedirect('/tanques');
        $response->assertSessionHas('success', 'Tanque eliminado exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_tanque_has_related_records()
    {
        $this->mockErrorResponse('/api/tanques/1', 'No se puede eliminar el tanque', 409);

        $response = $this->delete('/tanques/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar el tanque');
    }

    /** @test */
    public function test_filter_tanques_by_estado()
    {
        $tanques = [
            $this->createTestTanqueData(['id' => 1, 'estado' => 'OPERATIVO'])
        ];

        $this->mockSuccessfulResponse('/api/tanques?estado=OPERATIVO', ['data' => $tanques]);

        $response = $this->get('/tanques?estado=OPERATIVO');

        $response->assertStatus(200);
        $response->assertViewHas('tanques');
        
        $tanques = $response->viewData('tanques');
        $this->assertCount(1, $tanques);
        $this->assertEquals('OPERATIVO', $tanques[0]['estado']);
    }
}