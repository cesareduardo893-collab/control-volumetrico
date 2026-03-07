@extends('layouts.app')

@section('title', 'Editar Existencia')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Existencia #{{ $existencia['id'] }}</h6>
            </div>
            <div class="card-body">
                @if($existencia['estado'] != 'pendiente')
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    Esta existencia no puede ser editada porque se encuentra en estado "{{ $existencia['estado'] }}".
                </div>
                @endif
                
                <form method="POST" action="{{ route('existencias.update', $existencia['id']) }}" id="existenciaForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información de la Medición</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanque_id" class="form-label">Tanque <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('tanque_id') is-invalid @enderror" 
                                        id="tanque_id" 
                                        name="tanque_id" 
                                        {{ $existencia['estado'] != 'pendiente' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione un tanque...</option>
                                    @foreach($tanques['data'] ?? [] as $tanque)
                                        <option value="{{ $tanque['id'] }}" 
                                            data-capacidad="{{ $tanque['capacidad'] }}"
                                            data-producto="{{ $tanque['producto_id'] }}"
                                            {{ old('tanque_id', $existencia['tanque_id']) == $tanque['id'] ? 'selected' : '' }}>
                                            {{ $tanque['clave_tanque'] }} - {{ $tanque['nombre'] }} ({{ $tanque['instalacion']['nombre'] ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @if($existencia['estado'] != 'pendiente')
                                    <input type="hidden" name="tanque_id" value="{{ $existencia['tanque_id'] }}">
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
                                        {{ $existencia['estado'] != 'pendiente' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione un producto...</option>
                                    @foreach($productos['data'] ?? [] as $producto)
                                        <option value="{{ $producto['id'] }}" 
                                            data-densidad="{{ $producto['densidad_referencia'] }}"
                                            {{ old('producto_id', $existencia['producto_id']) == $producto['id'] ? 'selected' : '' }}>
                                            {{ $producto['clave_producto'] }} - {{ $producto['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($existencia['estado'] != 'pendiente')
                                    <input type="hidden" name="producto_id" value="{{ $existencia['producto_id'] }}">
                                @endif
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
                                        name="medidor_id"
                                        {{ $existencia['estado'] != 'pendiente' ? 'disabled' : '' }}>
                                    <option value="">Seleccione un medidor...</option>
                                </select>
                                @if($existencia['estado'] != 'pendiente' && $existencia['medidor_id'])
                                    <input type="hidden" name="medidor_id" value="{{ $existencia['medidor_id'] }}">
                                @endif
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
                                       value="{{ old('fecha_medicion', $existencia['fecha_medicion']) }}" 
                                       {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}
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
                                       value="{{ old('hora_medicion', $existencia['hora_medicion']) }}" 
                                       step="1"
                                       {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}
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
                                        {{ $existencia['estado'] != 'pendiente' ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="manual" {{ old('metodo_medicion', $existencia['metodo_medicion']) == 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="automatica" {{ old('metodo_medicion', $existencia['metodo_medicion']) == 'automatica' ? 'selected' : '' }}>Automática</option>
                                </select>
                                @if($existencia['estado'] != 'pendiente')
                                    <input type="hidden" name="metodo_medicion" value="{{ $existencia['metodo_medicion'] }}">
                                @endif
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
                                       value="{{ old('volumen_bruto', $existencia['volumen_bruto']) }}" 
                                       min="0" 
                                       step="0.001"
                                       {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}
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
                                       value="{{ old('temperatura', $existencia['temperatura']) }}" 
                                       step="0.1"
                                       {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}
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
                                       value="{{ old('densidad', $existencia['densidad']) }}" 
                                       min="0" 
                                       step="0.0001"
                                       {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}
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
                                       value="{{ old('factor_correccion', $existencia['factor_correccion']) }}" 
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
                                       value="{{ old('volumen_neto', $existencia['volumen_neto']) }}" 
                                       min="0" 
                                       step="0.001"
                                       {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}
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
                                       value="{{ old('nivel_agua', $existencia['nivel_agua']) }}" 
                                       min="0" 
                                       step="0.01"
                                       {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}>
                                @error('nivel_agua')
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
                                          {{ $existencia['estado'] != 'pendiente' ? 'readonly' : '' }}>{{ old('observaciones', $existencia['observaciones']) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="usuario_id" value="{{ session('user.id') }}">
                    
                    @if($existencia['estado'] == 'pendiente')
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('existencias.show', $existencia['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('existencias.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('existencias.show', $existencia['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('existencias.index') }}" class="btn btn-secondary">
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
    @if($existencia['estado'] == 'pendiente')
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Cargar medidores por tanque
    function cargarMedidores() {
        var tanqueId = $('#tanque_id').val();
        var medidorSelect = $('#medidor_id');
        var medidorActual = {{ $existencia['medidor_id'] ?? 0 }};
        
        if (tanqueId) {
            $.get('/api/medidores/por-tanque/' + tanqueId, function(data) {
                medidorSelect.empty().append('<option value="">Seleccione un medidor...</option>');
                
                $.each(data, function(key, medidor) {
                    medidorSelect.append('<option value="' + medidor.id + '" ' + (medidor.id == medidorActual ? 'selected' : '') + '>' + 
                        medidor.clave_medidor + ' - ' + medidor.nombre + '</option>');
                });
            });
        } else {
            medidorSelect.empty().append('<option value="">Primero seleccione un tanque</option>');
        }
    }
    
    cargarMedidores();
    
    $('#tanque_id').change(cargarMedidores);
    
    // Calcular volumen neto
    function calcularVolumenNeto() {
        var bruto = parseFloat($('#volumen_bruto').val()) || 0;
        var temperatura = parseFloat($('#temperatura').val()) || 15;
        
        var factor = 1 - (0.0005 * (temperatura - 15));
        $('#factor_correccion').val(factor.toFixed(4));
        
        var neto = bruto * factor;
        $('#volumen_neto').val(neto.toFixed(3));
    }
    
    $('#volumen_bruto, #temperatura').on('input', calcularVolumenNeto);
    @endif
});
</script>
@endpush