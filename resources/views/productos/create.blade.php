@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Producto</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('productos.store') }}" id="productoForm">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información Básica</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="clave_producto" class="form-label">Clave Producto <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('clave_producto') is-invalid @enderror" 
                                       id="clave_producto" 
                                       name="clave_producto" 
                                       value="{{ old('clave_producto') }}" 
                                       maxlength="50"
                                       required>
                                @error('clave_producto')
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
                                       value="{{ old('nombre') }}" 
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="2">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clasificación -->
                    <h5 class="mb-3 mt-4">Clasificación</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo" class="form-label">Tipo de Producto <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo') is-invalid @enderror" 
                                        id="tipo" 
                                        name="tipo" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="gasolina" {{ old('tipo') == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                    <option value="diesel" {{ old('tipo') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="combustoleo" {{ old('tipo') == 'combustoleo' ? 'selected' : '' }}>Combustóleo</option>
                                    <option value="petroleo" {{ old('tipo') == 'petroleo' ? 'selected' : '' }}>Petróleo</option>
                                    <option value="queroseno" {{ old('tipo') == 'queroseno' ? 'selected' : '' }}>Queroseno</option>
                                    <option value="otros" {{ old('tipo') == 'otros' ? 'selected' : '' }}>Otros</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="clave_sat" class="form-label">Clave SAT <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('clave_sat') is-invalid @enderror" 
                                       id="clave_sat" 
                                       name="clave_sat" 
                                       value="{{ old('clave_sat') }}" 
                                       maxlength="20"
                                       required>
                                @error('clave_sat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="clave_unidad" class="form-label">Clave Unidad <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('clave_unidad') is-invalid @enderror" 
                                       id="clave_unidad" 
                                       name="clave_unidad" 
                                       value="{{ old('clave_unidad') }}" 
                                       maxlength="10"
                                       required>
                                @error('clave_unidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Propiedades Físicas -->
                    <h5 class="mb-3 mt-4">Propiedades Físicas</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="densidad_referencia" class="form-label">Densidad de Referencia <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('densidad_referencia') is-invalid @enderror" 
                                       id="densidad_referencia" 
                                       name="densidad_referencia" 
                                       value="{{ old('densidad_referencia') }}" 
                                       min="0" 
                                       step="0.0001"
                                       required>
                                @error('densidad_referencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">kg/m³ a 15°C</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="temperatura_referencia" class="form-label">Temperatura de Referencia <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('temperatura_referencia') is-invalid @enderror" 
                                       id="temperatura_referencia" 
                                       name="temperatura_referencia" 
                                       value="{{ old('temperatura_referencia') }}" 
                                       step="0.1"
                                       required>
                                @error('temperatura_referencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">°C</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="factor_correccion" class="form-label">Factor de Corrección <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('factor_correccion') is-invalid @enderror" 
                                       id="factor_correccion" 
                                       name="factor_correccion" 
                                       value="{{ old('factor_correccion') }}" 
                                       min="0" 
                                       step="0.0001"
                                       required>
                                @error('factor_correccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rangos Operativos -->
                    <h5 class="mb-3 mt-4">Rangos Operativos</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rango_temperatura_min" class="form-label">Temperatura Mínima <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('rango_temperatura_min') is-invalid @enderror" 
                                       id="rango_temperatura_min" 
                                       name="rango_temperatura_min" 
                                       value="{{ old('rango_temperatura_min') }}" 
                                       step="0.1"
                                       required>
                                @error('rango_temperatura_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">°C</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rango_temperatura_max" class="form-label">Temperatura Máxima <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('rango_temperatura_max') is-invalid @enderror" 
                                       id="rango_temperatura_max" 
                                       name="rango_temperatura_max" 
                                       value="{{ old('rango_temperatura_max') }}" 
                                       step="0.1"
                                       required>
                                @error('rango_temperatura_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">°C</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rango_presion_min" class="form-label">Presión Mínima <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('rango_presion_min') is-invalid @enderror" 
                                       id="rango_presion_min" 
                                       name="rango_presion_min" 
                                       value="{{ old('rango_presion_min') }}" 
                                       step="0.1"
                                       required>
                                @error('rango_presion_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">psi</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rango_presion_max" class="form-label">Presión Máxima <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('rango_presion_max') is-invalid @enderror" 
                                       id="rango_presion_max" 
                                       name="rango_presion_max" 
                                       value="{{ old('rango_presion_max') }}" 
                                       step="0.1"
                                       required>
                                @error('rango_presion_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">psi</small>
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
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
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
    // Validar que temperatura mínima < temperatura máxima
    $('#rango_temperatura_min, #rango_temperatura_max').on('input', function() {
        var min = parseFloat($('#rango_temperatura_min').val()) || 0;
        var max = parseFloat($('#rango_temperatura_max').val()) || 0;
        
        if (min >= max) {
            $('#rango_temperatura_max').addClass('is-invalid');
            $('#rango_temperatura_max').next('.invalid-feedback').text('La temperatura máxima debe ser mayor a la mínima');
        } else {
            $('#rango_temperatura_max').removeClass('is-invalid');
        }
    });
    
    // Validar que presión mínima < presión máxima
    $('#rango_presion_min, #rango_presion_max').on('input', function() {
        var min = parseFloat($('#rango_presion_min').val()) || 0;
        var max = parseFloat($('#rango_presion_max').val()) || 0;
        
        if (min >= max) {
            $('#rango_presion_max').addClass('is-invalid');
            $('#rango_presion_max').next('.invalid-feedback').text('La presión máxima debe ser mayor a la mínima');
        } else {
            $('#rango_presion_max').removeClass('is-invalid');
        }
    });
});
</script>
@endpush