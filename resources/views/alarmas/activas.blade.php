@extends('layouts.app')

@section('title', 'Alarmas Activas')
@section('header', 'Alarmas Activas')

@section('actions')
<a href="{{ route('alarmas.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-list"></i> Ver Todas
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros rápidos -->
        <form method="GET" action="{{ route('alarmas.activas') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="componente_tipo" class="form-label">Tipo de Componente</label>
                <select class="form-select" id="componente_tipo" name="componente_tipo">
                    <option value="">Todos</option>
                    <option value="tanque" {{ request('componente_tipo') == 'tanque' ? 'selected' : '' }}>Tanque</option>
                    <option value="medidor" {{ request('componente_tipo') == 'medidor' ? 'selected' : '' }}>Medidor</option>
                    <option value="dispensario" {{ request('componente_tipo') == 'dispensario' ? 'selected' : '' }}>Dispensario</option>
                    <option value="manguera" {{ request('componente_tipo') == 'manguera' ? 'selected' : '' }}>Manguera</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="gravedad" class="form-label">Gravedad</label>
                <select class="form-select" id="gravedad" name="gravedad">
                    <option value="">Todas</option>
                    <option value="BAJA" {{ request('gravedad') == 'BAJA' ? 'selected' : '' }}>Baja</option>
                    <option value="MEDIA" {{ request('gravedad') == 'MEDIA' ? 'selected' : '' }}>Media</option>
                    <option value="ALTA" {{ request('gravedad') == 'ALTA' ? 'selected' : '' }}>Alta</option>
                    <option value="CRITICA" {{ request('gravedad') == 'CRITICA' ? 'selected' : '' }}>Crítica</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="componente_id" class="form-label">ID del Componente</label>
                <input type="number" class="form-control" id="componente_id" 
                       name="componente_id" value="{{ request('componente_id') }}">
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('alarmas.activas') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Críticas</h6>
                        <h3>{{ collect($alarmas)->where('gravedad', 'CRITICA')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Altas</h6>
                        <h3>{{ collect($alarmas)->where('gravedad', 'ALTA')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Medias</h6>
                        <h3>{{ collect($alarmas)->where('gravedad', 'MEDIA')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h6>Bajas</h6>
                        <h3>{{ collect($alarmas)->where('gravedad', 'BAJA')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de alarmas activas -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Componente</th>
                        <th>Gravedad</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alarmas as $alarma)
                        <tr>
                            <td>{{ $alarma['fecha_hora'] }}</td>
                            <td>
                                {{ $alarma['componente_tipo'] }}<br>
                                <small>{{ $alarma['componente_identificador'] }}</small>
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'BAJA' => 'info',
                                        'MEDIA' => 'warning',
                                        'ALTA' => 'danger',
                                        'CRITICA' => 'dark'
                                    ][$alarma['gravedad']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $alarma['gravedad'] }}</span>
                            </td>
                            <td>{{ Str::limit($alarma['descripcion'], 50) }}</td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'PENDIENTE' => 'danger',
                                        'EN_PROCESO' => 'warning'
                                    ][$alarma['estado_atencion']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $alarma['estado_atencion'] }}</span>
                            </td>
                            <td>
                                <a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($alarma['estado_atencion'] == 'PENDIENTE')
                                    <a href="{{ route('alarmas.atender.form', $alarma['id']) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay alarmas activas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection