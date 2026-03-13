@extends('layouts.app')

@section('title', 'Nuevo Reporte SAT')
@section('header', 'Generar Nuevo Reporte para el SAT')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Reporte</h5>
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
                
                <form method="POST" action="{{ route('reportes-sat.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($instalaciones as $instalacion)
                                    @php
                                        if (is_array($instalacion)) {
                                            $idInst = $instalacion['id'] ?? '';
                                            $nombreInst = $instalacion['nombre'] ?? '';
                                        } elseif (is_object($instalacion)) {
                                            $idInst = $instalacion->id ?? '';
                                            $nombreInst = $instalacion->nombre ?? '';
                                        } else {
                                            $idInst = (string)$instalacion;
                                            $nombreInst = $idInst;
                                        }
                                    @endphp
                                    <option value="{{ $idInst }}" {{ old('instalacion_id') == $idInst ? 'selected' : '' }}>
                                        {{ $nombreInst }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="folio" class="form-label">Folio *</label>
                            <input type="text" class="form-control" id="folio" name="folio" 
                                   value="{{ old('folio', 'REP-' . date('Ymd') . '-' . rand(100, 999)) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="periodo" class="form-label">Período (AAAAMM) *</label>
                            <input type="text" class="form-control" id="periodo" name="periodo" 
                                   value="{{ old('periodo', date('Ym')) }}" maxlength="7" required>
                            <small class="text-muted">Formato: Año y mes (ej. 202401)</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_reporte" class="form-label">Tipo de Reporte *</label>
                            <select class="form-select" id="tipo_reporte" name="tipo_reporte" required>
                                <option value="">Seleccione...</option>
                                <option value="MENSUAL" {{ old('tipo_reporte', 'MENSUAL') == 'MENSUAL' ? 'selected' : '' }}>Mensual</option>
                                <option value="ANUAL" {{ old('tipo_reporte') == 'ANUAL' ? 'selected' : '' }}>Anual</option>
                                <option value="ESPECIAL" {{ old('tipo_reporte') == 'ESPECIAL' ? 'selected' : '' }}>Especial</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="PENDIENTE" {{ old('estado', 'PENDIENTE') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                <option value="GENERADO" {{ old('estado') == 'GENERADO' ? 'selected' : '' }}>Generado</option>
                                <option value="FIRMADO" {{ old('estado') == 'FIRMADO' ? 'selected' : '' }}>Firmado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_generacion" class="form-label">Fecha de Generación</label>
                            <input type="date" class="form-control datepicker" id="fecha_generacion" 
                                   name="fecha_generacion" value="{{ old('fecha_generacion', now()->toDateString()) }}" readonly>
                            <small class="text-muted">Se asignará automáticamente</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="usuario_genera_id" class="form-label">Usuario que Genera</label>
                            <input type="text" class="form-control" value="{{ session('user_name') }}" readonly>
                            <input type="hidden" name="usuario_genera_id" value="{{ session('user_id') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('reportes-sat.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Generar Reporte
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
    
    // Validar formato de período
    $('#periodo').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 6) {
            value = value.substr(0, 6);
        }
        $(this).val(value);
    });
});
</script>
@endpush
