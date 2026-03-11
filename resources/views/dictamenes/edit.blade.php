@extends('layouts.app')

@section('title', 'Editar Dictamen')
@section('header', 'Editar Dictamen de Calidad')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Dictamen</h5>
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
                
                <form method="POST" action="{{ route('dictamenes.update', $dictamen['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="folio" class="form-label">Folio</label>
                            <input type="text" class="form-control" id="folio" 
                                   value="{{ $dictamen['folio'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="numero_lote" class="form-label">Número de Lote</label>
                            <input type="text" class="form-control" id="numero_lote" 
                                   value="{{ $dictamen['numero_lote'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_emision" class="form-label">Fecha de Emisión</label>
                            <input type="date" class="form-control" id="fecha_emision" 
                                   value="{{ $dictamen['fecha_emision'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_toma_muestra" class="form-label">Toma de Muestra</label>
                            <input type="date" class="form-control" id="fecha_toma_muestra" 
                                   value="{{ $dictamen['fecha_toma_muestra'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_pruebas" class="form-label">Fecha de Pruebas</label>
                            <input type="date" class="form-control" id="fecha_pruebas" 
                                   value="{{ $dictamen['fecha_pruebas'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_resultados" class="form-label">Fecha Resultados</label>
                            <input type="date" class="form-control" id="fecha_resultados" 
                                   value="{{ $dictamen['fecha_resultados'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="laboratorio_rfc" class="form-label">RFC Laboratorio</label>
                            <input type="text" class="form-control" id="laboratorio_rfc" 
                                   value="{{ $dictamen['laboratorio_rfc'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="laboratorio_nombre" class="form-label">Nombre Laboratorio</label>
                            <input type="text" class="form-control" id="laboratorio_nombre" 
                                   value="{{ $dictamen['laboratorio_nombre'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="laboratorio_numero_acreditacion" class="form-label">N° Acreditación</label>
                            <input type="text" class="form-control" id="laboratorio_numero_acreditacion" 
                                   value="{{ $dictamen['laboratorio_numero_acreditacion'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="volumen_muestra" class="form-label">Volumen de Muestra</label>
                            <input type="text" class="form-control" id="volumen_muestra" 
                                   value="{{ number_format($dictamen['volumen_muestra'], 3) }} {{ $dictamen['unidad_medida_muestra'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="metodo_muestreo" class="form-label">Método de Muestreo</label>
                            <input type="text" class="form-control" id="metodo_muestreo" 
                                   value="{{ $dictamen['metodo_muestreo'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="metodo_ensayo" class="form-label">Método de Ensayo</label>
                            <input type="text" class="form-control" id="metodo_ensayo" 
                                   value="{{ $dictamen['metodo_ensayo'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="VIGENTE" {{ old('estado', $dictamen['estado']) == 'VIGENTE' ? 'selected' : '' }}>Vigente</option>
                                <option value="CADUCADO" {{ old('estado', $dictamen['estado']) == 'CADUCADO' ? 'selected' : '' }}>Caducado</option>
                                <option value="CANCELADO" {{ old('estado', $dictamen['estado']) == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="vigente" name="vigente" value="1"
                                       {{ old('vigente', $dictamen['vigente'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="vigente">Dictamen Vigente</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resultados" class="form-label">Resultados del Análisis</label>
                        <textarea class="form-control" id="resultados" name="resultados" 
                                  rows="4">{{ old('resultados', $dictamen['resultados'] ?? '') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones', $dictamen['observaciones'] ?? '') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dictamenes.show', $dictamen['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Dictamen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection