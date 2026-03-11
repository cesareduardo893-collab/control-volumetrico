@extends('layouts.app')

@section('title', 'Permisos del Usuario')
@section('header', 'Permisos del Usuario')

@section('actions')
<a href="{{ route('users.show', $id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Usuario
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Resumen de Permisos</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="bi bi-shield-lock fs-1"></i>
                    <h5>Total de Permisos</h5>
                    <h2>{{ $permisos['total'] ?? 0 }}</h2>
                </div>
                
                <hr>
                
                <h6>Distribución por Módulo:</h6>
                @foreach($permisos['por_modulo'] ?? [] as $modulo => $count)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $modulo }}</span>
                        <span class="badge bg-primary">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Permisos por Módulo</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="permisosAccordion">
                    @forelse($permisos['por_modulo_detalle'] ?? [] as $modulo => $moduloPermisos)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" 
                                        type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ $loop->index }}">
                                    {{ $modulo }} 
                                    <span class="badge bg-primary ms-2">{{ count($moduloPermisos) }}</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $loop->index }}" 
                                 class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                 data-bs-parent="#permisosAccordion">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Permiso</th>
                                                    <th>Slug</th>
                                                    <th>Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($moduloPermisos as $permiso)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-success">✓</span>
                                                            {{ $permiso['name'] }}
                                                        </td>
                                                        <td><code>{{ $permiso['slug'] }}</code></td>
                                                        <td>{{ $permiso['description'] ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">El usuario no tiene permisos asignados</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection