<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

abstract class TestCase extends BaseTestCase
{
    protected $baseApiUrl = 'http://localhost:8000';

    protected $testApiToken = 'test-token-12345';

    protected $testUser = [
        'id' => 1,
        'full_name' => 'Test User',
        'email' => 'test@example.com',
        'roles' => ['Administrador'],
        'force_password_change' => false,
    ];

    protected array $httpFakes = [];

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.env' => 'testing']);
        config(['services.api.url' => $this->baseApiUrl]);
        Session::flush();
        $this->httpFakes = [];
    }

    protected function tearDown(): void
    {
        Http::preventStrayRequests(false);
        Http::fake([]);
        $this->httpFakes = [];
        parent::tearDown();
    }

    protected function authenticateUser(): void
    {
        Session::put('api_token', $this->testApiToken);
        Session::put('user_id', $this->testUser['id']);
        Session::put('user_name', $this->testUser['full_name']);
        Session::put('user_email', $this->testUser['email']);
        Session::put('user_roles', $this->testUser['roles']);
    }

    protected function applyHttpFakes(): void
    {
        $fakes = $this->httpFakes;
        $fakes['*'] = Http::response(['success' => true, 'data' => []], 200);

        // Reset stub callbacks to prevent accumulation from previous Http::fake() calls
        $reflection = new \ReflectionClass(Http::getFacadeRoot());
        $prop = $reflection->getProperty('stubCallbacks');
        $prop->setAccessible(true);
        $prop->setValue(Http::getFacadeRoot(), new \Illuminate\Support\Collection);

        Http::fake($fakes);
    }

    protected function mockSuccessfulResponse(string $endpoint, array $data = [], string $message = 'Success', int $status = 200): void
    {
        $this->httpFakes[$this->baseApiUrl.$endpoint.'*'] = Http::response([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
        $this->applyHttpFakes();
    }

    protected function mockErrorResponse(string $endpoint, string $message = 'Error', int $status = 400, array $errors = []): void
    {
        $this->httpFakes[$this->baseApiUrl.$endpoint.'*'] = Http::response([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
        $this->applyHttpFakes();
    }

    protected function mockValidationErrorResponse(string $endpoint, array $errors): void
    {
        $this->httpFakes[$this->baseApiUrl.$endpoint.'*'] = Http::response([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $errors,
        ], 422);
        $this->applyHttpFakes();
    }

    protected function mockPaginatedResponse(string $endpoint, array $data, int $total = 10, int $perPage = 10): void
    {
        $this->httpFakes[$this->baseApiUrl.$endpoint.'*'] = Http::response([
            'success' => true,
            'data' => [
                'data' => $data,
                'current_page' => 1,
                'from' => 1,
                'to' => count($data),
                'per_page' => $perPage,
                'last_page' => ceil($total / $perPage),
                'total' => $total,
                'links' => [],
            ],
        ], 200);
        $this->applyHttpFakes();
    }

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
            'tipo_alarma' => ['id' => 1, 'nombre' => 'Nivel Alto'],
            'gravedad' => 'ALTA',
            'descripcion' => 'Alarma de prueba',
            'estado_atencion' => 'PENDIENTE',
            'requiere_atencion_inmediata' => true,
            'atendida' => false,
            'atenciones' => [],
        ], $overrides);
    }

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
            'numero_permiso' => 'PERM-001',
            'tipo_permiso' => 'Almacenamiento',
            'instalaciones_count' => 2,
            'estatus_verificacion' => 'ACREDITADO',
            'activo' => true,
        ], $overrides);
    }

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
