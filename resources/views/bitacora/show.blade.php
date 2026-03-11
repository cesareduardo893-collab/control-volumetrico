@extends('layouts.app')

@section('title', 'Detalle del Evento')
@section('header', 'Detalle del Evento')

@section('actions')
<a href="{{ route('bitacora.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">ID del Evento:</th>
                        <td>{{ $evento['id'] }}</td>
                    </tr>
                    <tr>
                        <th>Fecha y Hora:</th>
                        <td>{{ $evento['fecha_hora'] }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Evento:</th>
                        <td>
                            @php
                                $badgeClass = [
                                    'LOGIN' => 'success',
                                    'LOGOUT' => 'secondary',
                                    'CREATE' => 'primary',
                                    'UPDATE' => 'warning',
                                    'DELETE' => 'danger',
                                    'VIEW' => 'info',
                                    'EXPORT' => 'dark'
                                ][$evento['tipo_evento']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">{{ $evento['tipo_evento'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Subtipo:</th>
                        <td>{{ $evento['subtipo_evento'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Módulo:</th>
                        <td>{{ $evento['modulo'] ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Usuario y Origen</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Usuario:</th>
                        <td>
                            @if($evento['usuario'])
                                {{ $evento['usuario']['nombres'] }} {{ $evento['usuario']['apellidos'] }}<br>
                                <small class="text-muted">{{ $evento['usuario']['email'] }}</small>
                            @else
                                <span class="text-muted">Sistema</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dirección IP:</th>
                        <td>{{ $evento['ip_address'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User Agent:</th>
                        <td><small>{{ $evento['user_agent'] ?? '-' }}</small></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Descripción</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $evento['descripcion'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Registro Afectado</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Tabla:</th>
                        <td>{{ $evento['tabla'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>ID del Registro:</th>
                        <td>{{ $evento['registro_id'] ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Valores Antiguos y Nuevos</h5>
            </div>
            <div class="card-body">
                @if(!empty($evento['valores_antiguos']) || !empty($evento['valores_nuevos']))
                    <ul class="nav nav-tabs" id="valuesTabs" role="tablist">
                        @if(!empty($evento['valores_antiguos']))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="old-tab" data-bs-toggle="tab" 
                                        data-bs-target="#old" type="button" role="tab">Valores Antiguos</button>
                            </li>
                        @endif
                        @if(!empty($evento['valores_nuevos']))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ empty($evento['valores_antiguos']) ? 'active' : '' }}" 
                                        id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button" role="tab">
                                    Valores Nuevos
                                </button>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content mt-3">
                        @if(!empty($evento['valores_antiguos']))
                            <div class="tab-pane fade show active" id="old" role="tabpanel">
                                <pre class="bg-light p-3 rounded"><code>{{ json_encode($evento['valores_antiguos'], JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        @endif
                        @if(!empty($evento['valores_nuevos']))
                            <div class="tab-pane fade {{ empty($evento['valores_antiguos']) ? 'show active' : '' }}" id="new" role="tabpanel">
                                <pre class="bg-light p-3 rounded"><code>{{ json_encode($evento['valores_nuevos'], JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-muted mb-0">No hay información de valores</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection