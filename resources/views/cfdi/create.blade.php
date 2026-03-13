@extends('layouts.app')

@section('title', 'Nuevo CFDI')
@section('header', 'Registrar Nuevo CFDI')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del CFDI</h5>
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
                
                <form method="POST" action="{{ route('cfdi.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="uuid" class="form-label">UUID *</label>
                            <input type="text" class="form-control" id="uuid" name="uuid" 
                                   value="{{ old('uuid') }}" maxlength="36" required>
                            <small class="text-muted">Formato: 8-4-4-4-12 (ej: 123e4567-e89b-12d3-a456-426614174000)</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                            <input type="date" class="form-control datepicker" id="fecha_emision" 
                                   name="fecha_emision" value="{{ old('fecha_emision', now()->toDateString()) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="rfc_emisor" class="form-label">RFC Emisor *</label>
                            <input type="text" class="form-control" id="rfc_emisor" name="rfc_emisor" 
                                   value="{{ old('rfc_emisor') }}" maxlength="13" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="rfc_receptor" class="form-label">RFC Receptor *</label>
                            <input type="text" class="form-control" id="rfc_receptor" name="rfc_receptor" 
                                   value="{{ old('rfc_receptor') }}" maxlength="13" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="tipo_operacion" class="form-label">Tipo de Operación *</label>
                            <select class="form-select" id="tipo_operacion" name="tipo_operacion" required>
                                <option value="">Seleccione...</option>
                                <option value="adquisicion" {{ old('tipo_operacion') == 'adquisicion' ? 'selected' : '' }}>Adquisición</option>
                                <option value="enajenacion" {{ old('tipo_operacion') == 'enajenacion' ? 'selected' : '' }}>Enajenación</option>
                                <option value="servicio" {{ old('tipo_operacion') == 'servicio' ? 'selected' : '' }}>Servicio</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="VIGENTE" {{ old('estado', 'VIGENTE') == 'VIGENTE' ? 'selected' : '' }}>Vigente</option>
                                <option value="CANCELADO" {{ old('estado') == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="subtotal" class="form-label">Subtotal *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       id="subtotal" name="subtotal" value="{{ old('subtotal', '0.00') }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="iva" class="form-label">IVA *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       id="iva" name="iva" value="{{ old('iva', '0.00') }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="ieps" class="form-label">IEPS *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       id="ieps" name="ieps" value="{{ old('ieps', '0.00') }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="total" class="form-label">Total *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       id="total" name="total" value="{{ old('total') }}" required readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="producto_id" class="form-label">Producto</label>
                            <select class="form-select select2" id="producto_id" name="producto_id">
                                <option value="">Seleccione (opcional)</option>
                                @foreach($productos as $producto)
                                    @php
                                        $pid = is_array($producto) ? ($producto['id'] ?? $producto['ID'] ?? null)
                                            : (is_object($producto) ? ($producto->id ?? $producto->ID ?? null) : $producto);
                                        $pName = is_array($producto) ? ($producto['nombre'] ?? $producto['name'] ?? '')
                                            : (is_object($producto) ? ($producto->nombre ?? $producto->name ?? '') : '');
                                    @endphp
                                    @if ($pid !== null)
                                        <option value="{{ $pid }}" {{ old('producto_id') == $pid ? 'selected' : '' }}>
                                            {{ $pName ?: (string)$pid }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="volumen" class="form-label">Volumen</label>
                            <div class="input-group">
                                <input type="number" step="0.001" min="0" class="form-control" 
                                       id="volumen" name="volumen" value="{{ old('volumen') }}">
                                <span class="input-group-text">L</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="registro_volumetrico_id" class="form-label">Registro Volumétrico</label>
                            <input type="number" class="form-control" id="registro_volumetrico_id" 
                                   name="registro_volumetrico_id" value="{{ old('registro_volumetrico_id') }}">
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cfdi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar CFDI
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
    
    // Calcular total automáticamente
    function calcularTotal() {
        let subtotal = parseFloat($('#subtotal').val()) || 0;
        let iva = parseFloat($('#iva').val()) || 0;
        let ieps = parseFloat($('#ieps').val()) || 0;
        let total = subtotal + iva + ieps;
        $('#total').val(total.toFixed(2));
    }
    
    $('#subtotal, #iva, #ieps').on('input', calcularTotal);
    calcularTotal();
});
</script>
@endpush
