@extends('layouts.app')

@section('title', 'Nuevo Contribuyente')
@section('header', 'Registrar Nuevo Contribuyente')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Contribuyente</h5>
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
                
                <form method="POST" action="{{ route('contribuyentes.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rfc" class="form-label">RFC *</label>
                            <input type="text" class="form-control" id="rfc" name="rfc" 
                                   value="{{ old('rfc') }}" maxlength="13" required>
                            <small class="text-muted">13 caracteres para personas morales, 13 para físicas</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="razon_social" class="form-label">Razón Social *</label>
                            <input type="text" class="form-control" id="razon_social" name="razon_social" 
                                   value="{{ old('razon_social') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_comercial" class="form-label">Nombre Comercial</label>
                            <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" 
                                   value="{{ old('nombre_comercial') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="regimen_fiscal" class="form-label">Régimen Fiscal *</label>
                            <input type="text" class="form-control" id="regimen_fiscal" name="regimen_fiscal" 
                                   value="{{ old('regimen_fiscal') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="domicilio_fiscal" class="form-label">Domicilio Fiscal *</label>
                        <textarea class="form-control" id="domicilio_fiscal" name="domicilio_fiscal" 
                                  rows="2" required>{{ old('domicilio_fiscal') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal *</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                   value="{{ old('codigo_postal') }}" maxlength="5" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="{{ old('telefono') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="representante_legal" class="form-label">Representante Legal</label>
                            <input type="text" class="form-control" id="representante_legal" name="representante_legal" 
                                   value="{{ old('representante_legal') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="representante_rfc" class="form-label">RFC Representante</label>
                            <input type="text" class="form-control" id="representante_rfc" name="representante_rfc" 
                                   value="{{ old('representante_rfc') }}" maxlength="13">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="numero_permiso" class="form-label">Número de Permiso</label>
                            <input type="text" class="form-control" id="numero_permiso" name="numero_permiso" 
                                   value="{{ old('numero_permiso') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_permiso" class="form-label">Tipo de Permiso</label>
                            <input type="text" class="form-control" id="tipo_permiso" name="tipo_permiso" 
                                   value="{{ old('tipo_permiso') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_vencimiento_permiso" class="form-label">Vencimiento Permiso</label>
                            <input type="date" class="form-control datepicker" id="fecha_vencimiento_permiso" 
                                   name="fecha_vencimiento_permiso" value="{{ old('fecha_vencimiento_permiso') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estatus_verificacion" class="form-label">Estatus de Verificación</label>
                            <select class="form-select" id="estatus_verificacion" name="estatus_verificacion">
                                <option value="">Seleccione...</option>
                                <option value="ACREDITADO" {{ old('estatus_verificacion') == 'ACREDITADO' ? 'selected' : '' }}>Acreditado</option>
                                <option value="NO_ACREDITADO" {{ old('estatus_verificacion') == 'NO_ACREDITADO' ? 'selected' : '' }}>No Acreditado</option>
                                <option value="PENDIENTE" {{ old('estatus_verificacion') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                <option value="EN_PROCESO" {{ old('estatus_verificacion') == 'EN_PROCESO' ? 'selected' : '' }}>En Proceso</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_ultima_verificacion" class="form-label">Última Verificación</label>
                            <input type="date" class="form-control datepicker" id="fecha_ultima_verificacion" 
                                   name="fecha_ultima_verificacion" value="{{ old('fecha_ultima_verificacion') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_proxima_verificacion" class="form-label">Próxima Verificación</label>
                            <input type="date" class="form-control datepicker" id="fecha_proxima_verificacion" 
                                   name="fecha_proxima_verificacion" value="{{ old('fecha_proxima_verificacion') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                       {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Contribuyente Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('contribuyentes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Contribuyente
                        </button>
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
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
});
</script>
@endpush