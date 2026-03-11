@extends('layouts.app')

@section('title', 'Editar Manguera')
@section('header', 'Editar Manguera')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Manguera</h5>
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
                
                <form method="POST" action="{{ route('mangueras.update', $manguera['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Clave</label>
                            <input type="text" class="form-control" id="clave" 
                                   value="{{ $manguera['clave'] }}" disabled readonly>
                            <small class="text-muted">La clave no puede ser modificada</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="dispensario_id" class="form-label">Dispensario</label>
                            <input type="text" class="form-control" 
                                   value="{{ $manguera['dispensario']['clave'] ?? $manguera['dispensario_id'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="2">{{ old('descripcion', $manguera['descripcion'] ?? '') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medidor_id" class="form-label">Asignar Medidor</label>
                            <select class="form-select select2" id="medidor_id" name="medidor_id">
                                <option value="">Sin medidor</option>
                                @foreach($medidores as $medidor)
                                    <option value="{{ $medidor['id'] }}" 
                                        {{ old('medidor_id', $manguera['medidor_id'] ?? '') == $medidor['id'] ? 'selected' : '' }}>
                                        {{ $medidor['clave'] }} - {{ $medidor['numero_serie'] }} ({{ $medidor['estado'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="OPERATIVO" {{ old('estado', $manguera['estado']) == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                                <option value="MANTENIMIENTO" {{ old('estado', $manguera['estado']) == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="FUERA_SERVICIO" {{ old('estado', $manguera['estado']) == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_ultima_calibracion" class="form-label">Última Calibración</label>
                            <input type="date" class="form-control datepicker" id="fecha_ultima_calibracion" 
                                   name="fecha_ultima_calibracion" value="{{ old('fecha_ultima_calibracion', $manguera['fecha_ultima_calibracion'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_proxima_calibracion" class="form-label">Próxima Calibración</label>
                            <input type="date" class="form-control datepicker" id="fecha_proxima_calibracion" 
                                   name="fecha_proxima_calibracion" value="{{ old('fecha_proxima_calibracion', $manguera['fecha_proxima_calibracion'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones', $manguera['observaciones'] ?? '') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', $manguera['activo']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Manguera Activa</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('mangueras.show', $manguera['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Manguera
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