@extends('layouts.app')

@section('title', 'Permisos por Módulo')
@section('header', 'Permisos Agrupados por Módulo')

@section('actions')
<a href="{{ route('permissions.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-list"></i> Ver Todos
</a>
<a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Permiso
</a>
@endsection

@section('content')
<div class="row">
    @forelse($permisosPorModulo as $modulo => $permisos)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        {{ $modulo }}
                        <span class="badge bg-light text-dark float-end">{{ count($permisos) }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Slug</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permisos as $permiso)
                                    <tr>
                                        <td>
                                            <strong>{{ $permiso['name'] }}</strong>
                                            @if(!empty($permiso['description']))
                                                <br><small class="text-muted">{{ $permiso['description'] }}</small>
                                            @endif
                                        </td>
                                        <td><code>{{ $permiso['slug'] }}</code></td>
                                        <td>
                                            @if($permiso['activo'])
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('permissions.show', $permiso['id']) }}" 
                                                   class="btn btn-sm btn-info" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('permissions.edit', $permiso['id']) }}" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No hay permisos registrados
            </div>
        </div>
    @endforelse
</div>
@endsection