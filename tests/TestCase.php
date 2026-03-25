<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{

    protected $baseApiUrl = 'http://localhost:8000';
    protected $testApiToken = 'test-token-12345';
    protected $testUser = [
        'id' => 1,
        'full_name' => 'Test User',
        'email' => 'test@example.com',
        'roles' => ['admin'],
        'force_password_change' => false
    ];

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Configurar entorno de pruebas
        config(['app.env' => 'testing']);
        config(['services.api.url' => $this->baseApiUrl]);

        // Limpiar sesión antes de cada prueba
        Session::flush();

        // Mockear respuestas HTTP por defecto
        $this->mockApiResponses();
    }

    /**
     * Mockear respuestas de la API
     */
    protected function mockApiResponses(): void
    {
        Http::fake([
            // Mock para login
            $this->baseApiUrl . '/api/login' => Http::response([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'token' => $this->testApiToken,
                    'user' => $this->testUser
                ]
            ], 200),

            // Mock para logout
            $this->baseApiUrl . '/api/logout' => Http::response([
                'success' => true,
                'message' => 'Logout exitoso'
            ], 200),

            // Mock para obtener usuario
            $this->baseApiUrl . '/api/user' => Http::response([
                'success' => true,
                'data' => $this->testUser
            ], 200),

            // Mock por defecto para otras rutas
            '*' => Http::response([
                'success' => true,
                'data' => []
            ], 200),
        ]);
    }

    /**
     * Autenticar usuario para pruebas
     */
    protected function authenticateUser(): void
    {
        Session::put('api_token', $this->testApiToken);
        Session::put('user_id', $this->testUser['id']);
        Session::put('user_name', $this->testUser['full_name']);
        Session::put('user_email', $this->testUser['email']);
        Session::put('user_roles', $this->testUser['roles']);
    }

    /**
     * Mockear respuesta exitosa de API
     */
    protected function mockSuccessfulResponse(string $endpoint, array $data = [], string $message = 'Success'): void
    {
        Http::fake([
            $this->baseApiUrl . $endpoint => Http::response([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], 200)
        ]);
    }

    /**
     * Mockear respuesta de error de API
     */
    protected function mockErrorResponse(string $endpoint, string $message = 'Error', int $status = 400, array $errors = []): void
    {
        Http::fake([
            $this->baseApiUrl . $endpoint => Http::response([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], $status)
        ]);
    }

    /**
     * Mockear respuesta de validación
     */
    protected function mockValidationErrorResponse(string $endpoint, array $errors): void
    {
        Http::fake([
            $this->baseApiUrl . $endpoint => Http::response([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $errors
            ], 422)
        ]);
    }

    /**
     * Mockear respuesta paginada
     */
    protected function mockPaginatedResponse(string $endpoint, array $data, int $total = 10, int $perPage = 10): void
    {
        $response = [
            'success' => true,
            'data' => $data,
            'current_page' => 1,
            'from' => 1,
            'to' => count($data),
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage),
            'total' => $total,
            'links' => []
        ];

        Http::fake([
            $this->baseApiUrl . $endpoint => Http::response($response, 200)
        ]);
    }

    /**
     * Crear datos de prueba para alarma
     */
    protected function createTestAlarmData(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
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
        ], $overrides);
    }

    /**
     * Crear datos de prueba para contribuyente
     */
    protected function createTestContribuyenteData(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
            'rfc' => 'XAXX010101XXX',
            'razon_social' => 'Empresa Prueba SA de CV',
            'nombre_comercial' => 'Prueba',
            'regimen_fiscal' => 'General',
            'domicilio_fiscal' => 'Calle Principal 123',
            'codigo_postal' => '12345',
            'activo' => true,
        ], $overrides);
    }

    /**
     * Crear datos de prueba para instalación
     */
    protected function createTestInstalacionData(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
            'contribuyente_id' => 1,
            'clave_instalacion' => 'INST-001',
            'nombre' => 'Instalación de Prueba',
            'tipo_instalacion' => 'Almacenamiento',
            'domicilio' => 'Calle Secundaria 456',
            'codigo_postal' => '54321',
            'municipio' => 'Municipio Test',
            'estado' => 'Estado Test',
            'estatus' => 'OPERACION',
            'activo' => true,
        ], $overrides);
    }

    /**
     * Crear datos de prueba para tanque
     */
    protected function createTestTanqueData(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
            'instalacion_id' => 1,
            'identificador' => 'TAN-001',
            'capacidad_total' => 10000,
            'capacidad_util' => 9500,
            'estado' => 'OPERATIVO',
            'activo' => true,
        ], $overrides);
    }
}