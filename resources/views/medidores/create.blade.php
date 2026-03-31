@extends('layouts.app')

@section('title', 'Nuevo Medidor')
@section('header', 'Registrar Nuevo Medidor (Emulador)')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cpu-fill"></i> Medidor Automático con Emulador
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
                    <strong>¡Modo automático!</strong> Los datos técnicos del medidor se cargan automáticamente desde el emulador.
                </div>
                
                <form method="POST" action="{{ route('medidores.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($instalaciones as $instalacion)
                                    <option value="{{ $instalacion['id'] ?? $instalacion->id ?? '' }}">
                                        {{ $instalacion['nombre'] ?? $instalacion->nombre ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tanque_id" class="form-label">Tanque (opcional)</label>
                            <select class="form-select select2" id="tanque_id" name="tanque_id">
                                <option value="">Seleccione...</option>
                            </select>
                            <small class="text-muted">Seleccione para cargar datos del emulador</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="clave" class="form-label">Clave *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="clave" name="clave" 
                                       value="{{ old('clave') }}" required>
                                <button type="button" class="btn btn-outline-success" onclick="generarClaveMedidor()" title="Generar clave">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
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
                                        Selecciona un tanque para cargar automáticamente los datos técnicos del medidor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-speedometer2"></i> Datos Técnicos del Medidor (Emulador)
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <select class="form-select" id="fabricante" name="fabricante">
                                <option value="">Seleccione...</option>
                                <option value="FIDELITÀ">FIDELITÀ</option>
                                <option value="SOLARTRON">SOLARTRON</option>
                                <option value="LIQUID CONTROLS">LIQUID CONTROLS</option>
                                <option value="BURKERT">BURKERT</option>
                                <option value="KROHNE">KROHNE</option>
                                <option value="EMERSON">EMERSON</option>
                                <option value="ENDRESS+HAUSER">ENDRESS+HAUSER</option>
                                <option value="SIEMENS">SIEMENS</option>
                                <option value="ABB">ABB</option>
                                <option value="SCHNEIDER">SCHNEIDER</option>
                                <option value="ROCKWELL">ROCKWELL</option>
                                <option value="HONEYWELL">HONEYWELL</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="elemento_tipo" class="form-label">Tipo de Elemento *</label>
                            <select class="form-select" id="elemento_tipo" name="elemento_tipo" required>
                                <option value="primario" selected>Primario</option>
                                <option value="secundario">Secundario</option>
                                <option value="terciario">Terciario</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="tipo_medicion" class="form-label">Tipo de Medición *</label>
                            <select class="form-select" id="tipo_medicion" name="tipo_medicion" required>
                                <option value="estatica">Estática</option>
                                <option value="dinamica" selected>Dinámica</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="tecnologia_id" class="form-label">Tecnología</label>
                            <select class="form-select" id="tecnologia_id" name="tecnologia_id">
                                <option value="1">Electrónica</option>
                                <option value="2">Mecánica</option>
                                <option value="3">Ultrasonido</option>
                                <option value="4"> electromagnético</option>
                                <option value="5">Coriolis</option>
                                <option value="6">Térmico</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="protocolo_comunicacion" class="form-label">Protocolo Comunicación</label>
                            <select class="form-select" id="protocolo_comunicacion" name="protocolo_comunicacion">
                                <option value="modbus" selected>Modbus TCP/IP</option>
                                <option value="opc">OPC UA</option>
                                <option value="ethernet">Ethernet/IP</option>
                                <option value="serial">RS-485</option>
                                <option value="wireless">Wireless</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="precision" class="form-label">Precisión (%) *</label>
                            <input type="number" step="0.01" min="0" class="form-control bg-info" 
                                   id="precision" name="precision" value="0.5" required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="capacidad_maxima" class="form-label">Capacidad Máxima (L/min) *</label>
                            <input type="number" step="0.1" min="0" class="form-control bg-success text-white fw-bold" 
                                   id="capacidad_maxima" name="capacidad_maxima" value="200" required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="presion_maxima" class="form-label">Presión Máxima (psi)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="presion_maxima" name="presion_maxima" value="150" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="temperatura_maxima" class="form-label">Temperatura Máxima (°C)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="temperatura_maxima" name="temperatura_maxima" value="60" required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="numero_serie" class="form-label">Número de Serie *</label>
                            <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                   value="{{ old('numero_serie') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control bg-light" id="modelo" name="modelo" 
                                   value="{{ old('modelo') }}" readonly placeholder="Se cargará del emulador">
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="OPERATIVO" selected>Operativo</option>
                                <option value="CALIBRACION">En Calibración</option>
                                <option value="MANTENIMIENTO">Mantenimiento</option>
                                <option value="FUERA_SERVICIO">Fuera de Servicio</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 mt-4 text-primary">
                        <i class="bi bi-calendar-check"></i> Fechas y Certificados
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_instalacion" class="form-label">Fecha de Instalación</label>
                            <input type="date" class="form-control" id="fecha_instalacion" 
                                   name="fecha_instalacion" value="{{ now()->toDateString() }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_ultima_calibracion" class="form-label">Última Calibración</label>
                            <input type="date" class="form-control" id="fecha_ultima_calibracion" 
                                   name="fecha_ultima_calibracion" value="{{ now()->toDateString() }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_proxima_calibracion" class="form-label">Próxima Calibración</label>
                            <input type="date" class="form-control" id="fecha_proxima_calibracion" 
                                   name="fecha_proxima_calibracion" value="{{ now()->addYear()->toDateString() }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="certificado_calibracion" class="form-label">Certificado Calibración</label>
                            <input type="text" class="form-control" id="certificado_calibracion" name="certificado_calibracion" 
                                   value="CERT-{{ date('Ymd') }}-{{ rand(1000,9999) }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2" 
                                  placeholder="Medidor generado automáticamente">{{ old('observaciones', 'Emulador automático') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('medidores.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" id="btnGuardar">
                            <i class="bi bi-save"></i> Guardar Medidor
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
function generarClaveMedidor() {
    const prefijos = ['MED', 'MTR', 'FLW', 'VOL'];
    const prefijo = prefijos[Math.floor(Math.random() * prefijos.length)];
    const numero = String(Math.floor(Math.random() * 99999) + 1).padStart(5, '0');
    document.getElementById('clave').value = `${prefijo}-${numero}`;
}

function generarNumeroSerie() {
    const letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    let serie = '';
    for (let i = 0; i < 3; i++) {
        serie += letras.charAt(Math.floor(Math.random() * letras.length));
    }
    serie += '-' + String(Math.floor(Math.random() * 999999)).padStart(6, '0');
    document.getElementById('numero_serie').value = serie;
}

$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    let apiBaseUrl = 'http://127.0.0.1:8000';
    
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
    
    // Cargar танques cuando cambia instalación
    $('#instalacion_id').on('change', function() {
        let instalacionId = $(this).val();
        
        if (!instalacionId) {
            $('#tanque_id').empty().append('<option value="">Seleccione una instalación primero...</option>');
            return;
        }
        
        $('#tanque_id').prop('disabled', true);
        $('#tanque_id').empty().append('<option value="">Cargando tanques...</option>');
        
        $.ajax({
            url: apiBaseUrl + '/api/emulador/tanques/instalacion/' + instalacionId,
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
                        let nombre = tanque.identificador || ('Tanque ' + id);
                        options += '<option value="' + id + '">' + nombre + '</option>';
                    });
                }
                
                $('#tanque_id').html(options).prop('disabled', false);
            },
            error: function() {
                $('#tanque_id').empty().append('<option value="">Error al cargar</option>');
            }
        });
    });
    
    // Cargar datos del medidor cuando se selecciona tanque
    $('#tanque_id').on('change', function() {
        let tanqueId = $(this).val();
        
        if (!tanqueId) {
            $('#textoEmulador').html('<i class="bi bi-info-circle"></i> Selecciona un tanque para cargar los datos');
            return;
        }
        
        mostrarCarga('Cargando datos técnicos del medidor...');
        
        // Generar datos automáticos del medidor
        const fabricantes = ['FIDELITÀ', 'SOLARTRON', 'LIQUID CONTROLS', 'BURKERT', 'EMERSON'];
        const modelos = ['MX4000', 'LCR-i', 'Sктация-200', 'E-XPV', 'ROTAX'];
        const tecnologias = ['1', '2', '3', '4'];
        const protocolos = ['modbus', 'opc', 'ethernet', 'serial'];
        
        const fabricanteAleatorio = fabricantes[Math.floor(Math.random() * fabricantes.length)];
        const modeloAleatorio = modelos[Math.floor(Math.random() * modelos.length)];
        const tecnologiaAleatoria = tecnologias[Math.floor(Math.random() * tecnologias.length)];
        const protocoloAleatorio = protocolos[Math.floor(Math.random() * protocolos.length)];
        
        const capacidadAleatoria = Math.floor(Math.random() * 300) + 50; // 50-350 L/min
        const precisionAleatoria = (Math.random() * 0.5 + 0.1).toFixed(2); // 0.1-0.6%
        const presionAleatoria = Math.floor(Math.random() * 100) + 100; // 100-200 psi
        const temperaturaAleatoria = Math.floor(Math.random() * 40) + 40; // 40-80 °C
        
        // Seleccionar fabricante
        $('#fabricante').val(fabricanteAleatorio);
        
        $('#modelo').val(modeloAleatorio);
        $('#tecnologia_id').val(tecnologiaAleatoria);
        $('#protocolo_comunicacion').val(protocoloAleatorio);
        $('#precision').val(precisionAleatoria);
        $('#capacidad_maxima').val(capacidadAleatoria);
        $('#presion_maxima').val(presionAleatoria);
        $('#temperatura_maxima').val(temperaturaAleatoria);
        
        // Generar número de serie si está vacío
        if (!$('#numero_serie').val()) {
            generarNumeroSerie();
        }
        
        // Generar clave si está vacía
        if (!$('#clave').val()) {
            generarClaveMedidor();
        }
        
        let mensaje = '¡Datos técnicos cargados! Fabricante: ' + fabricanteAleatorio + 
                      ', Modelo: ' + modeloAleatorio + 
                      ', Capacidad: ' + capacidadAleatoria + ' L/min';
        mostrarExito(mensaje);
    });
    
    // Generar número de serie automáticamente al cargar
    generarNumeroSerie();
    generarClaveMedidor();
});
</script>
@endpush