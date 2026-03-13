@extends('layouts.app')

@section('title', 'Nuevo Registro de Existencia')
@section('header', 'Registrar Nueva Existencia')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información de la Existencia</h5>
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
                
                <form method="POST" action="{{ route('existencias.store') }}" id="existenciaForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="numero_registro" class="form-label">Número de Registro *</label>
                            <input type="text" class="form-control" id="numero_registro" name="numero_registro" 
                                   value="{{ old('numero_registro') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control datepicker" id="fecha" name="fecha" 
                                   value="{{ old('fecha', now()->toDateString()) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="hora" class="form-label">Hora *</label>
                            <input type="time" class="form-control" id="hora" name="hora" 
                                   value="{{ old('hora', now()->format('H:i:s')) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanque_id" class="form-label">Tanque *</label>
                            <select class="form-select select2" id="tanque_id" name="tanque_id" required>
                                <option value="">Seleccione...</option>
                        @foreach($tanques as $tanque)
                                    @php
                                        $id = data_get($tanque, 'id');
                                        $identificador = data_get($tanque, 'identificador');
                                        $instalacionNombre = data_get($tanque, 'instalacion.nombre', '');
                                        $productoNombre = data_get($tanque, 'producto.nombre', null);
                                    @endphp
                                    <option value="{{ $id }}" {{ old('tanque_id') == $id ? 'selected' : '' }}>
                                        {{ $identificador }} - {{ $instalacionNombre }}
                                        @if($productoNombre)
                                            ({{ $productoNombre }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="producto_id" class="form-label">Producto *</label>
                            <select class="form-select select2" id="producto_id" name="producto_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($productos as $producto)
                                    @php
                                        $pid = data_get($producto, 'id');
                                        $pName = data_get($producto, 'nombre');
                                        $pCode = data_get($producto, 'clave_sat');
                                    @endphp
                                    <option value="{{ $pid }}" {{ old('producto_id') == $pid ? 'selected' : '' }}>
                                        {{ $pName }} @if($pCode) ({{ $pCode }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="volumen_medido" class="form-label">Volumen Medido (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_medido" name="volumen_medido" value="{{ old('volumen_medido') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="temperatura" class="form-label">Temperatura (°C) *</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="temperatura" name="temperatura" value="{{ old('temperatura', 15) }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="densidad" class="form-label">Densidad (kg/L)</label>
                            <input type="number" step="0.0001" min="0" class="form-control" 
                                   id="densidad" name="densidad" value="{{ old('densidad') }}">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_corregido" class="form-label">Volumen Corregido (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_corregido" name="volumen_corregido" value="{{ old('volumen_corregido') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="volumen_disponible" class="form-label">Volumen Disponible (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_disponible" name="volumen_disponible" value="{{ old('volumen_disponible') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_agua" class="form-label">Volumen de Agua (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_agua" name="volumen_agua" value="{{ old('volumen_agua', 0) }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_sedimentos" class="form-label">Volumen Sedimentos (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_sedimentos" name="volumen_sedimentos" value="{{ old('volumen_sedimentos', 0) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_registro" class="form-label">Tipo de Registro *</label>
                            <select class="form-select" id="tipo_registro" name="tipo_registro" required>
                                <option value="">Seleccione...</option>
                                <option value="inicial" {{ old('tipo_registro') == 'inicial' ? 'selected' : '' }}>Inicial</option>
                                <option value="operacion" {{ old('tipo_registro') == 'operacion' ? 'selected' : '' }}>Operación</option>
                                <option value="final" {{ old('tipo_registro') == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_movimiento" class="form-label">Tipo de Movimiento *</label>
                            <select class="form-select" id="tipo_movimiento" name="tipo_movimiento" required>
                                <option value="">Seleccione...</option>
                                <option value="INICIAL" {{ old('tipo_movimiento') == 'INICIAL' ? 'selected' : '' }}>Inicial</option>
                                <option value="RECEPCION" {{ old('tipo_movimiento') == 'RECEPCION' ? 'selected' : '' }}>Recepción</option>
                                <option value="ENTREGA" {{ old('tipo_movimiento') == 'ENTREGA' ? 'selected' : '' }}>Entrega</option>
                                <option value="VENTA" {{ old('tipo_movimiento') == 'VENTA' ? 'selected' : '' }}>Venta</option>
                                <option value="TRASPASO" {{ old('tipo_movimiento') == 'TRASPASO' ? 'selected' : '' }}>Traspaso</option>
                                <option value="AJUSTE" {{ old('tipo_movimiento') == 'AJUSTE' ? 'selected' : '' }}>Ajuste</option>
                                <option value="INVENTARIO" {{ old('tipo_movimiento') == 'INVENTARIO' ? 'selected' : '' }}>Inventario</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="PENDIENTE" {{ old('estado', 'PENDIENTE') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                <option value="VALIDADO" {{ old('estado') == 'VALIDADO' ? 'selected' : '' }}>Validado</option>
                                <option value="EN_REVISION" {{ old('estado') == 'EN_REVISION' ? 'selected' : '' }}>En Revisión</option>
                                <option value="CON_ALARMA" {{ old('estado') == 'CON_ALARMA' ? 'selected' : '' }}>Con Alarma</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('existencias.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Existencia
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
    
    // Calcular volumen corregido cuando cambia temperatura o densidad
    $('#volumen_medido, #temperatura, #densidad').on('input', function() {
        calcularVolumenCorregido();
    });
    
    function calcularVolumenCorregido() {
        let volumen = parseFloat($('#volumen_medido').val()) || 0;
        let temperatura = parseFloat($('#temperatura').val()) || 15;
        let densidad = parseFloat($('#densidad').val()) || 0.85;
        
        // Fórmula simplificada para corrección por temperatura
        // En producción usar la fórmula oficial
        let factorCorreccion = 1 - (0.0012 * (temperatura - 15));
        let volumenCorregido = volumen * factorCorreccion;
        
        $('#volumen_corregido').val(volumenCorregido.toFixed(3));
    }
});
</script>
@endpush
