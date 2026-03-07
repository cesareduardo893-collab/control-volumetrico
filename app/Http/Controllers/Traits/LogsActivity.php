<?php

namespace App\Http\Controllers\Traits;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

trait LogsActivity
{
    /**
     * Log an activity to the bitacora
     */
    protected function logActivity($userId, $categoria, $tipoEvento, $modulo, $descripcion, $tabla = null, $registroId = null, $datosAnteriores = null, $datosNuevos = null, $metadatos = [])
    {
// Use a valid tipo_evento from the allowed ENUM values
        $allowedTiposEvento = [
            'administracion_sistema',
            'eventos_ucc',
            'eventos_programas',
            'eventos_comunicacion',
            'operaciones_cotidianas',
            'verificaciones_autoridad',
            'inconsistencias_volumetricas',
            'seguridad'
        ];

        // Validate the tipo_evento value
        if (!in_array($tipoEvento, $allowedTiposEvento)) {
            // If the value is not valid, use a default value
            $tipoEvento = 'seguridad';
        }

// Create the bitacora entry
        $bitacora = new Bitacora();
        $bitacora->numero_registro = $this->getNextBitacoraNumber();
        $bitacora->usuario_id = $userId;
        $bitacora->tipo_evento = $tipoEvento;
        $bitacora->subtipo_evento = $categoria;
        $bitacora->modulo = $modulo;
        $bitacora->tabla = $tabla;
        $bitacora->registro_id = $registroId;
        $bitacora->datos_anteriores = $datosAnteriores;
        $bitacora->datos_nuevos = $datosNuevos;
        $bitacora->descripcion = $descripcion;
        $bitacora->ip_address = Request::ip();
        $bitacora->user_agent = Request::userAgent();
        $bitacora->dispositivo = $this->getDeviceFromUserAgent(Request::userAgent());
        $bitacora->metadatos_seguridad = $metadatos;

        // Generate hash values for SQLite
        $lastBitacora = Bitacora::orderBy('created_at', 'desc')->first();
        $bitacora->hash_anterior = $lastBitacora ? $lastBitacora->hash_actual : null;
        $bitacora->hash_actual = hash('sha256', $bitacora->descripcion . $bitacora->created_at);
        $bitacora->save();

        return $bitacora;
    }

    /**
     * Get the next bitacora number
     */
    private function getNextBitacoraNumber()
    {
        $last = Bitacora::orderBy('numero_registro', 'desc')->first();
        return $last ? $last->numero_registro + 1 : 1;
    }

    /**
     * Get device from user agent
     */
    private function getDeviceFromUserAgent($userAgent)
    {
        if (stripos($userAgent, 'Mobile') !== false || stripos($userAgent, 'Android') !== false) {
            return 'Móvil';
        } elseif (stripos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        } else {
            return 'Escritorio';
        }
    }
}