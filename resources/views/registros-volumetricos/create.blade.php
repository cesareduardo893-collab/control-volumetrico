@extends('layouts.app')

@section('title', 'Nuevo Registro Volumétrico')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Registro Volumétrico</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('registros-volumetricos.store') }}" id="registroForm">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del Movimiento</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instalacion_id" class="form-label">Instalación <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('instalacion_id') is-invalid @enderror" 
                                        id="instalacion_id" 
                                        name="instalacion_id" 
                                        required>
                                    <option value="">Seleccione una instalación...</option>
                                    @foreach($instalaciones['data'] ?? [] as $instalacion)
                                        <option value="{{ $instalacion['id'] }}" {{ old('instalacion_id') == $instalacion['id'] ? 'selected' : '' }}>
                                            {{ $instalacion['clave_instalacion'] }} - {{ $instalacion['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instalacion_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanque_id" class="form-label">Tanque <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('tanque_id') is-invalid @enderror" 
                                        id="tanque_id" 
                                        name="tanque_id" 
                                        required>
                                    <option value="">Primero seleccione una instalación</option>
                                </select>
                                @error('tanque_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="producto_id" class="form-label">Producto <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('producto_id') is-invalid @enderror" 
                                        id="producto_id" 
                                        name="producto_id" 
                                        required>
                                    <option value="">Seleccione un producto...</option>
                                    @foreach($productos['data'] ?? [] as $producto)
                                        <option value="{{ $producto['id'] }}" {{ old('producto_id') == $producto['id'] ? 'selected' : '' }}>
                                            {{ $producto['clave_producto'] }} - {{ $producto['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_movimiento" class="form-label">Tipo de Movimiento <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_movimiento') is-invalid @enderror" 
                                        id="tipo_movimiento" 
                                        name="tipo_movimiento" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="entrada" {{ old('tipo_movimiento') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="salida" {{ old('tipo_movimiento') == 'salida' ? 'selected' : '' }}>Salida</option>
                                    <option value="trasiego" {{ old('tipo_movimiento') == 'trasiego' ? 'selected' : '' }}>Trasiego</option>
                                    <option value="ajuste" {{ old('tipo_movimiento') == 'ajuste' ? 'selected' : '' }}>Ajuste</option>
                                </select>
                                @error('tipo_movimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_movimiento" class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_movimiento') is-invalid @enderror" 
                                       id="fecha_movimiento" 
                                       name="fecha_movimiento" 
                                       value="{{ old('fecha_movimiento', date('Y-m-d')) }}" 
                                       required>
                                @error('fecha_movimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hora_movimiento" class="form-label">Hora <span class="text-danger">*</span></label>
                                <input type="time" 
                                       class="form-control @error('hora_movimiento') is-invalid @enderror" 
                                       id="hora_movimiento" 
                                       name="hora_movimiento" 
                                       value="{{ old('hora_movimiento', date('H:i:s')) }}" 
                                       step="1"
                                       required>
                                @error('hora_movimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Datos Volumétricos -->
                    <h5 class="mb-3 mt-4">Datos Volumétricos</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="volumen_bruto" class="form-label">Volumen Bruto (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('volumen_bruto') is-invalid @enderror" 
                                       id="volumen_bruto" 
                                       name="volumen_bruto" 
                                       value="{{ old('volumen_bruto') }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('volumen_bruto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="temperatura" class="form-label">Temperatura (°C) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('temperatura') is-invalid @enderror" 
                                       id="temperatura" 
                                       name="temperatura" 
                                       value="{{ old('temperatura', 15) }}" 
                                       step="0.1"
                                       required>
                                @error('temperatura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="densidad" class="form-label">Densidad (kg/m³) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('densidad') is-invalid @enderror" 
                                       id="densidad" 
                                       name="densidad" 
                                       value="{{ old('densidad') }}" 
                                       min="0" 
                                       step="0.0001"
                                       required>
                                @error('densidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="factor_correccion" class="form-label">Factor de Corrección</label>
                                <input type="number" 
                                       class="form-control @error('factor_correccion') is-invalid @enderror" 
                                       id="factor_correccion" 
                                       name="factor_correccion" 
                                       value="{{ old('factor_correccion', 1) }}" 
                                       min="0" 
                                       step="0.0001"
                                       readonly>
                                @error('factor_correccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="volumen_neto" class="form-label">Volumen Neto (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('volumen_neto') is-invalid @enderror" 
                                       id="volumen_neto" 
                                       name="volumen_neto" 
                                       value="{{ old('volumen_neto') }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('volumen_neto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Equipos Asociados -->
                    <h5 class="mb-3 mt-4">Equipos Asociados</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="medidor_id" class="form-label">Medidor</label>
                                <select class="form-select select2 @error('medidor_id') is-invalid @enderror" 
                                        id="medidor_id" 
                                        name="medidor_id">
                                    <option value="">Seleccione un medidor...</option>
                                </select>
                                @error('medidor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dispensario_id" class="form-label">Dispensario</label>
                                <select class="form-select select2 @error('dispensario_id') is-invalid @enderror" 
                                        id="dispensario_id" 
                                        name="dispensario_id">
                                    <option value="">Seleccione un dispensario...</option>
                                </select>
                                @error('dispensario_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="manguera_id" class="form-label">Manguera</label>
                                <select class="form-select select2 @error('manguera_id') is-invalid @enderror" 
                                        id="manguera_id" 
                                        name="manguera_id">
                                    <option value="">Seleccione una manguera...</option>
                                </select>
                                @error('manguera_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Observaciones -->
                    <h5 class="mb-3 mt-4">Observaciones</h5>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          id="observaciones" 
                                          name="observaciones" 
                                          rows="3">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <input type="hidden" name="estado" value="registrado">
                    <input type="hidden" name="usuario_id" value="{{ session('user.id') }}">
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
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
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Cargar tanques por instalación
    $('#instalacion_id').change(function() {
        var instalacionId = $(this).val();
        var tanqueSelect = $('#tanque_id');
        
        tanqueSelect.empty().append('<option value="">Cargando tanques...</option>');
        
        if (instalacionId) {
            $.get('/api/tanques/por-instalacion/' + instalacionId, function(data) {
                tanqueSelect.empty().append('<option value="">Seleccione un tanque...</option>');
                
                $.each(data, function(key, tanque) {
                    tanqueSelect.append('<option value="' + tanque.id + '">' + tanque.clave_tanque + ' - ' + tanque.nombre + ' (' + tanque.producto.nombre + ')</option>');
                });
            });
            
            // Cargar medidores
            $.get('/api/medidores/por-instalacion/' + instalacionId, function(data) {
                var medidorSelect = $('#medidor_id');
                medidorSelect.empty().append('<option value="">Seleccione un medidor...</option>');
                
                $.each(data, function(key, medidor) {
                    medidorSelect.append('<option value="' + medidor.id + '">' + medidor.clave_medidor + ' - ' + medidor.nombre + '</option>');
                });
            });
            
            // Cargar dispensarios
            $.get('/api/dispensarios/por-instalacion/' + instalacionId, function(data) {
                var dispensarioSelect = $('#dispensario_id');
                dispensarioSelect.empty().append('<option value="">Seleccione un dispensario...</option>');
                
                $.each(data, function(key, dispensario) {
                    dispensarioSelect.append('<option value="' + dispensario.id + '">' + dispensario.clave_dispensario + ' - ' + dispensario.nombre + '</option>');
                });
            });
        } else {
            tanqueSelect.empty().append('<option value="">Primero seleccione una instalación</option>');
        }
    });
    
    // Cargar mangueras por dispensario
    $('#dispensario_id').change(function() {
        var dispensarioId = $(this).val();
        var mangueraSelect = $('#manguera_id');
        
        mangueraSelect.empty().append('<option value="">Cargando mangueras...</option>');
        
        if (dispensarioId) {
            $.get('/api/mangueras/por-dispensario/' + dispensarioId, function(data) {
                mangueraSelect.empty().append('<option value="">Seleccione una manguera...</option>');
                
                $.each(data, function(key, manguera) {
                    mangueraSelect.append('<option value="' + manguera.id + '">' + manguera.clave_manguera + ' - ' + manguera.producto.nombre + '</option>');
                });
            });
        } else {
            mangueraSelect.empty().append('<option value="">Primero seleccione un dispensario</option>');
        }
    });
    
    // Calcular volumen neto
    function calcularVolumenNeto() {
        var bruto = parseFloat($('#volumen_bruto').val()) || 0;
        var temperatura = parseFloat($('#temperatura').val()) || 15;
        var densidad = parseFloat($('#densidad').val()) || 0;
        
        // Aquí iría la lógica real de cálculo del factor de corrección
        // basado en tablas API o fórmulas específicas
        var factor = 1 - (0.0005 * (temperatura - 15));
        $('#factor_correccion').val(factor.toFixed(4));
        
        var neto = bruto * factor;
        $('#volumen_neto').val(neto.toFixed(3));
    }
    
    $('#volumen_bruto, #temperatura, #densidad').on('input', calcularVolumenNeto);
    
    // Validar que no exceda capacidad del tanque
    $('#tanque_id').change(function() {
        var tanqueId = $(this).val();
        
        if (tanqueId) {
            $.get('/api/tanques/' + tanqueId + '/capacidad-disponible', function(data) {
                var disponible = data.capacidad_disponible;
                $('#volumen_bruto').attr('max', disponible);
                
                if (parseFloat($('#volumen_bruto').val()) > disponible) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'El volumen ingresado supera la capacidad disponible del tanque (' + disponible.toFixed(2) + ' L)'
                    });
                }
            });
        }
    });
});
</script>
@endpush