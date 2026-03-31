@extends('layouts.app')

@section('title', 'Nueva Existencia')
@section('header', 'Registrar Nueva Existencia (Emulador)')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cpu-fill"></i> Existencia Automática con Emulador
                </h5>
                <span class="badge bg-light text-dark">Emulador Activo</span>
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
                
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-lightning-charge"></i> 
                    <strong>¡Modo automático!</strong> Selecciona el tanque y los datos se cargarán automáticamente.
                </div>
                
                <form method="POST" action="{{ route('existencias.store') }}" id="existenciaForm">
                    @csrf
                    
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
                            <label for="producto_id" class="form-label">Producto</label>
                            <input type="text" class="form-control bg-light" id="producto_id" readonly placeholder="Se cargará del tanque">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert" id="alertaEmulador">
                                <div class="d-flex align-items-center">
                                    <div class="spinner-border spinner-border-sm me-2" role="status" id="spinnerEmulador" style="display: none;">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <div id="textoEmulador">
                                        <i class="bi bi-info-circle"></i> 
                                        Selecciona un tanque para cargar los datos automáticamente
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-speedometer2"></i> Datos de Operación (Emulador)
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="volumen_medido" class="form-label">Volumen Medido (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control fw-bold text-primary" 
                                   id="volumen_medido" name="volumen_medido" readonly required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="volumen_corregido" class="form-label">Volumen Corregido (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control fw-bold bg-success text-white" 
                                   id="volumen_corregido" name="volumen_corregido" readonly required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="volumen_disponible" class="form-label">Volumen Disponible (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control bg-info" 
                                   id="volumen_disponible" name="volumen_disponible" readonly required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="nivel_porcentaje" class="form-label">Nivel Tanque (%)</label>
                            <input type="number" step="0.1" class="form-control bg-warning fw-bold" 
                                   id="nivel_porcentaje" name="nivel_porcentaje" readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <label for="temperatura" class="form-label">Temperatura (°C)</label>
                            <input type="number" step="0.1" class="form-control" id="temperatura" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="densidad" class="form-label">Densidad</label>
                            <input type="number" step="0.0001" class="form-control" id="densidad" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="volumen_agua" class="form-label">Volumen Agua (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_agua" name="volumen_agua" value="0" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="volumen_sedimentos" class="form-label">Volumen Sedimentos (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_sedimentos" name="volumen_sedimentos" value="0" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="factor_correccion" class="form-label">Factor Corrección</label>
                            <input type="number" step="0.0001" class="form-control" id="factor_correccion" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="tipo_operacion" class="form-label">Tipo Operación</label>
                            <input type="text" class="form-control bg-success text-white fw-bold" id="tipo_operacion" readonly>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 mt-4 text-primary">
                        <i class="bi bi-speedometer2"></i> Características del Tanque (Solo Lectura)
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <label for="capacidad_total" class="form-label">Capacidad Total (L)</label>
                            <input type="number" step="0.001" class="form-control bg-secondary text-white" id="capacidad_total" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="capacidad_util" class="form-label">Capacidad Util (L)</label>
                            <input type="number" step="0.001" class="form-control bg-secondary text-white" id="capacidad_util" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="capacidad_operativa" class="form-label">Capacidad Operativa (L)</label>
                            <input type="number" step="0.001" class="form-control bg-secondary text-white" id="capacidad_operativa" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="capacidad_minima" class="form-label">Capacidad Mínima (L)</label>
                            <input type="number" step="0.001" class="form-control bg-secondary text-white" id="capacidad_minima" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="temperatura_referencia" class="form-label">Temp. Referencia (°C)</label>
                            <input type="number" step="0.1" class="form-control bg-secondary text-white" id="temperatura_referencia" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="presion_referencia" class="form-label">Presión Ref. (bar)</label>
                            <input type="number" step="0.001" class="form-control bg-secondary text-white" id="presion_referencia" readonly>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="numero_registro" class="form-label">Número de Registro *</label>
                            <input type="text" class="form-control" id="numero_registro" name="numero_registro" 
                                   value="{{ old('numero_registro') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" 
                                   value="{{ old('fecha', now()->toDateString()) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="hora" class="form-label">Hora *</label>
                            <input type="time" class="form-control" id="hora" name="hora" 
                                   value="{{ old('hora', now()->format('H:i:s')) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_registro" class="form-label">Tipo de Registro *</label>
                            <select class="form-select" id="tipo_registro" name="tipo_registro" required>
                                <option value="inicial" selected>Inicial</option>
                                <option value="operacion">Operación</option>
                                <option value="final">Final</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_movimiento" class="form-label">Tipo de Movimiento *</label>
                            <select class="form-select" id="tipo_movimiento" name="tipo_movimiento" required>
                                <option value="INICIAL" selected>Inicial</option>
                                <option value="RECEPCION">Recepción</option>
                                <option value="ENTREGA">Entrega</option>
                                <option value="VENTA">Venta</option>
                                <option value="TRASPASO">Traspaso</option>
                                <option value="AJUSTE">Ajuste</option>
                                <option value="INVENTARIO">Inventario</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="PENDIENTE" selected>Pendiente</option>
                                <option value="VALIDADO">Validado</option>
                                <option value="EN_REVISION">En Revisión</option>
                                <option value="CON_ALARMA">Con Alarma</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2" 
                                  placeholder="Registro generado automáticamente">{{ old('observaciones', 'Emulador automático') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('existencias.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" id="btnGuardar" disabled>
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
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    let apiBaseUrl = 'http://127.0.0.1:8000';
    let tanqueSeleccionado = false;
    
    function mostrarCarga(mensaje) {
        $('#spinnerEmulador').show();
        $('#textoEmulador').html('<i class="bi bi-hourglass-split"></i> ' + mensaje);
        $('#alertaEmulador').removeClass('alert-success alert-danger').addClass('alert-info');
    }
    
    function mostrarExito(mensaje) {
        $('#spinnerEmulador').hide();
        $('#textoEmulador').html('<i class="bi bi-check-circle"></i> ' + mensaje);
        $('#alertaEmulador').removeClass('alert-info alert-danger').addClass('alert-success');
    }
    
    function mostrarError(mensaje) {
        $('#spinnerEmulador').hide();
        $('#textoEmulador').html('<i class="bi bi-x-circle"></i> ' + mensaje);
        $('#alertaEmulador').removeClass('alert-info alert-success').addClass('alert-danger');
    }
    
    function cargarDatosEmulador(tanqueId) {
        if (!tanqueId) {
            $('#volumen_medido, #volumen_corregido, #volumen_disponible, #temperatura, #densidad').val('');
            $('#volumen_agua, #volumen_sedimentos, #factor_correccion, #nivel_porcentaje').val('');
            $('#tipo_operacion, #producto_id').val('');
            $('#capacidad_total, #capacidad_util, #capacidad_operativa, #capacidad_minima').val('');
            $('#temperatura_referencia, #presion_referencia').val('');
            $('#btnGuardar').prop('disabled', true);
            $('#alertaEmulador').removeClass('alert-success').addClass('alert-warning');
            $('#textoEmulador').html('<i class="bi bi-info-circle"></i> Selecciona un tanque para cargar los datos');
            return;
        }
        
        mostrarCarga('Cargando datos del emulador...');
        
        $.ajax({
            url: apiBaseUrl + '/api/emulador/lectura/' + tanqueId,
            type: 'GET',
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response.success && response.data) {
                    let data = response.data;
                    
                    let volMedido = parseFloat(data.volumen || 0);
                    let fc = parseFloat(data.factor_correccion || 1);
                    let volCorregido = volMedido * fc;
                    
                    $('#volumen_medido').val(volMedido.toFixed(3));
                    $('#volumen_corregido').val(volCorregido.toFixed(3));
                    $('#volumen_disponible').val(volMedido.toFixed(3));
                    $('#temperatura').val(data.temperatura ? data.temperatura.toFixed(1) : 20);
                    $('#densidad').val(data.densidad ? data.densidad.toFixed(4) : 0.8);
                    $('#factor_correccion').val(fc.toFixed(6));
                    $('#nivel_porcentaje').val(data.nivel_porcentaje ? data.nivel_porcentaje.toFixed(1) : 0);
                    $('#tipo_operacion').val(data.tipo_operacion || 'ENTREGA');
                    $('#volumen_agua').val('0');
                    $('#volumen_sedimentos').val('0');
                    
                    if (data.tanque) {
                        $('#producto_id').val(data.tanque.producto || '');
                    }
                    
                    if (data.datos_tanque) {
                        let dt = data.datos_tanque;
                        $('#capacidad_total').val(dt.capacidad_total || 0);
                        $('#capacidad_util').val(dt.capacidad_util || 0);
                        $('#capacidad_operativa').val(dt.capacidad_operativa || 0);
                        $('#capacidad_minima').val(dt.capacidad_minima || 0);
                        $('#temperatura_referencia').val(dt.temperatura_referencia || 20);
                        $('#presion_referencia').val(dt.presion_referencia || 1.01325);
                    }
                    
                    let mensaje = '¡Datos cargados! Vol.Medido=' + $('#volumen_medido').val() + 
                                  'L, Vol.Corregido=' + $('#volumen_corregido').val() + 
                                  'L, Nivel=' + $('#nivel_porcentaje').val() + '%';
                    mostrarExito(mensaje);
                    
                    $('#btnGuardar').prop('disabled', false);
                    tanqueSeleccionado = true;
                } else {
                    mostrarError('No se recibieron datos del emulador');
                    tanqueSeleccionado = false;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                mostrarError('Error al conectar con el emulador. ¿API corriendo en puerto 8000?');
                tanqueSeleccionado = false;
            }
        });
    }
    
    $('#tanque_id').on('change', function() {
        let tanqueId = $(this).val();
        cargarDatosEmulador(tanqueId);
    });
    
    $('#btnGuardar').on('click', function(e) {
        if (!tanqueSeleccionado) {
            e.preventDefault();
            alert('Por favor seleccione un tanque y verifique que los datos se hayan cargado');
        }
    });
});
</script>
@endpush