@extends('layouts.app')

@section('title', 'Nuevo Certificado de Verificación')
@section('header', 'Nuevo Certificado de Verificación')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Registrar Nuevo Certificado</h5>
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
                
                <form method="POST" action="{{ route('certificados-verificacion.store') }}" id="certificadoForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="folio" class="form-label">Folio *</label>
                            <input type="text" class="form-control" id="folio" name="folio" 
                                   value="{{ old('folio') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="contribuyente_id" class="form-label">Contribuyente *</label>
                            <select class="form-select select2" id="contribuyente_id" name="contribuyente_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($contribuyentes as $contribuyente)
                                    <option value="{{ $contribuyente['id'] }}" {{ old('contribuyente_id') == $contribuyente['id'] ? 'selected' : '' }}>
                                        {{ $contribuyente['razon_social'] }} ({{ $contribuyente['rfc'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                            <input type="date" class="form-control datepicker" id="fecha_emision" 
                                   name="fecha_emision" value="{{ old('fecha_emision', now()->toDateString()) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="proveedor_rfc" class="form-label">RFC del Proveedor *</label>
                            <input type="text" class="form-control" id="proveedor_rfc" name="proveedor_rfc" 
                                   value="{{ old('proveedor_rfc') }}" maxlength="13" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="proveedor_nombre" class="form-label">Nombre del Proveedor *</label>
                            <input type="text" class="form-control" id="proveedor_nombre" name="proveedor_nombre" 
                                   value="{{ old('proveedor_nombre') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_inicio_verificacion" class="form-label">Fecha Inicio Verificación *</label>
                            <input type="date" class="form-control datepicker" id="fecha_inicio_verificacion" 
                                   name="fecha_inicio_verificacion" value="{{ old('fecha_inicio_verificacion') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_fin_verificacion" class="form-label">Fecha Fin Verificación *</label>
                            <input type="date" class="form-control datepicker" id="fecha_fin_verificacion" 
                                   name="fecha_fin_verificacion" value="{{ old('fecha_fin_verificacion') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="resultado" class="form-label">Resultado *</label>
                            <select class="form-select" id="resultado" name="resultado" required>
                                <option value="">Seleccione...</option>
                                <option value="acreditado" {{ old('resultado') == 'acreditado' ? 'selected' : '' }}>Acreditado</option>
                                <option value="no_acreditado" {{ old('resultado') == 'no_acreditado' ? 'selected' : '' }}>No Acreditado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_caducidad" class="form-label">Fecha de Caducidad</label>
                            <input type="date" class="form-control datepicker" id="fecha_caducidad" 
                                   name="fecha_caducidad" value="{{ old('fecha_caducidad') }}">
                            <small class="text-muted">Dejar en blanco si no aplica</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="numero_permiso" class="form-label">Número de Permiso</label>
                            <input type="text" class="form-control" id="numero_permiso" name="numero_permiso" 
                                   value="{{ old('numero_permiso') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tabla de Cumplimiento *</label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="cumplimientoTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Cumple</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Documentación completa</td>
                                        <td>
                                            <select class="form-select" name="tabla_cumplimiento[documentacion]">
                                                <option value="SI">Sí</option>
                                                <option value="NO">No</option>
                                                <option value="N/A">No Aplica</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tabla_cumplimiento[documentacion_obs]">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Equipo de medición calibrado</td>
                                        <td>
                                            <select class="form-select" name="tabla_cumplimiento[equipo_calibrado]">
                                                <option value="SI">Sí</option>
                                                <option value="NO">No</option>
                                                <option value="N/A">No Aplica</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tabla_cumplimiento[equipo_calibrado_obs]">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Registros volumétricos actualizados</td>
                                        <td>
                                            <select class="form-select" name="tabla_cumplimiento[registros_actualizados]">
                                                <option value="SI">Sí</option>
                                                <option value="NO">No</option>
                                                <option value="N/A">No Aplica</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tabla_cumplimiento[registros_actualizados_obs]">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sistema de medición funciona correctamente</td>
                                        <td>
                                            <select class="form-select" name="tabla_cumplimiento[sistema_funciona]">
                                                <option value="SI">Sí</option>
                                                <option value="NO">No</option>
                                                <option value="N/A">No Aplica</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tabla_cumplimiento[sistema_funciona_obs]">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Precintos de seguridad intactos</td>
                                        <td>
                                            <select class="form-select" name="tabla_cumplimiento[precintos]">
                                                <option value="SI">Sí</option>
                                                <option value="NO">No</option>
                                                <option value="N/A">No Aplica</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tabla_cumplimiento[precintos_obs]">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Producto coincide con lo declarado</td>
                                        <td>
                                            <select class="form-select" name="tabla_cumplimiento[producto_declarado]">
                                                <option value="SI">Sí</option>
                                                <option value="NO">No</option>
                                                <option value="N/A">No Aplica</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="tabla_cumplimiento[producto_declarado_obs]">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('certificados-verificacion.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Certificado
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
    
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Validación de fechas
    $('#fecha_inicio_verificacion, #fecha_fin_verificacion').change(function() {
        let inicio = $('#fecha_inicio_verificacion').val();
        let fin = $('#fecha_fin_verificacion').val();
        
        if (inicio && fin && inicio > fin) {
            alert('La fecha de fin debe ser posterior a la fecha de inicio');
            $('#fecha_fin_verificacion').val('');
        }
    });
});
</script>
@endpush