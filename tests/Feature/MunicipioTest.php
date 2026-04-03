<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MunicipioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_por_estado_returns_municipios_successfully()
    {
        $municipios = [
            ['id' => 1, 'nombre' => 'Municipio 1', 'estado' => 'Jalisco'],
            ['id' => 2, 'nombre' => 'Municipio 2', 'estado' => 'Jalisco'],
            ['id' => 3, 'nombre' => 'Municipio 3', 'estado' => 'Jalisco'],
        ];

        $this->mockSuccessfulResponse('/api/municipios/por-estado', $municipios);

        $response = $this->getJson('/api/municipios/por-estado?estado=Jalisco');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function test_por_estado_returns_error_when_estado_missing()
    {
        $response = $this->getJson('/api/municipios/por-estado');

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
        $response->assertJsonFragment(['message' => 'El parámetro estado es requerido']);
    }

    /** @test */
    public function test_por_estado_returns_empty_array_when_no_municipios()
    {
        $this->mockSuccessfulResponse('/api/municipios/por-estado', []);

        $response = $this->getJson('/api/municipios/por-estado?estado=EstadoInexistente');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonCount(0, 'data');
    }

    /** @test */
    public function test_por_estado_handles_api_error()
    {
        $this->mockErrorResponse('/api/municipios/por-estado', 'Error al obtener municipios', 500);

        $response = $this->getJson('/api/municipios/por-estado?estado=Jalisco');

        $response->assertStatus(500);
        $response->assertJson(['success' => false]);
    }
}