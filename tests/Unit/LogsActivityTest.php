<?php

namespace Tests\Unit;

use App\Models\Bitacora;
use App\Http\Controllers\Traits\LogsActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogsActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_activity_creates_bitacora_record()
    {
        // Create a test controller that uses the LogsActivity trait
        $controller = new class extends \App\Http\Controllers\Controller {
            use LogsActivity;

            public function testLog()
            {
                return $this->logActivity(
                    1, // userId
                    Bitacora::TIPO_EVENTO_ADMINISTRACION, // tipoEvento
                    'TEST_EVENT', // subtipoEvento
                    'Test Module', // modulo
                    'Test description' // descripcion
                );
            }
        };

        // Call the logActivity method
        $bitacora = $controller->testLog();

        // Assert the bitacora record was created
        $this->assertDatabaseHas('bitacora', [
            'usuario_id' => 1,
            'tipo_evento' => Bitacora::TIPO_EVENTO_ADMINISTRACION,
            'subtipo_evento' => 'TEST_EVENT',
            'modulo' => 'Test Module',
            'descripcion' => 'Test description',
        ]);

        // Assert the returned model is correct
        $this->assertInstanceOf(Bitacora::class, $bitacora);
        $this->assertEquals(Bitacora::TIPO_EVENTO_ADMINISTRACION, $bitacora->tipo_evento);
        $this->assertEquals('TEST_EVENT', $bitacora->subtipo_evento);
    }

    public function test_log_activity_defaults_invalid_tipo_evento()
    {
        $controller = new class extends \App\Http\Controllers\Controller {
            use LogsActivity;

            public function testLog()
            {
                return $this->logActivity(
                    1,
                    'INVALID_TIPO_EVENTO', // This should be defaulted to 'seguridad'
                    'TEST_EVENT',
                    'Test Module',
                    'Test description'
                );
            }
        };

        $bitacora = $controller->testLog();

        // Assert the invalid tipo_evento was defaulted to 'seguridad'
        $this->assertEquals('seguridad', $bitacora->tipo_evento);
    }
}
