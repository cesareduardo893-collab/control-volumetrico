@extends('layouts.app')

@section('title', 'Nueva Manguera')
@section('header', 'Registrar Nueva Manguera')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información de la Manguera</h5>
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
                
                <form method="POST" action="{{ route('mangueras.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dispensario_id" class="form-label">Dispensario *</label>
                            <select class="form-select select2" id="dispensario_id" name="dispensario_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($dispensarios as $dispensario)
                                    @php
                                        $did = is_array($dispensario) ? ($dispensario['id'] ?? $dispensario['ID'] ?? null)
                                            : (is_object($dispensario) ? ($dispensario->id ?? $dispensario->ID ?? null) : $dispensario);
                                        $dnombre = is_array($dispensario) ? ($dispensario['nombre'] ?? $dispensario['name'] ?? '')
                                            : (is_object($dispensario) ? ($dispensario->nombre ?? $dispensario->name ?? '') : '');
                                        $dclave = is_array($dispensario) ? ($dispensario['clave'] ?? '')
                                            : (is_object($dispensario) ? ($dispensario->clave ?? '') : '');
                                    @endphp
                                    @if ($did !== null)
                                        <option value="{{ $did }}" {{ old('dispensario_id') == $did ? 'selected' : '' }}>
                                            {{ $dnombre ?: (string)$did }} ({{ $dclave }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Clave *</label>
                            <input type="text" class="form-control" id="clave" name="clave" 
                                   value="{{ old('clave') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="2">{{ old('descripcion') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medidor_id" class="form-label">Medidor</label>
                            <select class="form-select select2" id="medidor_id" name="medidor_id">
                                <option value="">Seleccione...</option>
                                @foreach($medidores as $medidor)
                                    @php
                                        $mid = is_array($medidor) ? ($medidor['id'] ?? $medidor['ID'] ?? null)
                                            : (is_object($medidor) ? ($medidor->id ?? $medidor->ID ?? null) : $medidor);
                                        $mclave = is_array($medidor) ? ($medidor['clave'] ?? $medidor['numero_serie'] ?? '')
                                            : (is_object($medidor) ? ($medidor->clave ?? $medidor->numero_serie ?? '') : '');
                                        $mmodelo = is_array($medidor) ? ($medidor['modelo'] ?? '')
                                            : (is_object($medidor) ? ($medidor->modelo ?? '') : '');
                                    @endphp
                                    @if ($mid !== null)
                                        <option value="{{ $mid }}" {{ old('medidor_id') == $mid ? 'selected' : '' }}>
                                            {{ $mclave }} ({{ $mmodelo }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="OPERATIVO" {{ old('estado', 'OPERATIVO') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                                <option value="MANTENIMIENTO" {{ old('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="FUERA_SERVICIO" {{ old('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Manguera Activa</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('mangueras.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Manguera
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
});
</script>
@endpush