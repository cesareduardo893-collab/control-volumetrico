@extends('layouts.app')

@section('title', 'Nuevo Dictamen')
@section('header', 'Registrar Nuevo Dictamen de Calidad')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Dictamen</h5>
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
                
                <form method="POST" action="{{ route('dictamenes.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="folio" class="form-label">Folio *</label>
                            <input type="text" class="form-control" id="folio" name="folio" 
                                   value="{{ old('folio') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="numero_lote" class="form-label">Número de Lote *</label>
                            <input type="text" class="form-control" id="numero_lote" name="numero_lote" 
                                   value="{{ old('numero_lote') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                            <input type="date" class="form-control datepicker" id="fecha_emision" 
                                   name="fecha_emision" value="{{ old('fecha_emision', now()->toDateString()) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contribuyente_id" class="form-label">Contribuyente *</label>
                            <select class="form-select select2" id="contribuyente_id" name="contribuyente_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($contribuyentes as $contribuyente)
                                    <option value="{{ $contribuyente['id'] }}" {{ old('contribuyente_id') == $contribuyente['id'] ? 'selected' : '' }}>
                                        {{ $contribuyente['razon_social'] }} ({{ $contribuyente['rfc'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id">
                                <option value="">Seleccione (opcional)</option>
                                @foreach($instalaciones as $instalacion)
                                    <option value="{{ $instalacion['id'] }}" {{ old('instalacion_id') == $instalacion['id'] ? 'selected' : '' }}>
                                        {{ $instalacion['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="laboratorio_rfc" class="form-label">RFC del Laboratorio *</label>
                            <input type="text" class="form-control" id="laboratorio_rfc" name="laboratorio_rfc" 
                                   value="{{ old('laboratorio_rfc') }}" maxlength="13" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="laboratorio_nombre" class="form-label">Nombre del Laboratorio *</label>
                            <input type="text" class="form-control" id="laboratorio_nombre" name="laboratorio_nombre" 
                                   value="{{ old('laboratorio_nombre') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="laboratorio_numero_acreditacion" class="form-label">N° Acreditación *</label>
                            <input type="text" class="form-control" id="laboratorio_numero_acreditacion" 
                                   name="laboratorio_numero_acreditacion" value="{{ old('laboratorio_numero_acreditacion') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="fecha_toma_muestra" class="form-label">Toma de Muestra *</label>
                            <input type="date" class="form-control datepicker" id="fecha_toma_muestra" 
                                   name="fecha_toma_muestra" value="{{ old('fecha_toma_muestra') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="fecha_pruebas" class="form-label">Fecha de Pruebas *</label>
                            <input type="date" class="form-control datepicker" id="fecha_pruebas" 
                                   name="fecha_pruebas" value="{{ old('fecha_pruebas') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="fecha_resultados" class="form-label">Fecha Resultados *</label>
                            <input type="date" class="form-control datepicker" id="fecha_resultados" 
                                   name="fecha_resultados" value="{{ old('fecha_resultados') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="VIGENTE" {{ old('estado', 'VIGENTE') == 'VIGENTE' ? 'selected' : '' }}>Vigente</option>
                                <option value="CADUCADO" {{ old('estado') == 'CADUCADO' ? 'selected' : '' }}>Caducado</option>
                                <option value="CANCELADO" {{ old('estado') == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="producto_id" class="form-label">Producto *</label>
                            <select class="form-select select2" id="producto_id" name="producto_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto['id'] }}" {{ old('producto_id') == $producto['id'] ? 'selected' : '' }}>
                                        {{ $producto['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_muestra" class="form-label">Volumen de Muestra *</label>
                            <div class="input-group">
                                <input type="number" step="0.001" min="0" class="form-control" 
                                       id="volumen_muestra" name="volumen_muestra" value="{{ old('volumen_muestra') }}" required>
                                <span class="input-group-text">L</span>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="unidad_medida_muestra" class="form-label">Unidad de Medida *</label>
                            <input type="text" class="form-control" id="unidad_medida_muestra" 
                                   name="unidad_medida_muestra" value="{{ old('unidad_medida_muestra', 'LITROS') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="metodo_muestreo" class="form-label">Método de Muestreo *</label>
                            <input type="text" class="form-control" id="metodo_muestreo" 
                                   name="metodo_muestreo" value="{{ old('metodo_muestreo') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="metodo_ensayo" class="form-label">Método de Ensayo *</label>
                            <input type="text" class="form-control" id="metodo_ensayo" 
                                   name="metodo_ensayo" value="{{ old('metodo_ensayo') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resultados" class="form-label">Resultados del Análisis</label>
                        <textarea class="form-control" id="resultados" name="resultados" 
                                  rows="4">{{ old('resultados') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dictamenes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Dictamen
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
    
    // Validación de fechas
    $('#fecha_toma_muestra, #fecha_pruebas, #fecha_resultados').change(function() {
        let toma = $('#fecha_toma_muestra').val();
        let pruebas = $('#fecha_pruebas').val();
        let resultados = $('#fecha_resultados').val();
        
        if (toma && pruebas && toma > pruebas) {
            alert('La fecha de pruebas debe ser posterior a la toma de muestra');
            $('#fecha_pruebas').val('');
        }
        
        if (pruebas && resultados && pruebas > resultados) {
            alert('La fecha de resultados debe ser posterior a las pruebas');
            $('#fecha_resultados').val('');
        }
    });
});
</script>
@endpush