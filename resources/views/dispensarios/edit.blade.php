@extends('layouts.app')

@section('title', 'Editar Dispensario')
@section('header', 'Editar Dispensario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Dispensario</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('dispensarios.update', $dispensario['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Clave</label>
                            <input type="text" class="form-control" id="clave" name="clave" 
                                   value="{{ old('clave', $dispensario['clave']) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($instalaciones as $instalacion)
                                    <option value="{{ $instalacion['id'] }}" 
                                        {{ old('instalacion_id', $dispensario['instalacion_id']) == $instalacion['id'] ? 'selected' : '' }}>
                                        {{ $instalacion['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="2">{{ old('descripcion', $dispensario['descripcion'] ?? '') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" 
                                   value="{{ old('modelo', $dispensario['modelo'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante" 
                                   value="{{ old('fabricante', $dispensario['fabricante'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_serie" class="form-label">Número de Serie</label>
                            <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                   value="{{ old('numero_serie', $dispensario['numero_serie'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="OPERATIVO" {{ old('estado', ($dispensario['estado'] ?? '')) == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                                <option value="MANTENIMIENTO" {{ old('estado', ($dispensario['estado'] ?? '')) == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="FUERA_SERVICIO" {{ old('estado', ($dispensario['estado'] ?? '')) == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Sección de conexión con tanques -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-fuel-pump me-2"></i>
                                Conexión con Tanques de Almacenamiento
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Importante:</strong> Según el Anexo 21 de la Resolución Miscelánea Fiscal, 
                                cada dispensario debe estar conectado a al menos un tanque para poder realizar 
                                la conciliación diaria de existencias.
                            </div>
                            
                            <!-- Tanques actualmente conectados -->
                            @if(isset($dispensario['tanques']) && count($dispensario['tanques']) > 0)
                                <div class="mb-4">
                                    <h6 class="text-primary">
                                        <i class="bi bi-link-45deg me-2"></i>
                                        Tanques Conectados Actualmente
                                    </h6>
                                    <div class="row">
                                        @foreach($dispensario['tanques'] as $tanque)
                                            @php
                                                $tanqueId = is_array($tanque) ? ($tanque['id'] ?? $tanque['ID'] ?? null) 
                                                    : (is_object($tanque) ? ($tanque->id ?? $tanque->ID ?? null) : $tanque);
                                                $tanqueIdentificador = is_array($tanque) ? ($tanque['identificador'] ?? '') 
                                                    : (is_object($tanque) ? ($tanque->identificador ?? '') : '');
                                                $tanqueProducto = is_array($tanque) ? ($tanque['producto']['nombre'] ?? 'Sin producto') 
                                                    : (is_object($tanque) ? ($tanque->producto->nombre ?? 'Sin producto') : 'Sin producto');
                                                $tanqueCapacidad = is_array($tanque) ? ($tanque['capacidad_total'] ?? 0) 
                                                    : (is_object($tanque) ? ($tanque->capacidad_total ?? 0) : 0);
                                                $tanqueEstado = is_array($tanque) ? ($tanque['estado'] ?? '') 
                                                    : (is_object($tanque) ? ($tanque->estado ?? '') : '');
                                                $pivotActivo = is_array($tanque) ? ($tanque['pivot']['activo'] ?? true) 
                                                    : (is_object($tanque) ? ($tanque->pivot->activo ?? true) : true);
                                            @endphp
                                            @if($tanqueId !== null)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card h-100 border-{{ $pivotActivo ? 'success' : 'secondary' }}">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <strong class="text-primary">{{ $tanqueIdentificador }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">{{ $tanqueProducto }}</small>
                                                                    <br>
                                                                    <small class="text-muted">Cap: {{ number_format($tanqueCapacidad, 0) }} L</small>
                                                                </div>
                                                                <div class="text-end">
                                                                    <span class="badge bg-{{ $tanqueEstado == 'OPERATIVO' ? 'success' : 'warning' }}">
                                                                        {{ $tanqueEstado }}
                                                                    </span>
                                                                    <br>
                                                                    <span class="badge bg-{{ $pivotActivo ? 'success' : 'secondary' }} mt-1">
                                                                        {{ $pivotActivo ? 'Conectado' : 'Desconectado' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2">
                                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                        onclick="desconectarTanque({{ $tanqueId }})">
                                                                    <i class="bi bi-x-circle"></i> Desconectar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                            @endif
                            
                            <!-- Agregar nuevas conexiones -->
                            <div class="mb-4">
                                <h6 class="text-success">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Agregar Nuevas Conexiones
                                </h6>
                                <div class="row" id="tanques-container">
                                    @foreach($tanques as $tanque)
                                        @php
                                            $tanqueId = is_array($tanque) ? ($tanque['id'] ?? $tanque['ID'] ?? null) 
                                                : (is_object($tanque) ? ($tanque->id ?? $tanque->ID ?? null) : $tanque);
                                            $tanqueIdentificador = is_array($tanque) ? ($tanque['identificador'] ?? '') 
                                                : (is_object($tanque) ? ($tanque->identificador ?? '') : '');
                                            $tanqueProducto = is_array($tanque) ? ($tanque['producto']['nombre'] ?? 'Sin producto') 
                                                : (is_object($tanque) ? ($tanque->producto->nombre ?? 'Sin producto') : 'Sin producto');
                                            $tanqueCapacidad = is_array($tanque) ? ($tanque['capacidad_total'] ?? 0) 
                                                : (is_object($tanque) ? ($tanque->capacidad_total ?? 0) : 0);
                                            $tanqueEstado = is_array($tanque) ? ($tanque['estado'] ?? '') 
                                                : (is_object($tanque) ? ($tanque->estado ?? '') : '');
                                            
                                            // Verificar si ya está conectado
                                            $yaConectado = false;
                                            if(isset($dispensario['tanques'])) {
                                                foreach($dispensario['tanques'] as $tanqueConectado) {
                                                    $conectadoId = is_array($tanqueConectado) ? ($tanqueConectado['id'] ?? null) 
                                                        : (is_object($tanqueConectado) ? ($tanqueConectado->id ?? null) : null);
                                                    if($conectadoId == $tanqueId) {
                                                        $yaConectado = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($tanqueId !== null && !$yaConectado)
                                            <div class="col-md-4 mb-3">
                                                <div class="card h-100 border-2" id="tanque-card-{{ $tanqueId }}">
                                                    <div class="card-body p-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="tanques_seleccionados[]" 
                                                                   value="{{ $tanqueId }}" 
                                                                   id="tanque_{{ $tanqueId }}"
                                                                   {{ in_array($tanqueId, old('tanques_seleccionados', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100" for="tanque_{{ $tanqueId }}">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <strong class="text-primary">{{ $tanqueIdentificador }}</strong>
                                                                        <br>
                                                                        <small class="text-muted">{{ $tanqueProducto }}</small>
                                                                        <br>
                                                                        <small class="text-muted">Cap: {{ number_format($tanqueCapacidad, 0) }} L</small>
                                                                    </div>
                                                                    <span class="badge bg-{{ $tanqueEstado == 'OPERATIVO' ? 'success' : 'warning' }}">
                                                                        {{ $tanqueEstado }}
                                                                    </span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Solo se muestran tanques en estado OPERATIVO que no están conectados
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_instalacion" class="form-label">Fecha de Instalación</label>
                            <input type="date" class="form-control datepicker" id="fecha_instalacion" 
                                   name="fecha_instalacion" value="{{ old('fecha_instalacion', $dispensario['fecha_instalacion'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_ultimo_mantenimiento" class="form-label">Último Mantenimiento</label>
                            <input type="date" class="form-control datepicker" id="fecha_ultimo_mantenimiento" 
                                   name="fecha_ultimo_mantenimiento" value="{{ old('fecha_ultimo_mantenimiento', $dispensario['fecha_ultimo_mantenimiento'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_proximo_mantenimiento" class="form-label">Próximo Mantenimiento</label>
                            <input type="date" class="form-control datepicker" id="fecha_proximo_mantenimiento" 
                                   name="fecha_proximo_mantenimiento" value="{{ old('fecha_proximo_mantenimiento', $dispensario['fecha_proximo_mantenimiento'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacidad_maxima" class="form-label">Capacidad Máxima (L/min)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="capacidad_maxima" name="capacidad_maxima" 
                                   value="{{ old('capacidad_maxima', $dispensario['capacidad_maxima'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="presion_operacion" class="form-label">Presión de Operación (psi)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="presion_operacion" name="presion_operacion" 
                                   value="{{ old('presion_operacion', $dispensario['presion_operacion'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', (($dispensario['activo'] ?? true) ?? true)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Dispensario Activo</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dispensarios.show', $dispensario['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Dispensario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
    
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush