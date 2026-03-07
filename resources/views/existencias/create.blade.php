@extends('layouts.app')

@section('title', 'Nueva Existencia')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nueva Existencia</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('existencias.store') }}" id="existenciaForm">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información de la Medición</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanque_id" class="form-label">Tanque <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('tanque_id') is-invalid @enderror" 
                                        id="tanque_id" 
                                        name="tanque_id" 
                                        required>
                                    <option value="">Seleccione un tanque...</option>
                                    @foreach($tanques['data'] ?? [] as $tanque)
                                        <option value="{{ $tanque['id'] }}" 
                                            data-capacidad="{{ $tanque['capacidad'] }}"
                                            data-producto="{{ $tanque['producto_id'] }}"
                                            {{ old('tanque_id', request('tanque_id')) == $tanque['id'] ? 'selected' : '' }}>
                                            {{ $tanque['clave_tanque'] }} - {{ $tanque['nombre'] }} ({{ $tanque['instalacion']['nombre'] ?? 'N/A' }})
                                        </option>
                                    @endforeach
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
                                        <option value="{{ $producto['id'] }}" 
                                            data-densidad="{{ $producto['densidad_referencia'] }}"
                                            {{ old('producto_id') == $producto['id'] ? 'selected' : '' }}>
                                            {{ $producto['clave_producto'] }} - {{ $producto['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_medicion" class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_medicion') is-invalid @enderror" 
                                       id="fecha_medicion" 
                                       name="fecha_medicion" 
                                       value="{{ old('fecha_medicion', date('Y-m-d')) }}" 
                                       required>
                                @error('fecha_medicion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hora_medicion" class="form-label">Hora <span class="text-danger">*</span></label>
                                <input type="time" 
                                       class="form-control @error('hora_medicion') is-invalid @enderror" 
                                       id="hora_medicion" 
                                       name="hora_medicion" 
                                       value="{{ old('hora_medicion', date('H:i:s')) }}" 
                                       step="1"
                                       required>
                                @error('hora_medicion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="metodo_medicion" class="form-label">Método de Medición <span class="text-danger">*</span></label>
                                <select class="form-select @error('metodo_medicion') is-invalid @enderror" 
                                        id="metodo_medicion" 
                                        name="metodo_medicion" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="manual" {{ old('metodo_medicion') == 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="automatica" {{ old('metodo_medicion') == 'automatica' ? 'selected' : '' }}>Automática</option>
                                </select>
                                @error('metodo_medicion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Datos de Medición -->
                    <h5 class="mb-3 mt-4">Datos de Medición</h5>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nivel_agua" class="form-label">Nivel de Agua</label>
                                <input type="number" 
                                       class="form-control @error('nivel_agua') is-invalid @enderror" 
                                       id="nivel_agua" 
                                       name="nivel_agua" 
                                       value="{{ old('nivel_agua') }}" 
                                       min="0" 
                                       step="0.01">
                                @error('nivel_agua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">cm</small>
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
                    <input type="hidden" name="estado" value="pendiente">
                    <input type="hidden" name="usuario_id" value="{{ session('user.id') }}">
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('existencias.index') }}" class="btn btn-secondary">
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
    
    // Cargar medidores por tanque
    $('#tanque_id').change(function() {
        var tanqueId = $(this).val();
        var medidorSelect = $('#medidor_id');
        
        medidorSelect.empty().append('<option value="">Cargando medidores...</option>');
        
        if (tanqueId) {
            $.get('/api/medidores/por-tanque/' + tanqueId, function(data) {
                medidorSelect.empty().append('<option value="">Seleccione un medidor...</option>');
                
                $.each(data, function(key, medidor) {
                    medidorSelect.append('<option value="' + medidor.id + '">' + medidor.clave_medidor + ' - ' + medidor.nombre + '</option>');
                });
            });
            
            // Obtener producto del tanque
            var selectedOption = $(this).find('option:selected');
            var productoId = selectedOption.data('producto');
            if (productoId) {
                $('#producto_id').val(productoId).trigger('change');
            }
        } else {
            medidorSelect.empty().append('<option value="">Primero seleccione un tanque</option>');
        }
    });
    
    // Cuando se selecciona un producto, cargar densidad
    $('#producto_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var densidad = selectedOption.data('densidad');
        
        if (densidad) {
            $('#densidad').val(densidad);
        }
    });
    
    // Calcular volumen neto
    function calcularVolumenNeto() {
        var bruto = parseFloat($('#volumen_bruto').val()) || 0;
        var temperatura = parseFloat($('#temperatura').val()) || 15;
        
        // Calcular factor de corrección (simplificado)
        // En un sistema real, esto usaría tablas API o fórmulas específicas
        var factor = 1 - (0.0005 * (temperatura - 15));
        $('#factor_correccion').val(factor.toFixed(4));
        
        var neto = bruto * factor;
        $('#volumen_neto').val(neto.toFixed(3));
    }
    
    $('#volumen_bruto, #temperatura').on('input', calcularVolumenNeto);
    
    // Validar que no exceda capacidad del tanque
    $('#tanque_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var capacidad = selectedOption.data('capacidad') || 0;
        
        $('#volumen_bruto').attr('max', capacidad);
        
        if (parseFloat($('#volumen_bruto').val()) > capacidad) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'El volumen ingresado supera la capacidad del tanque (' + capacidad + ' L)'
            });
        }
    });
    
    // Si hay tanque preseleccionado, cargar medidores
    if ($('#tanque_id').val()) {
        $('#tanque_id').trigger('change');
    }
});
</script>
@endpush