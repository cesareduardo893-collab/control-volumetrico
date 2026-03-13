@extends('layouts.app')

@section('title', 'Actualizar Estado de Alarma')
@section('header', 'Actualizar Estado de Alarma')

@section('actions')
<a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Actualizar Estado de Alarma</h5>
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
                
                <!-- Información de la alarma -->
                <div class="alert alert-info mb-4">
                    <strong>Alarma:</strong> {{ $alarma['numero_registro'] }} - 
                    <strong>Tipo:</strong> {{ $alarma['tipo_alarma']['nombre'] ?? 'N/A' }} - 
                    <strong>Estado Actual:</strong> 
                    <span class="badge bg-{{ $alarma['estado_atencion'] == 'PENDIENTE' ? 'danger' : ($alarma['estado_atencion'] == 'EN_PROCESO' ? 'warning' : 'success') }}">
                        {{ $alarma['estado_atencion'] }}
                    </span>
                </div>
                
                <form method="POST" action="{{ route('alarmas.actualizar-estado', $alarma['id']) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="estado_atencion" class="form-label">Nuevo Estado *</label>
                        <select class="form-select" id="estado_atencion" name="estado_atencion" required>
                            <option value="PENDIENTE" {{ old('estado_atencion', $alarma['estado_atencion']) == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                            <option value="EN_PROCESO" {{ old('estado_atencion', $alarma['estado_atencion']) == 'EN_PROCESO' ? 'selected' : '' }}>En Proceso</option>
                            <option value="RESUELTA" {{ old('estado_atencion', $alarma['estado_atencion']) == 'RESUELTA' ? 'selected' : '' }}>Resuelta</option>
                            <option value="IGNORADA" {{ old('estado_atencion', $alarma['estado_atencion']) == 'IGNORADA' ? 'selected' : '' }}>Ignorada</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="4">{{ old('observaciones') }}</textarea>
                        <small class="text-muted">Observaciones sobre el cambio de estado</small>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Estado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
