@extends('layouts.app')

@section('title', 'Nuevo Registro Volumétrico')
@section('header', 'Registrar Nuevo Registro Volumétrico')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Registro Volumétrico</h5>
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
                
                <form method="POST" action="{{ route('registros-volumetricos.store') }}" id="registroForm">
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
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($instalaciones as $instalacion)
                                    @php
                                        if (is_array($instalacion)) {
                                            $id = $instalacion['id'] ?? '';
                                            $nombre = $instalacion['nombre'] ?? '';
                                        } elseif (is_object($instalacion)) {
                                            $id = $instalacion->id ?? '';
                                            $nombre = $instalacion->nombre ?? '';
                                        } else {
                                            $id = (string) $instalacion;
                                            $nombre = (string) $instalacion;
                                        }
                                    @endphp
                                    <option value="{{ $id }}" {{ old('instalacion_id') == $id ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tanque_id" class="form-label">Tanque *</label>
                            <select class="form-select select2" id="tanque_id" name="tanque_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($tanques as $tanque)
                                    @php
                                        if (is_array($tanque)) {
                                            $tid = $tanque['id'] ?? '';
                                            $identificador = $tanque['identificador'] ?? '';
                                            $instNombre = $tanque['instalacion']['nombre'] ?? '';
                                        } elseif (is_object($tanque)) {
                                            $tid = $tanque->id ?? '';
                                            $identificador = $tanque->identificador ?? '';
                                            $instNombre = !empty($tanque->instalacion->nombre) ? $tanque->instalacion->nombre : '';
                                        } else {
                                            $tid = (string) $tanque;
                                            $identificador = $tid;
                                            $instNombre = '';
                                        }
                                        $textoTanque = trim($identificador . (!empty($instNombre) ? ' - ' . $instNombre : ''));
                                    @endphp
                                    <option value="{{ $tid }}" {{ old('tanque_id') == $tid ? 'selected' : '' }}>
                                        {{ $textoTanque ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="producto_id" class="form-label">Producto *</label>
                            <select class="form-select select2" id="producto_id" name="producto_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($productos as $producto)
                                    @php
                                        if (is_array($producto)) {
                                            $pid = $producto['id'] ?? '';
                                            $nombreP = $producto['nombre'] ?? '';
                                        } elseif (is_object($producto)) {
                                            $pid = $producto->id ?? '';
                                            $nombreP = $producto->nombre ?? '';
                                        } else {
                                            $pid = (string)$producto;
                                            $nombreP = (string)$producto;
                                        }
                                    @endphp
                                    <option value="{{ $pid }}" {{ old('producto_id') == $pid ? 'selected' : '' }}>
                                        {{ $nombreP }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="hora_inicio" class="form-label">Hora Inicio *</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                                   value="{{ old('hora_inicio', '00:00:00') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="hora_fin" class="form-label">Hora Fin *</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                                   value="{{ old('hora_fin', '23:59:59') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="medidor_id" class="form-label">Medidor</label>
                            <select class="form-select select2" id="medidor_id" name="medidor_id">
                                <option value="">Seleccione (opcional)</option>
                                @foreach($medidores as $medidor)
                                    @php
                                        if (is_array($medidor)) {
                                            $mid = $medidor['id'] ?? '';
                                            $clave = $medidor['clave'] ?? '';
                                            $ns = $medidor['numero_serie'] ?? '';
                                        } elseif (is_object($medidor)) {
                                            $mid = $medidor->id ?? '';
                                            $clave = $medidor->clave ?? '';
                                            $ns = $medidor->numero_serie ?? '';
                                        } else {
                                            $mid = (string)$medidor;
                                            $clave = $mid;
                                            $ns = '';
                                        }
                                    @endphp
                                    <option value="{{ $mid }}" {{ old('medidor_id') == $mid ? 'selected' : '' }}>
                                        {{ $clave }} - {{ $ns }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="volumen_inicial" class="form-label">Volumen Inicial (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_inicial" name="volumen_inicial" value="{{ old('volumen_inicial') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_final" class="form-label">Volumen Final (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_final" name="volumen_final" value="{{ old('volumen_final') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_operacion" class="form-label">Volumen Operación (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_operacion" name="volumen_operacion" value="{{ old('volumen_operacion') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_corregido" class="form-label">Volumen Corregido (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_corregido" name="volumen_corregido" value="{{ old('volumen_corregido') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="temperatura_inicial" class="form-label">Temperatura Inicial (°C) *</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="temperatura_inicial" name="temperatura_inicial" value="{{ old('temperatura_inicial', 15) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="temperatura_final" class="form-label">Temperatura Final (°C) *</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="temperatura_final" name="temperatura_final" value="{{ old('temperatura_final', 15) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="densidad" class="form-label">Densidad (kg/L) *</label>
                            <input type="number" step="0.0001" min="0" class="form-control" 
                                   id="densidad" name="densidad" value="{{ old('densidad', 0.85) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_registro" class="form-label">Tipo de Registro *</label>
                            <select class="form-select" id="tipo_registro" name="tipo_registro" required>
                                <option value="">Seleccione...</option>
                                <option value="operacion" {{ old('tipo_registro', 'operacion') == 'operacion' ? 'selected' : '' }}>Operación</option>
                                <option value="acumulado" {{ old('tipo_registro') == 'acumulado' ? 'selected' : '' }}>Acumulado</option>
                                <option value="existencias" {{ old('tipo_registro') == 'existencias' ? 'selected' : '' }}>Existencias</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="operacion" class="form-label">Operación *</label>
                            <select class="form-select" id="operacion" name="operacion" required>
                                <option value="">Seleccione...</option>
                                <option value="recepcion" {{ old('operacion') == 'recepcion' ? 'selected' : '' }}>Recepción</option>
                                <option value="entrega" {{ old('operacion') == 'entrega' ? 'selected' : '' }}>Entrega</option>
                                <option value="inventario_inicial" {{ old('operacion') == 'inventario_inicial' ? 'selected' : '' }}>Inventario Inicial</option>
                                <option value="inventario_final" {{ old('operacion') == 'inventario_final' ? 'selected' : '' }}>Inventario Final</option>
                                <option value="venta" {{ old('operacion') == 'venta' ? 'selected' : '' }}>Venta</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="PENDIENTE" {{ old('estado', 'PENDIENTE') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                <option value="PROCESADO" {{ old('estado') == 'PROCESADO' ? 'selected' : '' }}>Procesado</option>
                                <option value="VALIDADO" {{ old('estado') == 'VALIDADO' ? 'selected' : '' }}>Validado</option>
                                <option value="ERROR" {{ old('estado') == 'ERROR' ? 'selected' : '' }}>Error</option>
                                <option value="CON_ALARMA" {{ old('estado') == 'CON_ALARMA' ? 'selected' : '' }}>Con Alarma</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="documento_fiscal_uuid" class="form-label">UUID Documento Fiscal</label>
                            <input type="text" class="form-control" id="documento_fiscal_uuid" name="documento_fiscal_uuid" 
                                   value="{{ old('documento_fiscal_uuid') }}" placeholder="Opcional">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="rfc_contraparte" class="form-label">RFC Contraparte</label>
                            <input type="text" class="form-control" id="rfc_contraparte" name="rfc_contraparte" 
                                   value="{{ old('rfc_contraparte') }}" placeholder="Opcional">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Registro
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
    
    // Calcular volumen de operación
    function calcularVolumenOperacion() {
        let inicial = parseFloat($('#volumen_inicial').val()) || 0;
        let final = parseFloat($('#volumen_final').val()) || 0;
        let operacion = final - inicial;
        $('#volumen_operacion').val(operacion.toFixed(3));
    }
    
    $('#volumen_inicial, #volumen_final').on('input', calcularVolumenOperacion);
    
    // Validar horas
    $('#hora_inicio, #hora_fin').change(function() {
        let inicio = $('#hora_inicio').val();
        let fin = $('#hora_fin').val();
        
        if (inicio && fin && inicio >= fin) {
            alert('La hora de fin debe ser posterior a la hora de inicio');
            $('#hora_fin').val('');
        }
    });
});
</script>
@endpush
