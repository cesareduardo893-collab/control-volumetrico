@extends('layouts.app')

@section('title', 'Nuevo Contribuyente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Contribuyente</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('contribuyentes.store') }}" id="contribuyenteForm">
                    @csrf
                    
                    <!-- Datos Fiscales -->
                    <h5 class="mb-3">Datos Fiscales</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rfc" class="form-label">RFC <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('rfc') is-invalid @enderror" 
                                       id="rfc" 
                                       name="rfc" 
                                       value="{{ old('rfc') }}" 
                                       maxlength="13"
                                       required>
                                @error('rfc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('razon_social') is-invalid @enderror" 
                                       id="razon_social" 
                                       name="razon_social" 
                                       value="{{ old('razon_social') }}" 
                                       required>
                                @error('razon_social')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_comercial" class="form-label">Nombre Comercial</label>
                                <input type="text" 
                                       class="form-control @error('nombre_comercial') is-invalid @enderror" 
                                       id="nombre_comercial" 
                                       name="nombre_comercial" 
                                       value="{{ old('nombre_comercial') }}">
                                @error('nombre_comercial')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="regimen_fiscal" class="form-label">Régimen Fiscal <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('regimen_fiscal') is-invalid @enderror" 
                                       id="regimen_fiscal" 
                                       name="regimen_fiscal" 
                                       value="{{ old('regimen_fiscal') }}" 
                                       required>
                                @error('regimen_fiscal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Domicilio Fiscal -->
                    <h5 class="mb-3 mt-4">Domicilio Fiscal</h5>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="domicilio_fiscal" class="form-label">Domicilio Fiscal <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('domicilio_fiscal') is-invalid @enderror" 
                                       id="domicilio_fiscal" 
                                       name="domicilio_fiscal" 
                                       value="{{ old('domicilio_fiscal') }}" 
                                       required>
                                @error('domicilio_fiscal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codigo_postal" class="form-label">Código Postal <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('codigo_postal') is-invalid @enderror" 
                                       id="codigo_postal" 
                                       name="codigo_postal" 
                                       value="{{ old('codigo_postal') }}" 
                                       maxlength="5"
                                       required>
                                @error('codigo_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contacto -->
                    <h5 class="mb-3 mt-4">Contacto</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Representante Legal -->
                    <h5 class="mb-3 mt-4">Representante Legal</h5>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="representante_legal" class="form-label">Nombre del Representante</label>
                                <input type="text" 
                                       class="form-control @error('representante_legal') is-invalid @enderror" 
                                       id="representante_legal" 
                                       name="representante_legal" 
                                       value="{{ old('representante_legal') }}">
                                @error('representante_legal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="representante_rfc" class="form-label">RFC Representante</label>
                                <input type="text" 
                                       class="form-control @error('representante_rfc') is-invalid @enderror" 
                                       id="representante_rfc" 
                                       name="representante_rfc" 
                                       value="{{ old('representante_rfc') }}" 
                                       maxlength="13">
                                @error('representante_rfc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carácter y Permisos -->
                    <h5 class="mb-3 mt-4">Carácter y Permisos</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="caracter_actua" class="form-label">Carácter <span class="text-danger">*</span></label>
                                <select class="form-select @error('caracter_actua') is-invalid @enderror" 
                                        id="caracter_actua" 
                                        name="caracter_actua" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="contratista" {{ old('caracter_actua') == 'contratista' ? 'selected' : '' }}>Contratista</option>
                                    <option value="asignatario" {{ old('caracter_actua') == 'asignatario' ? 'selected' : '' }}>Asignatario</option>
                                    <option value="permisionario" {{ old('caracter_actua') == 'permisionario' ? 'selected' : '' }}>Permisionario</option>
                                    <option value="usuario" {{ old('caracter_actua') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                                </select>
                                @error('caracter_actua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero_permiso" class="form-label">Número de Permiso</label>
                                <input type="text" 
                                       class="form-control @error('numero_permiso') is-invalid @enderror" 
                                       id="numero_permiso" 
                                       name="numero_permiso" 
                                       value="{{ old('numero_permiso') }}">
                                @error('numero_permiso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_permiso" class="form-label">Tipo de Permiso</label>
                                <input type="text" 
                                       class="form-control @error('tipo_permiso') is-invalid @enderror" 
                                       id="tipo_permiso" 
                                       name="tipo_permiso" 
                                       value="{{ old('tipo_permiso') }}">
                                @error('tipo_permiso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Proveedor de Equipos -->
                    <h5 class="mb-3 mt-4">Proveedor de Equipos</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="proveedor_equipos_rfc" class="form-label">RFC Proveedor</label>
                                <input type="text" 
                                       class="form-control @error('proveedor_equipos_rfc') is-invalid @enderror" 
                                       id="proveedor_equipos_rfc" 
                                       name="proveedor_equipos_rfc" 
                                       value="{{ old('proveedor_equipos_rfc') }}" 
                                       maxlength="13">
                                @error('proveedor_equipos_rfc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="proveedor_equipos_nombre" class="form-label">Nombre del Proveedor</label>
                                <input type="text" 
                                       class="form-control @error('proveedor_equipos_nombre') is-invalid @enderror" 
                                       id="proveedor_equipos_nombre" 
                                       name="proveedor_equipos_nombre" 
                                       value="{{ old('proveedor_equipos_nombre') }}">
                                @error('proveedor_equipos_nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="activo" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('contribuyentes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Validación de RFC
    $('#rfc').on('input', function() {
        let value = $(this).val().toUpperCase();
        $(this).val(value);
        
        // Validar formato de RFC
        let rfcPattern = /^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
        if (value.length === 12 || value.length === 13) {
            if (!rfcPattern.test(value)) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').text('El RFC no tiene un formato válido');
            } else {
                $(this).removeClass('is-invalid');
            }
        }
    });
    
    // Validación de código postal
    $('#codigo_postal').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });
});
</script>
@endpush