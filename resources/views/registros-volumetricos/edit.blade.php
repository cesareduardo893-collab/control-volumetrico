@extends('layouts.app')

@section('title', 'Editar Registro Volumétrico')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Registro Volumétrico #{{ $registro['id'] }}</h6>
            </div>
            <div class="card-body">
                @if($registro['estado'] != 'registrado')
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    Este registro no puede ser editado porque se encuentra en estado "{{ $registro['estado'] }}".
                </div>
                @endif
                
                <form method="POST" action="{{ route('registros-volumetricos.update', $registro['id']) }}" id="registroForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del Movimiento</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instalacion_id" class="form-label">Instalación <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('instalacion_id') is-invalid @enderror" 
                                        id="instalacion_id" 
                                        name="instalacion_id" 
                                        {{ $registro['estado'] != 'registrado' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione una instalación...</option>
                                    @foreach($instalaciones['data'] ?? [] as $instalacion)
                                        <option value="{{ $instalacion['id'] }}" 
                                            {{ old('instalacion_id', $registro['instalacion_id']) == $instalacion['id'] ? 'selected' : '' }}>
                                            {{ $instalacion['clave_instalacion'] }} - {{ $instalacion['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($registro['estado'] != 'registrado')
                                    <input type="hidden" name="instalacion_id" value="{{ $registro['instalacion_id'] }}">
                                @endif
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
                                        {{ $registro['estado'] != 'registrado' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Cargando tanques...</option>
                                </select>
                                @if($registro['estado'] != 'registrado')
                                    <input type="hidden" name="tanque_id" value="{{ $registro['tanque_id'] }}">
                                @endif
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
                                        {{ $registro['estado'] != 'registrado' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione un producto...</option>
                                    @foreach($productos['data'] ?? [] as $producto)
                                        <option value="{{ $producto['id'] }}" 
                                            {{ old('producto_id', $registro['producto_id']) == $producto['id'] ? 'selected' : '' }}>
                                            {{ $producto['clave_producto'] }} - {{ $producto['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($registro['estado'] != 'registrado')
                                    <input type="hidden" name="producto_id" value="{{ $registro['producto_id'] }}">
                                @endif
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
                                        {{ $registro['estado'] != 'registrado' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="entrada" {{ old('tipo_movimiento', $registro['tipo_movimiento']) == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="salida" {{ old('tipo_movimiento', $registro['tipo_movimiento']) == 'salida' ? 'selected' : '' }}>Salida</option>
                                    <option value="trasiego" {{ old('tipo_movimiento', $registro['tipo_movimiento']) == 'trasiego' ? 'selected' : '' }}>Trasiego</option>
                                    <option value="ajuste" {{ old('tipo_movimiento', $registro['tipo_movimiento']) == 'ajuste' ? 'selected' : '' }}>Ajuste</option>
                                </select>
                                @if($registro['estado'] != 'registrado')
                                    <input type="hidden" name="tipo_movimiento" value="{{ $registro['tipo_movimiento'] }}">
                                @endif
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
                                       value="{{ old('fecha_movimiento', $registro['fecha_movimiento']) }}" 
                                       {{ $registro['estado'] != 'registrado' ? 'readonly' : '' }}
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
                                       value="{{ old('hora_movimiento', $registro['hora_movimiento']) }}" 
                                       step="1"
                                       {{ $registro['estado'] != 'registrado' ? 'readonly' : '' }}
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
                                       value="{{ old('volumen_bruto', $registro['volumen_bruto']) }}" 
                                       min="0" 
                                       step="0.001"
                                       {{ $registro['estado'] != 'registrado' ? 'readonly' : '' }}
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
                                       value="{{ old('temperatura', $registro['temperatura']) }}" 
                                       step="0.1"
                                       {{ $registro['estado'] != 'registrado' ? 'readonly' : '' }}
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
                                       value="{{ old('densidad', $registro['densidad']) }}" 
                                       min="0" 
                                       step="0.0001"
                                       {{ $registro['estado'] != 'registrado' ? 'readonly' : '' }}
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
                                       value="{{ old('factor_correccion', $registro['factor_correccion']) }}" 
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
                                       value="{{ old('volumen_neto', $registro['volumen_neto']) }}" 
                                       min="0" 
                                       step="0.001"
                                       {{ $registro['estado'] != 'registrado' ? 'readonly' : '' }}
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
                                        name="medidor_id"
                                        {{ $registro['estado'] != 'registrado' ? 'disabled' : '' }}>
                                    <option value="">Seleccione un medidor...</option>
                                </select>
                                @if($registro['estado'] != 'registrado' && $registro['medidor_id'])
                                    <input type="hidden" name="medidor_id" value="{{ $registro['medidor_id'] }}">
                                @endif
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
                                        name="dispensario_id"
                                        {{ $registro['estado'] != 'registrado' ? 'disabled' : '' }}>
                                    <option value="">Seleccione un dispensario...</option>
                                </select>
                                @if($registro['estado'] != 'registrado' && $registro['dispensario_id'])
                                    <input type="hidden" name="dispensario_id" value="{{ $registro['dispensario_id'] }}">
                                @endif
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
                                        name="manguera_id"
                                        {{ $registro['estado'] != 'registrado' ? 'disabled' : '' }}>
                                    <option value="">Seleccione una manguera...</option>
                                </select>
                                @if($registro['estado'] != 'registrado' && $registro['manguera_id'])
                                    <input type="hidden" name="manguera_id" value="{{ $registro['manguera_id'] }}">
                                @endif
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
                                          rows="3"
                                          {{ $registro['estado'] != 'registrado' ? 'readonly' : '' }}>{{ old('observaciones', $registro['observaciones']) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="usuario_id" value="{{ session('user.id') }}">
                    
                    @if($registro['estado'] == 'registrado')
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('registros-volumetricos.show', $registro['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('registros-volumetricos.show', $registro['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Volver
                            </a>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if($registro['estado'] == 'registrado')
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Cargar tanques por instalación
    function cargarTanques() {
        var instalacionId = $('#instalacion_id').val();
        var tanqueSelect = $('#tanque_id');
        var tanqueActual = {{ $registro['tanque_id'] }};
        
        if (instalacionId) {
            $.get('/api/tanques/por-instalacion/' + instalacionId, function(data) {
                tanqueSelect.empty().append('<option value="">Seleccione un tanque...</option>');
                
                $.each(data, function(key, tanque) {
                    tanqueSelect.append('<option value="' + tanque.id + '" ' + (tanque.id == tanqueActual ? 'selected' : '') + '>' + 
                        tanque.clave_tanque + ' - ' + tanque.nombre + ' (' + tanque.producto.nombre + ')</option>');
                });
            });
            
            // Cargar medidores
            $.get('/api/medidores/por-instalacion/' + instalacionId, function(data) {
                var medidorSelect = $('#medidor_id');
                var medidorActual = {{ $registro['medidor_id'] ?? 0 }};
                
                medidorSelect.empty().append('<option value="">Seleccione un medidor...</option>');
                
                $.each(data, function(key, medidor) {
                    medidorSelect.append('<option value="' + medidor.id + '" ' + (medidor.id == medidorActual ? 'selected' : '') + '>' + 
                        medidor.clave_medidor + ' - ' + medidor.nombre + '</option>');
                });
            });
            
            // Cargar dispensarios
            $.get('/api/dispensarios/por-instalacion/' + instalacionId, function(data) {
                var dispensarioSelect = $('#dispensario_id');
                var dispensarioActual = {{ $registro['dispensario_id'] ?? 0 }};
                
                dispensarioSelect.empty().append('<option value="">Seleccione un dispensario...</option>');
                
                $.each(data, function(key, dispensario) {
                    dispensarioSelect.append('<option value="' + dispensario.id + '" ' + (dispensario.id == dispensarioActual ? 'selected' : '') + '>' + 
                        dispensario.clave_dispensario + ' - ' + dispensario.nombre + '</option>');
                });
            });
        }
    }
    
    cargarTanques();
    
    $('#instalacion_id').change(cargarTanques);
    
    // Cargar mangueras por dispensario
    $('#dispensario_id').change(function() {
        var dispensarioId = $(this).val();
        var mangueraSelect = $('#manguera_id');
        var mangueraActual = {{ $registro['manguera_id'] ?? 0 }};
        
        mangueraSelect.empty().append('<option value="">Cargando mangueras...</option>');
        
        if (dispensarioId) {
            $.get('/api/mangueras/por-dispensario/' + dispensarioId, function(data) {
                mangueraSelect.empty().append('<option value="">Seleccione una manguera...</option>');
                
                $.each(data, function(key, manguera) {
                    mangueraSelect.append('<option value="' + manguera.id + '" ' + (manguera.id == mangueraActual ? 'selected' : '') + '>' + 
                        manguera.clave_manguera + ' - ' + manguera.producto.nombre + '</option>');
                });
            });
        } else {
            mangueraSelect.empty().append('<option value="">Primero seleccione un dispensario</option>');
        }
    });
    
    if ($('#dispensario_id').val()) {
        $('#dispensario_id').trigger('change');
    }
    
    // Calcular volumen neto
    function calcularVolumenNeto() {
        var bruto = parseFloat($('#volumen_bruto').val()) || 0;
        var temperatura = parseFloat($('#temperatura').val()) || 15;
        var densidad = parseFloat($('#densidad').val()) || 0;
        
        var factor = 1 - (0.0005 * (temperatura - 15));
        $('#factor_correccion').val(factor.toFixed(4));
        
        var neto = bruto * factor;
        $('#volumen_neto').val(neto.toFixed(3));
    }
    
    $('#volumen_bruto, #temperatura, #densidad').on('input', calcularVolumenNeto);
    @endif
});
</script>
@endpush