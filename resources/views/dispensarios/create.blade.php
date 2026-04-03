@extends('layouts.app')

@section('title', 'Nuevo Dispensario')
@section('header', 'Registrar Nuevo Dispensario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Dispensario</h5>
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
                
                <form method="POST" action="{{ route('dispensarios.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($instalaciones as $instalacion)
                                    @php
                                        $iid = is_array($instalacion) ? ($instalacion['id'] ?? $instalacion['ID'] ?? null)
                                            : (is_object($instalacion) ? ($instalacion->id ?? $instalacion->ID ?? null) : $instalacion);
                                        $iname = is_array($instalacion) ? ($instalacion['nombre'] ?? $instalacion['name'] ?? '')
                                            : (is_object($instalacion) ? ($instalacion->nombre ?? $instalacion->name ?? '') : '');
                                        $ikey = is_array($instalacion) ? ($instalacion['clave_instalacion'] ?? $instalacion['clave'] ?? '')
                                            : (is_object($instalacion) ? ($instalacion->clave_instalacion ?? $instalacion->clave ?? '') : '');
                                    @endphp
                                    @if ($iid !== null)
                                        <option value="{{ $iid }}" {{ old('instalacion_id') == $iid ? 'selected' : '' }}>
                                            {{ $iname ?: (string)$iid }} ({{ $ikey }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Clave *</label>
                            <input type="text" class="form-control" id="clave" name="clave" 
                                   value="{{ old('clave') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="2">{{ old('descripcion') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" 
                                   value="{{ old('modelo') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante" 
                                   value="{{ old('fabricante') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_serie" class="form-label">Número de Serie</label>
                            <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                   value="{{ old('numero_serie') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="OPERATIVO" {{ old('estado', 'OPERATIVO') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                                <option value="MANTENIMIENTO" {{ old('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="FUERA_SERVICIO" {{ old('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
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
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Seleccionar Tanques para Conexión:</label>
                                    <div class="row" id="tanques-container">
                                        <div class="col-12">
                                            <small class="text-muted">Seleccione una instalación para ver los tanques disponibles</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Solo se muestran tanques en estado OPERATIVO de la misma instalación
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_instalacion" class="form-label">Fecha de Instalación</label>
                            <input type="date" class="form-control datepicker" id="fecha_instalacion" 
                                   name="fecha_instalacion" value="{{ old('fecha_instalacion') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_ultimo_mantenimiento" class="form-label">Último Mantenimiento</label>
                            <input type="date" class="form-control datepicker" id="fecha_ultimo_mantenimiento" 
                                   name="fecha_ultimo_mantenimiento" value="{{ old('fecha_ultimo_mantenimiento') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_proximo_mantenimiento" class="form-label">Próximo Mantenimiento</label>
                            <input type="date" class="form-control datepicker" id="fecha_proximo_mantenimiento" 
                                   name="fecha_proximo_mantenimiento" value="{{ old('fecha_proximo_mantenimiento') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacidad_maxima" class="form-label">Capacidad Máxima (L/min)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="capacidad_maxima" name="capacidad_maxima" value="{{ old('capacidad_maxima') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="presion_operacion" class="form-label">Presión de Operación (psi)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="presion_operacion" name="presion_operacion" value="{{ old('presion_operacion') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Dispensario Activo</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dispensarios.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Dispensario
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
    
    // Filter tanques by selected installation
    var allTanques = @json($tanques);
    
    $('#instalacion_id').on('change', function() {
        var instalacionId = $(this).val();
        var container = $('#tanques-container');
        container.empty();
        
        if (!instalacionId) {
            container.html('<div class="col-12"><small class="text-muted">Seleccione una instalación para ver los tanques disponibles</small></div>');
            return;
        }
        
        // Filter tanques by instalacion_id
        var filteredTanques = allTanques.filter(function(t) {
            var tInstId = t.instalacion_id || (t.instalacion && t.instalacion.id);
            return tInstId == instalacionId;
        });
        
        if (filteredTanques.length === 0) {
            container.html('<div class="col-12"><small class="text-warning">No hay tanques operativos en esta instalación</small></div>');
            return;
        }
        
        filteredTanques.forEach(function(tanque) {
            var id = tanque.id;
            var identificador = tanque.identificador || '';
            var producto = (tanque.producto && tanque.producto.nombre) ? tanque.producto.nombre : 'Sin producto';
            var capacidad = tanque.capacidad_total || 0;
            var estado = tanque.estado || '';
            var badgeClass = estado === 'OPERATIVO' ? 'success' : 'warning';
            
            var html = '<div class="col-md-4 mb-3">' +
                '<div class="card h-100 border-2" id="tanque-card-' + id + '">' +
                '<div class="card-body p-3">' +
                '<div class="form-check">' +
                '<input class="form-check-input" type="checkbox" name="tanques_seleccionados[]" value="' + id + '" id="tanque_' + id + '">' +
                '<label class="form-check-label w-100" for="tanque_' + id + '">' +
                '<div class="d-flex justify-content-between align-items-start">' +
                '<div>' +
                '<strong class="text-primary">' + identificador + '</strong><br>' +
                '<small class="text-muted">' + producto + '</small><br>' +
                '<small class="text-muted">Cap: ' + Number(capacidad).toLocaleString() + ' L</small>' +
                '</div>' +
                '<span class="badge bg-' + badgeClass + '">' + estado + '</span>' +
                '</div></label></div></div></div></div>';
            
            container.append(html);
        });
    });
    
    // Trigger filter on page load if installation is pre-selected
    if ($('#instalacion_id').val()) {
        $('#instalacion_id').trigger('change');
    }
});
</script>
@endpush
