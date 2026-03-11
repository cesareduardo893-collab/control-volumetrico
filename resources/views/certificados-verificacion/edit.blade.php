@extends('layouts.app')

@section('title', 'Editar Certificado')
@section('header', 'Editar Certificado de Verificación')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Certificado</h5>
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
                
                <form method="POST" action="{{ route('certificados-verificacion.update', $certificado['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="folio" class="form-label">Folio</label>
                            <input type="text" class="form-control" id="folio" 
                                   value="{{ $certificado['folio'] }}" disabled readonly>
                            <small class="text-muted">El folio no puede ser modificado</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_emision" class="form-label">Fecha de Emisión</label>
                            <input type="date" class="form-control" id="fecha_emision" 
                                   value="{{ $certificado['fecha_emision'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="resultado" class="form-label">Resultado</label>
                            <input type="text" class="form-control" id="resultado" 
                                   value="{{ ucfirst($certificado['resultado']) }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="proveedor_rfc" class="form-label">RFC Proveedor</label>
                            <input type="text" class="form-control" id="proveedor_rfc" 
                                   value="{{ $certificado['proveedor_rfc'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="proveedor_nombre" class="form-label">Nombre Proveedor</label>
                            <input type="text" class="form-control" id="proveedor_nombre" 
                                   value="{{ $certificado['proveedor_nombre'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_caducidad" class="form-label">Fecha de Caducidad</label>
                            <input type="date" class="form-control datepicker" id="fecha_caducidad" 
                                   name="fecha_caducidad" value="{{ old('fecha_caducidad', $certificado['fecha_caducidad'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="vigente" name="vigente" value="1"
                                       {{ old('vigente', $certificado['vigente'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="vigente">Certificado Vigente</label>
                            </div>
                            
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="requiere_verificacion_extraordinaria" 
                                       name="requiere_verificacion_extraordinaria" value="1"
                                       {{ old('requiere_verificacion_extraordinaria', $certificado['requiere_verificacion_extraordinaria'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="requiere_verificacion_extraordinaria">
                                    Requiere Verificación Extraordinaria
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones', $certificado['observaciones'] ?? '') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('certificados-verificacion.show', $certificado['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Certificado
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