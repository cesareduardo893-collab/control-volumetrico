@extends('layouts.app')

@section('title', 'Nueva Alarma')
@section('header', 'Nueva Alarma')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Registrar Nueva Alarma</h5>
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
                
                <form method="POST" action="{{ route('alarmas.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_registro" class="form-label">Número de Registro</label>
                            <input type="text" class="form-control" id="numero_registro" 
                                   name="numero_registro" value="{{ old('numero_registro') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                            <input type="datetime-local" class="form-control" id="fecha_hora" 
                                   name="fecha_hora" value="{{ old('fecha_hora', now()->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="componente_tipo" class="form-label">Tipo de Componente</label>
                            <select class="form-select" id="componente_tipo" name="componente_tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="tanque" {{ old('componente_tipo') == 'tanque' ? 'selected' : '' }}>Tanque</option>
                                <option value="medidor" {{ old('componente_tipo') == 'medidor' ? 'selected' : '' }}>Medidor</option>
                                <option value="dispensario" {{ old('componente_tipo') == 'dispensario' ? 'selected' : '' }}>Dispensario</option>
                                <option value="manguera" {{ old('componente_tipo') == 'manguera' ? 'selected' : '' }}>Manguera</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="componente_id" class="form-label">ID del Componente</label>
                            <input type="number" class="form-control" id="componente_id" 
                                   name="componente_id" value="{{ old('componente_id') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="componente_identificador" class="form-label">Identificador</label>
                            <input type="text" class="form-control" id="componente_identificador" 
                                   name="componente_identificador" value="{{ old('componente_identificador') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_alarma_id" class="form-label">Tipo de Alarma</label>
                            <select class="form-select" id="tipo_alarma_id" name="tipo_alarma_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($tiposAlarma as $tipo)
                                    <option value="{{ $tipo['id'] }}" {{ old('tipo_alarma_id') == $tipo['id'] ? 'selected' : '' }}>
                                        {{ $tipo['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gravedad" class="form-label">Gravedad</label>
                            <select class="form-select" id="gravedad" name="gravedad" required>
                                <option value="">Seleccione...</option>
                                <option value="BAJA" {{ old('gravedad') == 'BAJA' ? 'selected' : '' }}>Baja</option>
                                <option value="MEDIA" {{ old('gravedad') == 'MEDIA' ? 'selected' : '' }}>Media</option>
                                <option value="ALTA" {{ old('gravedad') == 'ALTA' ? 'selected' : '' }}>Alta</option>
                                <option value="CRITICA" {{ old('gravedad') == 'CRITICA' ? 'selected' : '' }}>Crítica</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="3" required>{{ old('descripcion') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="estado_atencion" class="form-label">Estado de Atención</label>
                            <select class="form-select" id="estado_atencion" name="estado_atencion" required>
                                <option value="">Seleccione...</option>
                                <option value="PENDIENTE" {{ old('estado_atencion') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                <option value="EN_PROCESO" {{ old('estado_atencion') == 'EN_PROCESO' ? 'selected' : '' }}>En Proceso</option>
                                <option value="RESUELTA" {{ old('estado_atencion') == 'RESUELTA' ? 'selected' : '' }}>Resuelta</option>
                                <option value="IGNORADA" {{ old('estado_atencion') == 'IGNORADA' ? 'selected' : '' }}>Ignorada</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="requiere_atencion_inmediata" class="form-label">Requiere Atención Inmediata</label>
                            <select class="form-select" id="requiere_atencion_inmediata" name="requiere_atencion_inmediata">
                                <option value="0" {{ old('requiere_atencion_inmediata') == '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('requiere_atencion_inmediata') == '1' ? 'selected' : '' }}>Sí</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('alarmas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Alarma
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection