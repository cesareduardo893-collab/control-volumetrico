@extends('layouts.app')

@section('title', 'Editar Producto')
@section('header', 'Editar Producto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Producto</h5>
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
                
                <form method="POST" action="{{ route('productos.update', $producto['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clave_sat" class="form-label">Clave SAT</label>
                            <input type="text" class="form-control" id="clave_sat" 
                                   value="{{ $producto['clave_sat'] }}" disabled readonly>
                            <small class="text-muted">La clave SAT no puede ser modificada</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código Interno</label>
                            <input type="text" class="form-control" id="codigo" 
                                   value="{{ $producto['codigo'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clave_identificacion" class="form-label">Clave Identificación</label>
                            <input type="text" class="form-control" id="clave_identificacion" 
                                   value="{{ $producto['clave_identificacion'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre', $producto['nombre']) }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="3">{{ old('descripcion', $producto['descripcion'] ?? '') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="unidad_medida" class="form-label">Unidad de Medida</label>
                            <input type="text" class="form-control" id="unidad_medida" name="unidad_medida" 
                                   value="{{ old('unidad_medida', $producto['unidad_medida']) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_hidrocarburo" class="form-label">Tipo de Hidrocarburo</label>
                            <select class="form-select" id="tipo_hidrocarburo" name="tipo_hidrocarburo" required>
                                <option value="petroleo" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'petroleo' ? 'selected' : '' }}>Petróleo</option>
                                <option value="gas_natural" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'gas_natural' ? 'selected' : '' }}>Gas Natural</option>
                                <option value="condensados" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'condensados' ? 'selected' : '' }}>Condensados</option>
                                <option value="gasolina" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                <option value="diesel" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="turbosina" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'turbosina' ? 'selected' : '' }}>Turbosina</option>
                                <option value="gas_lp" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'gas_lp' ? 'selected' : '' }}>Gas LP</option>
                                <option value="propano" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'propano' ? 'selected' : '' }}>Propano</option>
                                <option value="otro" {{ old('tipo_hidrocarburo', $producto['tipo_hidrocarburo']) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="densidad_referencia" class="form-label">Densidad de Referencia</label>
                            <div class="input-group">
                                <input type="number" step="0.0001" min="0" class="form-control" 
                                       id="densidad_referencia" name="densidad_referencia" 
                                       value="{{ old('densidad_referencia', $producto['densidad_referencia'] ?? '') }}">
                                <span class="input-group-text">kg/L</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="temperatura_referencia" class="form-label">Temperatura de Referencia</label>
                            <div class="input-group">
                                <input type="number" step="0.1" class="form-control" 
                                       id="temperatura_referencia" name="temperatura_referencia" 
                                       value="{{ old('temperatura_referencia', $producto['temperatura_referencia'] ?? 15) }}">
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="factor_conversion" class="form-label">Factor de Conversión</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="factor_conversion" name="factor_conversion" 
                                   value="{{ old('factor_conversion', $producto['factor_conversion'] ?? 1) }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="octanaje" class="form-label">Octanaje (para gasolinas)</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="octanaje" name="octanaje" 
                                   value="{{ old('octanaje', $producto['octanaje'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="numero_octano" class="form-label">Número de Octano</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="numero_octano" name="numero_octano" 
                                   value="{{ old('numero_octano', $producto['numero_octano'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', $producto['activo']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Producto Activo</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('productos.show', $producto['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection