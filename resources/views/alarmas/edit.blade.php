@extends('layouts.app')

@section('title', 'Editar Alarma')
@section('header', 'Editar Alarma')

@section('actions')
<a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Editar Alarma</h5>
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
                
                <form method="POST" action="{{ route('alarmas.update', $alarma['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_registro" class="form-label">Número de Registro *</label>
                            <input type="text" class="form-control" id="numero_registro" name="numero_registro" 
                                   value="{{ old('numero_registro', $alarma['numero_registro']) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_hora" class="form-label">Fecha y Hora *</label>
                            <input type="datetime-local" class="form-control" id="fecha_hora" name="fecha_hora" 
                                   value="{{ old('fecha_hora', $alarma['fecha_hora']) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="componente_tipo" class="form-label">Tipo de Componente *</label>
                            <select class="form-select" id="componente_tipo" name="componente_tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="tanque" {{ old('componente_tipo', $alarma['componente_tipo']) == 'tanque' ? 'selected' : '' }}>Tanque</option>
                                <option value="medidor" {{ old('componente_tipo', $alarma['componente_tipo']) == 'medidor' ? 'selected' : '' }}>Medidor</option>
                                <option value="dispensario" {{ old('componente_tipo', $alarma['componente_tipo']) == 'dispensario' ? 'selected' : '' }}>Dispensario</option>
                                <option value="manguera" {{ old('componente_tipo', $alarma['componente_tipo']) == 'manguera' ? 'selected' : '' }}>Manguera</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="componente_identificador" class="form-label">Identificador del Componente *</label>
                            <input type="text" class="form-control" id="componente_identificador" name="componente_identificador" 
                                   value="{{ old('componente_identificador', $alarma['componente_identificador']) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_alarma_id" class="form-label">Tipo de Alarma *</label>
                            <select class="form-select" id="tipo_alarma_id" name="tipo_alarma_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($tiposAlarma as $tipo)
                                    <option value="{{ $tipo['id'] }}" {{ old('tipo_alarma_id', $alarma['tipo_alarma_id']) == $tipo['id'] ? 'selected' : '' }}>
                                        {{ $tipo['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gravedad" class="form-label">Gravedad *</label>
                            <select class="form-select" id="gravedad" name="gravedad" required>
                                <option value="">Seleccione...</option>
                                <option value="BAJA" {{ old('gravedad', $alarma['gravedad']) == 'BAJA' ? 'selected' : '' }}>Baja</option>
                                <option value="MEDIA" {{ old('gravedad', $alarma['gravedad']) == 'MEDIA' ? 'selected' : '' }}>Media</option>
                                <option value="ALTA" {{ old('gravedad', $alarma['gravedad']) == 'ALTA' ? 'selected' : '' }}>Alta</option>
                                <option value="CRITICA" {{ old('gravedad', $alarma['gravedad']) == 'CRITICA' ? 'selected' : '' }}>Crítica</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="3" required>{{ old('descripcion', $alarma['descripcion']) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estado_atencion" class="form-label">Estado de Atención *</label>
                        <select class="form-select" id="estado_atencion" name="estado_atencion" required>
                            <option value="PENDIENTE" {{ old('estado_atencion', $alarma['estado_atencion']) == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                            <option value="EN_PROCESO" {{ old('estado_atencion', $alarma['estado_atencion']) == 'EN_PROCESO' ? 'selected' : '' }}>En Proceso</option>
                            <option value="RESUELTA" {{ old('estado_atencion', $alarma['estado_atencion']) == 'RESUELTA' ? 'selected' : '' }}>Resuelta</option>
                            <option value="IGNORADA" {{ old('estado_atencion', $alarma['estado_atencion']) == 'IGNORADA' ? 'selected' : '' }}>Ignorada</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="requiere_atencion_inmediata" 
                                   name="requiere_atencion_inmediata" value="1" 
                                   {{ old('requiere_atencion_inmediata', $alarma['requiere_atencion_inmediata']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="requiere_atencion_inmediata">
                                Requiere Atención Inmediata
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Alarma
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
