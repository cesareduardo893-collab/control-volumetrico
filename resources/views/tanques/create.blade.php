@extends('layouts.app')

@section('title', 'Nuevo Tanque')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Tanque</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tanques.store') }}" id="tanqueForm">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información Básica</h5>
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
                                        <option value="{{ $instalacion['id'] }}" {{ old('instalacion_id', request('instalacion_id')) == $instalacion['id'] ? 'selected' : '' }}>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="clave_tanque" class="form-label">Clave Tanque <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('clave_tanque') is-invalid @enderror" 
                                       id="clave_tanque" 
                                       name="clave_tanque" 
                                       value="{{ old('clave_tanque') }}" 
                                       maxlength="50"
                                       required>
                                @error('clave_tanque')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre') }}" 
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ubicacion" class="form-label">Ubicación <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('ubicacion') is-invalid @enderror" 
                                       id="ubicacion" 
                                       name="ubicacion" 
                                       value="{{ old('ubicacion') }}" 
                                       required>
                                @error('ubicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Capacidades -->
                    <h5 class="mb-3 mt-4">Capacidades</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="capacidad" class="form-label">Capacidad Total (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('capacidad') is-invalid @enderror" 
                                       id="capacidad" 
                                       name="capacidad" 
                                       value="{{ old('capacidad') }}" 
                                       min="0" 
                                       step="0.01"
                                       required>
                                @error('capacidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="capacidad_operativa" class="form-label">Capacidad Operativa (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('capacidad_operativa') is-invalid @enderror" 
                                       id="capacidad_operativa" 
                                       name="capacidad_operativa" 
                                       value="{{ old('capacidad_operativa') }}" 
                                       min="0" 
                                       step="0.01"
                                       required>
                                @error('capacidad_operativa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="capacidad_seguridad" class="form-label">Capacidad de Seguridad (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('capacidad_seguridad') is-invalid @enderror" 
                                       id="capacidad_seguridad" 
                                       name="capacidad_seguridad" 
                                       value="{{ old('capacidad_seguridad') }}" 
                                       min="0" 
                                       step="0.01"
                                       required>
                                @error('capacidad_seguridad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dimensiones -->
                    <h5 class="mb-3 mt-4">Dimensiones</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="diametro" class="form-label">Diámetro (m)</label>
                                <input type="number" 
                                       class="form-control @error('diametro') is-invalid @enderror" 
                                       id="diametro" 
                                       name="diametro" 
                                       value="{{ old('diametro') }}" 
                                       min="0" 
                                       step="0.01">
                                @error('diametro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="longitud" class="form-label">Longitud (m)</label>
                                <input type="number" 
                                       class="form-control @error('longitud') is-invalid @enderror" 
                                       id="longitud" 
                                       name="longitud" 
                                       value="{{ old('longitud') }}" 
                                       min="0" 
                                       step="0.01">
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="altura_total" class="form-label">Altura Total (m)</label>
                                <input type="number" 
                                       class="form-control @error('altura_total') is-invalid @enderror" 
                                       id="altura_total" 
                                       name="altura_total" 
                                       value="{{ old('altura_total') }}" 
                                       min="0" 
                                       step="0.01">
                                @error('altura_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="altura_operativa" class="form-label">Altura Operativa (m)</label>
                                <input type="number" 
                                       class="form-control @error('altura_operativa') is-invalid @enderror" 
                                       id="altura_operativa" 
                                       name="altura_operativa" 
                                       value="{{ old('altura_operativa') }}" 
                                       min="0" 
                                       step="0.01">
                                @error('altura_operativa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="altura_seguridad" class="form-label">Altura Seguridad (m)</label>
                                <input type="number" 
                                       class="form-control @error('altura_seguridad') is-invalid @enderror" 
                                       id="altura_seguridad" 
                                       name="altura_seguridad" 
                                       value="{{ old('altura_seguridad') }}" 
                                       min="0" 
                                       step="0.01">
                                @error('altura_seguridad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="forma" class="form-label">Forma <span class="text-danger">*</span></label>
                                <select class="form-select @error('forma') is-invalid @enderror" 
                                        id="forma" 
                                        name="forma" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="cilindrico" {{ old('forma') == 'cilindrico' ? 'selected' : '' }}>Cilíndrico</option>
                                    <option value="rectangular" {{ old('forma') == 'rectangular' ? 'selected' : '' }}>Rectangular</option>
                                    <option value="esferico" {{ old('forma') == 'esferico' ? 'selected' : '' }}>Esférico</option>
                                </select>
                                @error('forma')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo_tanque" class="form-label">Tipo de Tanque <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_tanque') is-invalid @enderror" 
                                        id="tipo_tanque" 
                                        name="tipo_tanque" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="atmosferico" {{ old('tipo_tanque') == 'atmosferico' ? 'selected' : '' }}>Atmosférico</option>
                                    <option value="soterrado" {{ old('tipo_tanque') == 'soterrado' ? 'selected' : '' }}>Soterrado</option>
                                    <option value="areometro" {{ old('tipo_tanque') == 'areometro' ? 'selected' : '' }}>Areómetro</option>
                                </select>
                                @error('tipo_tanque')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="material" class="form-label">Material <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('material') is-invalid @enderror" 
                                       id="material" 
                                       name="material" 
                                       value="{{ old('material') }}" 
                                       required>
                                @error('material')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Configuración de Medición -->
                    <h5 class="mb-3 mt-4">Configuración de Medición</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_medicion" class="form-label">Tipo de Medición <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_medicion') is-invalid @enderror" 
                                        id="tipo_medicion" 
                                        name="tipo_medicion" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="manual" {{ old('tipo_medicion') == 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="automatica" {{ old('tipo_medicion') == 'automatica' ? 'selected' : '' }}>Automática</option>
                                </select>
                                @error('tipo_medicion')
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
                                    <option value="litros" {{ old('unidad_medida') == 'litros' ? 'selected' : '' }}>Litros</option>
                                    <option value="galones" {{ old('unidad_medida') == 'galones' ? 'selected' : '' }}>Galones</option>
                                    <option value="barriles" {{ old('unidad_medida') == 'barriles' ? 'selected' : '' }}>Barriles</option>
                                </select>
                                @error('unidad_medida')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="precision_medicion" class="form-label">Precisión de Medición</label>
                                <input type="number" 
                                       class="form-control @error('precision_medicion') is-invalid @enderror" 
                                       id="precision_medicion" 
                                       name="precision_medicion" 
                                       value="{{ old('precision_medicion') }}" 
                                       min="0" 
                                       step="0.001">
                                @error('precision_medicion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="factor_correccion" class="form-label">Factor de Corrección</label>
                                <input type="number" 
                                       class="form-control @error('factor_correccion') is-invalid @enderror" 
                                       id="factor_correccion" 
                                       name="factor_correccion" 
                                       value="{{ old('factor_correccion') }}" 
                                       min="0" 
                                       step="0.0001">
                                @error('factor_correccion')
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
                                <label for="umbral_nivel_min" class="form-label">Nivel Mínimo (%)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_nivel_min') is-invalid @enderror" 
                                       id="umbral_nivel_min" 
                                       name="umbral_nivel_min" 
                                       value="{{ old('umbral_nivel_min') }}" 
                                       min="0" 
                                       max="100">
                                @error('umbral_nivel_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_nivel_max" class="form-label">Nivel Máximo (%)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_nivel_max') is-invalid @enderror" 
                                       id="umbral_nivel_max" 
                                       name="umbral_nivel_max" 
                                       value="{{ old('umbral_nivel_max') }}" 
                                       min="0" 
                                       max="100">
                                @error('umbral_nivel_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_temperatura_min" class="form-label">Temp. Mínima (°C)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_temperatura_min') is-invalid @enderror" 
                                       id="umbral_temperatura_min" 
                                       name="umbral_temperatura_min" 
                                       value="{{ old('umbral_temperatura_min') }}" 
                                       step="any">
                                @error('umbral_temperatura_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_temperatura_max" class="form-label">Temp. Máxima (°C)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_temperatura_max') is-invalid @enderror" 
                                       id="umbral_temperatura_max" 
                                       name="umbral_temperatura_max" 
                                       value="{{ old('umbral_temperatura_max') }}" 
                                       step="any">
                                @error('umbral_temperatura_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_presion_min" class="form-label">Presión Mínima (psi)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_presion_min') is-invalid @enderror" 
                                       id="umbral_presion_min" 
                                       name="umbral_presion_min" 
                                       value="{{ old('umbral_presion_min') }}" 
                                       step="any">
                                @error('umbral_presion_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_presion_max" class="form-label">Presión Máxima (psi)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_presion_max') is-invalid @enderror" 
                                       id="umbral_presion_max" 
                                       name="umbral_presion_max" 
                                       value="{{ old('umbral_presion_max') }}" 
                                       step="any">
                                @error('umbral_presion_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ubicación Geográfica -->
                    <h5 class="mb-3 mt-4">Ubicación Geográfica</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" 
                                       class="form-control @error('latitud') is-invalid @enderror" 
                                       id="latitud" 
                                       name="latitud" 
                                       value="{{ old('latitud') }}" 
                                       step="any">
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" 
                                       class="form-control @error('longitud') is-invalid @enderror" 
                                       id="longitud" 
                                       name="longitud" 
                                       value="{{ old('longitud') }}" 
                                       step="any">
                                @error('longitud')
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
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('tanques.index') }}" class="btn btn-secondary">
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
    
    // Validar que capacidad_operativa <= capacidad
    $('#capacidad, #capacidad_operativa').on('input', function() {
        var capacidad = parseFloat($('#capacidad').val()) || 0;
        var operativa = parseFloat($('#capacidad_operativa').val()) || 0;
        
        if (operativa > capacidad) {
            $('#capacidad_operativa').addClass('is-invalid');
            $('#capacidad_operativa').next('.invalid-feedback').text('La capacidad operativa no puede ser mayor a la capacidad total');
        } else {
            $('#capacidad_operativa').removeClass('is-invalid');
        }
    });
    
    // Validar que capacidad_seguridad <= capacidad_operativa
    $('#capacidad_operativa, #capacidad_seguridad').on('input', function() {
        var operativa = parseFloat($('#capacidad_operativa').val()) || 0;
        var seguridad = parseFloat($('#capacidad_seguridad').val()) || 0;
        
        if (seguridad > operativa) {
            $('#capacidad_seguridad').addClass('is-invalid');
            $('#capacidad_seguridad').next('.invalid-feedback').text('La capacidad de seguridad no puede ser mayor a la capacidad operativa');
        } else {
            $('#capacidad_seguridad').removeClass('is-invalid');
        }
    });
});
</script>
@endpush