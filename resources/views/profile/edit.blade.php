@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Mi Perfil</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Personal -->
                    <h5 class="mb-3">Información Personal</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nombres') is-invalid @enderror" 
                                       id="nombres" 
                                       name="nombres" 
                                       value="{{ old('nombres', $user['nombres'] ?? '') }}" 
                                       required>
                                @error('nombres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('apellidos') is-invalid @enderror" 
                                       id="apellidos" 
                                       name="apellidos" 
                                       value="{{ old('apellidos', $user['apellidos'] ?? '') }}" 
                                       required>
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user['email'] ?? '') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono', $user['telefono'] ?? '') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                          id="direccion" 
                                          name="direccion" 
                                          rows="2">{{ old('direccion', $user['direccion'] ?? '') }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Perfil
                            </button>
                            <a href="{{ route('profile.change-password') }}" class="btn btn-warning">
                                <i class="fas fa-key"></i> Cambiar Contraseña
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-3">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Roles y Permisos</h6>
            </div>
            <div class="card-body">
                <h6 class="mb-3">Roles asignados:</h6>
                @forelse($user['roles'] ?? [] as $role)
                    <span class="badge bg-info mb-2 p-2">{{ $role['display_name'] }}</span>
                @empty
                    <p class="text-muted">No tiene roles asignados</p>
                @endforelse
                
                <h6 class="mb-3 mt-4">Permisos directos:</h6>
                @forelse($user['permissions'] ?? [] as $permission)
                    <span class="badge bg-secondary mb-1">{{ $permission['display_name'] }}</span>
                @empty
                    <p class="text-muted">No tiene permisos directos asignados</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Última Actividad</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Último acceso:</th>
                        <td>{{ $user['last_login'] ?? 'Nunca' }}</td>
                    </tr>
                    <tr>
                        <th>Última IP:</th>
                        <td>{{ $user['last_ip'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Miembro desde:</th>
                        <td>{{ $user['created_at'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Última actualización:</th>
                        <td>{{ $user['updated_at'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Validación de teléfono (solo números)
    $('#telefono').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });
});
</script>
@endpush