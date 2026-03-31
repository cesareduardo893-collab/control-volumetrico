<?php

namespace App\Http\Controllers\Traits;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log an activity to the bitacora
     */
    protected function logActivity($userId, $tipoEvento, $subtipoEvento, $modulo, $descripcion, $tabla = null, $registroId = null, $datosAnteriores = null, $datosNuevos = null, $metadatos = [])
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
            'seguridad',
        ];

        // Validate the tipo_evento value
        if (! in_array($tipoEvento, $allowedTiposEvento)) {
            // If the value is not valid, use a default value
            $tipoEvento = 'seguridad';
        }

        // Create the bitacora entry
        try {
            $bitacora = new Bitacora;
            $bitacora->numero_registro = $this->getNextBitacoraNumber();
            $bitacora->usuario_id = $userId;
            $bitacora->tipo_evento = $tipoEvento;
            $bitacora->subtipo_evento = $subtipoEvento;
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
            $now = now();
            $bitacora->hash_actual = hash('sha256', $bitacora->descripcion.$now);
            $bitacora->save();

            return $bitacora;
        } catch (\Exception $e) {
            \Log::error('Error al registrar en bitácora: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get the next bitacora number
     */
    private function getNextBitacoraNumber()
    {
        try {
            $last = Bitacora::orderBy('id', 'desc')->first();

            return $last ? $last->id + 1 : 1;
        } catch (\Exception $e) {
            return rand(1000, 9999);
        }
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
