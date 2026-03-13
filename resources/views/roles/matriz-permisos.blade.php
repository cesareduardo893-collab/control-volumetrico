@extends('layouts.app')

@section('title', 'Matriz de Permisos')
@section('header', 'Matriz de Permisos por Rol')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Módulo / Permiso</th>
                        @foreach($roles as $role)
                            <th class="text-center" style="min-width: 120px;">
                                {{ $role['nombre'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentModule = null;
                    @endphp
                    @foreach($permisos as $permiso)
                        @if($currentModule !== $permiso['modulo'])
                            @php
                                $currentModule = $permiso['modulo'];
                            @endphp
                            <tr class="table-secondary">
                                <td colspan="{{ count($roles) + 1 }}">
                                    <strong><i class="bi bi-folder"></i> {{ $permiso['modulo'] }}</strong>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <small>{{ $permiso['name'] }}</small>
                                <br>
                                <span class="text-muted small">{{ $permiso['slug'] }}</span>
                            </td>
                            @foreach($roles as $role)
                                @php
                                    $hasPermission = isset($matriz[$role['id']]) && 
                                                   in_array($permiso['id'], $matriz[$role['id']]);
                                @endphp
                                <td class="text-center">
                                    @if($hasPermission)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-lg"></i>
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash-lg"></i>
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Roles
            </a>
            <div>
                <span class="badge bg-success me-2"><i class="bi bi-check-lg"></i> Tiene permiso</span>
                <span class="badge bg-secondary"><i class="bi bi-dash-lg"></i> No tiene permiso</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable for better scrolling
    $('.table').DataTable({
        responsive: true,
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        scrollX: true,
        scrollY: '60vh',
        scrollCollapse: true
    });
});
</script>
@endpush
