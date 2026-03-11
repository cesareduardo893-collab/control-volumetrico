@extends('layouts.app')

@section('title', 'Detalle del Permiso')
@section('header', 'Detalle del Permiso')

@section('actions')
<a href="{{ route('permissions.edit', $permiso['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('permissions.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">ID:</th>
                        <td>{{ $permiso['id'] }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><strong>{{ $permiso['name'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $permiso['slug'] }}</code></td>
                    </tr>
                    <tr>
                        <th>Módulo:</th>
                        <td><span class="badge bg-info">{{ $permiso['modulo'] ?? 'General' }}</span></td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if($permiso['activo'])
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Descripción</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $permiso['description'] ?? 'No hay descripción disponible' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Roles que tienen este permiso</h5>
            </div>
            <div class="card-body">
                @if(!empty($permiso['roles']))
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Descripción</th>
                                    <th>Nivel</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permiso['roles'] as $rol)
                                    <tr>
                                        <td>
                                            <a href="{{ route('roles.show', $rol['id']) }}">
                                                <strong>{{ $rol['nombre'] }}</strong>
                                            </a>
                                        </td>
                                        <td>{{ $rol['descripcion'] ?? '-' }}</td>
                                        <td><span class="badge bg-info">{{ $rol['nivel_jerarquico'] }}</span></td>
                                        <td>
                                            @if($rol['activo'])
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Este permiso no está asignado a ningún rol</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if($permiso['activo'])
<form method="POST" action="{{ route('permissions.destroy', $permiso['id']) }}" class="d-inline"
      onsubmit="return confirm('¿Está seguro de desactivar este permiso? Los roles perderán este permiso.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Desactivar Permiso
    </button>
</form>
@endif
@endsection