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
                            <small class="text-muted">Seleccione la estación de servicio</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo_reporte" class="form-label">Tipo de Reporte</label>
                            <select class="form-select" id="tipo_reporte" name="tipo_reporte">
                                <option value="MENSUAL" {{ old('tipo_reporte', 'MENSUAL') == 'MENSUAL' ? 'selected' : '' }}>Mensual</option>
                                <option value="ANUAL" {{ old('tipo_reporte') == 'ANUAL' ? 'selected' : '' }}>Anual</option>
                                <option value="ESPECIAL" {{ old('tipo_reporte') == 'ESPECIAL' ? 'selected' : '' }}>Especial</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="periodo" class="form-label">Período (AAAA-MM) *</label>
                            <input type="month" class="form-control" id="periodo" name="periodo" 
                                   value="{{ old('periodo', date('Y-m')) }}" required>
                            <small class="text-muted">Seleccione el mes del reporte</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="usuario" class="form-label">Usuario que Genera</label>
                            <input type="text" class="form-control" value="{{ session('user_name') }}" readonly>
                            <small class="text-muted">Se asignará automáticamente</small>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Nota:</strong> Al generar el reporte, el sistema automáticamente:
                        <ul class="mb-0 mt-2">
                            <li>Recolecta todos los datos volumétricos del período</li>
                            <li>Genera el archivo XML según formato SAT</li>
                            <li>Calcula el hash SHA256</li>
                        </ul>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('reportes-sat.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-earmark-code"></i> Generar Reporte SAT
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
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush