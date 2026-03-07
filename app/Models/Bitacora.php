<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacora';

    public $timestamps = true;

    const UPDATED_AT = null; // No se permite actualización

    protected $fillable = [
        'numero_registro',
        'usuario_id',
        'tipo_evento',
        'subtipo_evento',
        'modulo',
        'tabla',
        'registro_id',
        'datos_anteriores',
        'datos_nuevos',
        'descripcion',
        'ip_address',
        'user_agent',
        'dispositivo',
        'metadatos_seguridad',
        'observaciones',
        'hash_anterior',
        'hash_actual',
        'firma_digital',
    ];

    protected $casts = [
        'datos_anteriores'      => 'array',
        'datos_nuevos'          => 'array',
        'metadatos_seguridad'   => 'array',
        'created_at'            => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}