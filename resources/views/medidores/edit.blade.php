@extends('layouts.app')

@section('title', 'Editar Medidor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Medidor: {{ $medidor['nombre'] }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('medidores.update', $medidor['id']) }}" id="medidorForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información Básica</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="clave_medidor" class="form-label">Clave Medidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('clave_medidor') is-invalid @enderror" 
                                       id="clave_medidor" 
                                       name="clave_medidor" 
                                       value="{{ old('clave_medidor', $medidor['clave_medidor']) }}" 
                                       maxlength="50"
                                       required>
                                @error('clave_medidor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $medidor['nombre']) }}" 
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Asignación -->
                    <h5 class="mb-3 mt-4">Asignación</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_asignacion" class="form-label">Tipo de Asignación</label>
                                <select class="form-select" id="tipo_asignacion">
                                    <option value="tanque" {{ $medidor['tanque_id'] ? 'selected' : '' }}>Tanque</option>
                                    <option value="dispensario" {{ $medidor['dispensario_id'] ? 'selected' : '' }}>Dispensario</option>
                                    <option value="ninguno" {{ !$medidor['tanque_id'] && !$medidor['dispensario_id'] ? 'selected' : '' }}>Sin asignar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="tanque_container" style="{{ $medidor['tanque_id'] ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="tanque_id" class="form-label">Tanque</label>
                                <select class="form-select select2 @error('tanque_id') is-invalid @enderror" 
                                        id="tanque_id" 
                                        name="tanque_id">
                                    <option value="">Seleccione un tanque...</option>
                                    @foreach($tanques['data'] ?? [] as $tanque)
                                        <option value="{{ $tanque['id'] }}" 
                                            {{ old('tanque_id', $medidor['tanque_id']) == $tanque['id'] ? 'selected' : '' }}>
                                            {{ $tanque['clave_tanque'] }} - {{ $tanque['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="dispensario_container" style="{{ $medidor['dispensario_id'] ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="dispensario_id" class="form-label">Dispensario</label>
                                <select class="form-select select2 @error('dispensario_id') is-invalid @enderror" 
                                        id="dispensario_id" 
                                        name="dispensario_id">
                                    <option value="">Seleccione un dispensario...</option>
                                    @foreach($dispensarios['data'] ?? [] as $dispensario)
                                        <option value="{{ $dispensario['id'] }}" 
                                            {{ old('dispensario_id', $medidor['dispensario_id']) == $dispensario['id'] ? 'selected' : '' }}>
                                            {{ $dispensario['clave_dispensario'] }} - {{ $dispensario['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Especificaciones Técnicas -->
                    <h5 class="mb-3 mt-4">Especificaciones Técnicas</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_medidor" class="form-label">Tipo de Medidor <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_medidor') is-invalid @enderror" 
                                        id="tipo_medidor" 
                                        name="tipo_medidor" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="flotador" {{ old('tipo_medidor', $medidor['tipo_medidor']) == 'flotador' ? 'selected' : '' }}>Flotador</option>
                                    <option value="ultrasonico" {{ old('tipo_medidor', $medidor['tipo_medidor']) == 'ultrasonico' ? 'selected' : '' }}>Ultrasónico</option>
                                    <option value="radar" {{ old('tipo_medidor', $medidor['tipo_medidor']) == 'radar' ? 'selected' : '' }}>Radar</option>
                                    <option value="electromagnetico" {{ old('tipo_medidor', $medidor['tipo_medidor']) == 'electromagnetico' ? 'selected' : '' }}>Electromagnético</option>
                                </select>
                                @error('tipo_medidor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="marca" class="form-label">Marca <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('marca') is-invalid @enderror" 
                                       id="marca" 
                                       name="marca" 
                                       value="{{ old('marca', $medidor['marca']) }}" 
                                       required>
                                @error('marca')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modelo" class="form-label">Modelo <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('modelo') is-invalid @enderror" 
                                       id="modelo" 
                                       name="modelo" 
                                       value="{{ old('modelo', $medidor['modelo']) }}" 
                                       required>
                                @error('modelo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="serie" class="form-label">Número de Serie <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('serie') is-invalid @enderror" 
                                       id="serie" 
                                       name="serie" 
                                       value="{{ old('serie', $medidor['serie']) }}" 
                                       required>
                                @error('serie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rango_medicion_min" class="form-label">Rango Mínimo <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('rango_medicion_min') is-invalid @enderror" 
                                       id="rango_medicion_min" 
                                       name="rango_medicion_min" 
                                       value="{{ old('rango_medicion_min', $medidor['rango_medicion_min']) }}" 
                                       min="0" 
                                       step="0.01"
                                       required>
                                @error('rango_medicion_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rango_medicion_max" class="form-label">Rango Máximo <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('rango_medicion_max') is-invalid @enderror" 
                                       id="rango_medicion_max" 
                                       name="rango_medicion_max" 
                                       value="{{ old('rango_medicion_max', $medidor['rango_medicion_max']) }}" 
                                       min="0" 
                                       step="0.01"
                                       required>
                                @error('rango_medicion_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="precision" class="form-label">Precisión <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('precision') is-invalid @enderror" 
                                       id="precision" 
                                       name="precision" 
                                       value="{{ old('precision', $medidor['precision']) }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('precision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unidad_medida" class="form-label">Unidad de Medida <span class="text-danger">*</span></label>
                                <select class="form-select @error('unidad_medida') is-invalid @enderror" 
                                        id="unidad_medida" 
                                        name="unidad_medida" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="litros" {{ old('unidad_medida', $medidor['unidad_medida']) == 'litros' ? 'selected' : '' }}>Litros</option>
                                    <option value="galones" {{ old('unidad_medida', $medidor['unidad_medida']) == 'galones' ? 'selected' : '' }}>Galones</option>
                                    <option value="barriles" {{ old('unidad_medida', $medidor['unidad_medida']) == 'barriles' ? 'selected' : '' }}>Barriles</option>
                                </select>
                                @error('unidad_medida')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="resolucion" class="form-label">Resolución <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('resolucion') is-invalid @enderror" 
                                       id="resolucion" 
                                       name="resolucion" 
                                       value="{{ old('resolucion', $medidor['resolucion']) }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('resolucion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comunicación -->
                    <h5 class="mb-3 mt-4">Comunicación</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="frecuencia_muestreo" class="form-label">Frecuencia de Muestreo</label>
                                <input type="number" 
                                       class="form-control @error('frecuencia_muestreo') is-invalid @enderror" 
                                       id="frecuencia_muestreo" 
                                       name="frecuencia_muestreo" 
                                       value="{{ old('frecuencia_muestreo', $medidor['frecuencia_muestreo']) }}" 
                                       min="1">
                                @error('frecuencia_muestreo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_comunicacion" class="form-label">Tipo de Comunicación <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_comunicacion') is-invalid @enderror" 
                                        id="tipo_comunicacion" 
                                        name="tipo_comunicacion" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="analogica" {{ old('tipo_comunicacion', $medidor['tipo_comunicacion']) == 'analogica' ? 'selected' : '' }}>Analógica</option>
                                    <option value="digital" {{ old('tipo_comunicacion', $medidor['tipo_comunicacion']) == 'digital' ? 'selected' : '' }}>Digital</option>
                                    <option value="rs485" {{ old('tipo_comunicacion', $medidor['tipo_comunicacion']) == 'rs485' ? 'selected' : '' }}>RS-485</option>
                                    <option value="rs232" {{ old('tipo_comunicacion', $medidor['tipo_comunicacion']) == 'rs232' ? 'selected' : '' }}>RS-232</option>
                                    <option value="ethernet" {{ old('tipo_comunicacion', $medidor['tipo_comunicacion']) == 'ethernet' ? 'selected' : '' }}>Ethernet</option>
                                </select>
                                @error('tipo_comunicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="protocolo_comunicacion" class="form-label">Protocolo</label>
                                <input type="text" 
                                       class="form-control @error('protocolo_comunicacion') is-invalid @enderror" 
                                       id="protocolo_comunicacion" 
                                       name="protocolo_comunicacion" 
                                       value="{{ old('protocolo_comunicacion', $medidor['protocolo_comunicacion']) }}">
                                @error('protocolo_comunicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion_comunicacion" class="form-label">Dirección</label>
                                <input type="text" 
                                       class="form-control @error('direccion_comunicacion') is-invalid @enderror" 
                                       id="direccion_comunicacion" 
                                       name="direccion_comunicacion" 
                                       value="{{ old('direccion_comunicacion', $medidor['direccion_comunicacion']) }}">
                                @error('direccion_comunicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Calibración -->
                    <h5 class="mb-3 mt-4">Calibración</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estado_calibracion" class="form-label">Estado de Calibración <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado_calibracion') is-invalid @enderror" 
                                        id="estado_calibracion" 
                                        name="estado_calibracion" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="calibrado" {{ old('estado_calibracion', $medidor['estado_calibracion']) == 'calibrado' ? 'selected' : '' }}>Calibrado</option>
                                    <option value="no_calibrado" {{ old('estado_calibracion', $medidor['estado_calibracion']) == 'no_calibrado' ? 'selected' : '' }}>No calibrado</option>
                                    <option value="pendiente_calibracion" {{ old('estado_calibracion', $medidor['estado_calibracion']) == 'pendiente_calibracion' ? 'selected' : '' }}>Pendiente de calibración</option>
                                </select>
                                @error('estado_calibracion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_calibracion" class="form-label">Fecha de Calibración</label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_calibracion') is-invalid @enderror" 
                                       id="fecha_calibracion" 
                                       name="fecha_calibracion" 
                                       value="{{ old('fecha_calibracion', $medidor['fecha_calibracion']) }}">
                                @error('fecha_calibracion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_proxima_calibracion" class="form-label">Próxima Calibración</label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_proxima_calibracion') is-invalid @enderror" 
                                       id="fecha_proxima_calibracion" 
                                       name="fecha_proxima_calibracion" 
                                       value="{{ old('fecha_proxima_calibracion', $medidor['fecha_proxima_calibracion']) }}">
                                @error('fecha_proxima_calibracion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Umbrales de Alarma -->
                    <h5 class="mb-3 mt-4">Umbrales de Alarma</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_flujo_min" class="form-label">Flujo Mínimo</label>
                                <input type="number" 
                                       class="form-control @error('umbral_flujo_min') is-invalid @enderror" 
                                       id="umbral_flujo_min" 
                                       name="umbral_flujo_min" 
                                       value="{{ old('umbral_flujo_min', $medidor['umbral_flujo_min']) }}" 
                                       min="0" 
                                       step="0.01">
                                @error('umbral_flujo_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_flujo_max" class="form-label">Flujo Máximo</label>
                                <input type="number" 
                                       class="form-control @error('umbral_flujo_max') is-invalid @enderror" 
                                       id="umbral_flujo_max" 
                                       name="umbral_flujo_max" 
                                       value="{{ old('umbral_flujo_max', $medidor['umbral_flujo_max']) }}" 
                                       min="0" 
                                       step="0.01">
                                @error('umbral_flujo_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_presion_min" class="form-label">Presión Mínima</label>
                                <input type="number" 
                                       class="form-control @error('umbral_presion_min') is-invalid @enderror" 
                                       id="umbral_presion_min" 
                                       name="umbral_presion_min" 
                                       value="{{ old('umbral_presion_min', $medidor['umbral_presion_min']) }}" 
                                       step="0.01">
                                @error('umbral_presion_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_presion_max" class="form-label">Presión Máxima</label>
                                <input type="number" 
                                       class="form-control @error('umbral_presion_max') is-invalid @enderror" 
                                       id="umbral_presion_max" 
                                       name="umbral_presion_max" 
                                       value="{{ old('umbral_presion_max', $medidor['umbral_presion_max']) }}" 
                                       step="0.01">
                                @error('umbral_presion_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_temperatura_min" class="form-label">Temp. Mínima</label>
                                <input type="number" 
                                       class="form-control @error('umbral_temperatura_min') is-invalid @enderror" 
                                       id="umbral_temperatura_min" 
                                       name="umbral_temperatura_min" 
                                       value="{{ old('umbral_temperatura_min', $medidor['umbral_temperatura_min']) }}" 
                                       step="0.1">
                                @error('umbral_temperatura_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_temperatura_max" class="form-label">Temp. Máxima</label>
                                <input type="number" 
                                       class="form-control @error('umbral_temperatura_max') is-invalid @enderror" 
                                       id="umbral_temperatura_max" 
                                       name="umbral_temperatura_max" 
                                       value="{{ old('umbral_temperatura_max', $medidor['umbral_temperatura_max']) }}" 
                                       step="0.1">
                                @error('umbral_temperatura_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="activo" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', $medidor['activo']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('medidores.show', $medidor['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('medidores.index') }}" class="btn btn-secondary">
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
    
    // Cambiar tipo de asignación
    $('#tipo_asignacion').change(function() {
        var tipo = $(this).val();
        
        if (tipo === 'tanque') {
            $('#tanque_container').show();
            $('#dispensario_container').hide();
            $('#tanque_id').prop('required', true);
            $('#dispensario_id').prop('required', false).val(null).trigger('change');
        } else if (tipo === 'dispensario') {
            $('#tanque_container').hide();
            $('#dispensario_container').show();
            $('#tanque_id').prop('required', false).val(null).trigger('change');
            $('#dispensario_id').prop('required', true);
        } else {
            $('#tanque_container').hide();
            $('#dispensario_container').hide();
            $('#tanque_id').prop('required', false).val(null).trigger('change');
            $('#dispensario_id').prop('required', false).val(null).trigger('change');
        }
    });
    
    // Validar que rango_min < rango_max
    $('#rango_medicion_min, #rango_medicion_max').on('input', function() {
        var min = parseFloat($('#rango_medicion_min').val()) || 0;
        var max = parseFloat($('#rango_medicion_max').val()) || 0;
        
        if (min >= max) {
            $('#rango_medicion_max').addClass('is-invalid');
            $('#rango_medicion_max').next('.invalid-feedback').text('El rango máximo debe ser mayor al mínimo');
        } else {
            $('#rango_medicion_max').removeClass('is-invalid');
        }
    });
});
</script>
@endpush