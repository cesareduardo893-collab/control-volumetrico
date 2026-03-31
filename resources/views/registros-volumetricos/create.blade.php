@extends('layouts.app')

@section('title', 'Nuevo Registro Volumétrico')
@section('header', 'Registrar Nuevo Registro Volumétrico (Emulador)')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cpu-fill"></i> Registro Volumétrico Automático
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
                
                <form method="POST" action="{{ route('registros-volumetricos.emulador') }}" id="registroForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @forelse($instalaciones as $instalacion)
                                    <option value="{{ $instalacion['id'] ?? $instalacion->id }}">
                                        {{ $instalacion['nombre'] ?? $instalacion->nombre ?? 'Instalación' }}
                                    </option>
                                @empty
                                    <option value="">No hay instalaciones</option>
                                @endforelse
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tanque_id" class="form-label">Tanque *</label>
                            <select class="form-select select2" id="tanque_id" name="tanque_id" required disabled>
                                <option value="">Seleccione una instalación primero...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
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
                            <label for="volumen_inicial" class="form-label">Volumen Inicial (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control fw-bold text-primary" 
                                   id="volumen_inicial" name="volumen_inicial" readonly required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="volumen_final" class="form-label">Volumen Final (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control fw-bold text-primary" 
                                   id="volumen_final" name="volumen_final" readonly required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="volumen_operacion" class="form-label">Volumen Operación (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control fw-bold text-info" 
                                   id="volumen_operacion" name="volumen_operacion" readonly required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="volumen_corregido" class="form-label">Volumen Corregido (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control fw-bold bg-success text-white" 
                                   id="volumen_corregido" name="volumen_corregido" readonly required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <label for="temperatura" class="form-label">Temperatura (°C)</label>
                            <input type="number" step="0.1" class="form-control" id="temperatura" name="temperatura" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="presion" class="form-label">Presión (bar)</label>
                            <input type="number" step="0.001" class="form-control" id="presion" name="presion" readonly>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="densidad" class="form-label">Densidad</label>
                            <input type="number" step="0.0001" class="form-control" id="densidad" name="densidad" readonly>
                            <input type="hidden" id="densidad_hidden" name="densidad">
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="factor_correccion" class="form-label">Factor Corrección</label>
                            <input type="number" step="0.0001" class="form-control" id="factor_correccion" name="factor_correccion" readonly>
                            <input type="hidden" id="factor_correccion_hidden" name="factor_correccion">
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label for="nivel_porcentaje" class="form-label">Nivel Tanque (%)</label>
                            <input type="number" step="0.1" class="form-control bg-warning fw-bold" id="nivel_porcentaje" readonly>
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
                        <div class="col-md-3 mb-3">
                            <label for="hora_inicio" class="form-label">Hora Inicio *</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                                   value="{{ date('H:i:s') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="hora_fin" class="form-label">Hora Fin *</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                                   value="{{ date('H:i:s') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" 
                                   value="{{ now()->toDateString() }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="medidor_id" class="form-label">Medidor (opcional)</label>
                            <select class="form-select" id="medidor_id" name="medidor_id">
                                <option value="">Sin medidor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_registro" class="form-label">Tipo de Registro *</label>
                            <select class="form-select" id="tipo_registro" name="tipo_registro" required>
                                <option value="operacion" selected>Operación</option>
                                <option value="acumulado">Acumulado</option>
                                <option value="existencias">Existencias</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="operacion" class="form-label">Operación *</label>
                            <select class="form-select" id="operacion" name="operacion" required>
                                <option value="entrega" selected>Entrega</option>
                                <option value="recepcion">Recepción</option>
                                <option value="inventario_inicial">Inventario Inicial</option>
                                <option value="inventario_final">Inventario Final</option>
                                <option value="venta">Venta</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="PENDIENTE" selected>Pendiente</option>
                                <option value="PROCESADO">Procesado</option>
                                <option value="VALIDADO">Validado</option>
                                <option value="CON_ALARMA">Con Alarma</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2" 
                                  placeholder="Registro generado automáticamente">{{ old('observations', 'Emulador automático') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" id="btnGuardar" disabled>
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
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
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
    
    function limpiarCampos() {
        $('#volumen_inicial, #volumen_final, #volumen_operacion, #volumen_corregido').val('');
        $('#temperatura, #presion, #densidad, #factor_correccion, #nivel_porcentaje').val('');
        $('#tipo_operacion').val('');
        $('#capacidad_total, #capacidad_util, #capacidad_operativa, #capacidad_minima').val('');
        $('#temperatura_referencia, #presion_referencia').val('');
        $('#producto_id').val('');
        $('#btnGuardar').prop('disabled', true);
    }
    
    function cargarDatosEmulador(tanqueId) {
        if (!tanqueId) {
            limpiarCampos();
            $('#alertaEmulador').removeClass('alert-success').addClass('alert-warning');
            $('#textoEmulador').html('<i class="bi bi-info-circle"></i> Selecciona un tanque para cargar los datos');
            return;
        }
        
        mostrarCarga('Cargando datos del emulador...');
        
        $.ajax({
            url: '{{ route("api.emulador.lectura", ["tanqueId" => "__TANQUE_ID__"]) }}'.replace('__TANQUE_ID__', tanqueId),
            type: 'GET',
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response.success && response.data) {
                    let data = response.data;
                    
                    $('#volumen_inicial').val(parseFloat(data.volumen_anterior || 0).toFixed(3));
                    $('#volumen_final').val(parseFloat(data.volumen || 0).toFixed(3));
                    $('#volumen_operacion').val(Math.abs(parseFloat(data.volumen_cambio || 0)).toFixed(3));
                    $('#factor_correccion').val(parseFloat(data.factor_correccion || 1).toFixed(6));
                    $('#factor_correccion_hidden').val(parseFloat(data.factor_correccion || 1).toFixed(6));
                    
                    let volOp = parseFloat($('#volumen_operacion').val()) || 0;
                    let fc = parseFloat($('#factor_correccion').val()) || 1;
                    $('#volumen_corregido').val((volOp * fc).toFixed(3));
                    
                    $('#temperatura').val(data.temperatura ? data.temperatura.toFixed(1) : 20);
                    $('#presion').val(data.presion ? data.presion.toFixed(3) : 1);
                    $('#densidad').val(data.densidad ? data.densidad.toFixed(4) : 0.8);
                    $('#densidad_hidden').val(data.densidad ? data.densidad.toFixed(4) : 0.8);
                    $('#nivel_porcentaje').val(data.nivel_porcentaje ? data.nivel_porcentaje.toFixed(1) : 0);
                    $('#tipo_operacion').val(data.tipo_operacion || 'ENTREGA');
                    
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
                    
                    let mensaje = '¡Datos cargados! Vol.Inicial=' + $('#volumen_inicial').val() + 
                                  'L, Vol.Final=' + $('#volumen_final').val() + 
                                  'L, Operación=' + $('#tipo_operacion').val();
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
    
    function cargarTanques(instalacionId) {
        if (!instalacionId) {
            $('#tanque_id').empty().append('<option value="">Seleccione una instalación primero...</option>');
            $('#tanque_id').prop('disabled', true);
            limpiarCampos();
            return;
        }
        
        $('#tanque_id').prop('disabled', true);
        $('#tanque_id').empty().append('<option value="">Cargando tanques...</option>');
        
        $.ajax({
            url: '{{ route("api.emulador.tanques", ["instalacionId" => "__INSTALACION_ID__"]) }}'.replace('__INSTALACION_ID__', instalacionId),
            type: 'GET',
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                let data = response.data || [];
                let options = '<option value="">Seleccione...</option>';
                
                if (data.length === 0) {
                    options = '<option value="">No hay tanques en esta instalación</option>';
                } else {
                    data.forEach(function(tanque) {
                        let id = tanque.id;
                        let nombre = tanque.identificador || tanque.numero_serie || ('Tanque ' + id);
                        options += '<option value="' + id + '">' + nombre + '</option>';
                    });
                }
                
                $('#tanque_id').html(options).prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('Error cargando tanques:', error);
                $('#tanque_id').empty().append('<option value="">Error al cargar tanques</option>');
            }
        });
    }
    
    $('#instalacion_id').on('change', function() {
        let instalacionId = $(this).val();
        cargarTanques(instalacionId);
    });
    
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