@extends('layouts.app')

@section('title', 'Atender Alarma')
@section('header', 'Atender Alarma')

@section('actions')
<a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">Atender Alarma</h5>
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
                    <strong>Gravedad:</strong> 
                    <span class="badge bg-{{ $alarma['gravedad'] == 'CRITICA' ? 'danger' : ($alarma['gravedad'] == 'ALTA' ? 'warning' : ($alarma['gravedad'] == 'MEDIA' ? 'info' : 'secondary')) }}">
                        {{ $alarma['gravedad'] }}
                    </span>
                </div>
                
                <form method="POST" action="{{ route('alarmas.atender', $alarma['id']) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="estado_atencion" class="form-label">Estado de Atención *</label>
                        <select class="form-select" id="estado_atencion" name="estado_atencion" required>
                            <option value="EN_PROCESO" {{ old('estado_atencion') == 'EN_PROCESO' ? 'selected' : '' }}>En Proceso</option>
                            <option value="RESUELTA" {{ old('estado_atencion') == 'RESUELTA' ? 'selected' : '' }}>Resuelta</option>
                            <option value="IGNORADA" {{ old('estado_atencion') == 'IGNORADA' ? 'selected' : '' }}>Ignorada</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="acciones_tomadas" class="form-label">Acciones Tomadas *</label>
                        <textarea class="form-control" id="acciones_tomadas" name="acciones_tomadas" 
                                  rows="4" required>{{ old('acciones_tomadas') }}</textarea>
                        <small class="text-muted">Describa las acciones realizadas para atender la alarma</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle"></i> Atender Alarma
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
