@extends('layouts.app')

@section('title', 'Nuevo Tanque de Almacenamiento')
@section('header', 'Registrar Nuevo Tanque - Simulación de Llenado')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-fuel-pump me-2"></i> Registro de Tanque con Simulación de Recepción
                </h5>
                <span class="badge bg-warning text-dark">
                    <i class="bi bi-speedometer2 me-1"></i> Emulador de Llenado Activo
                </span>
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
                
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Simulación de Recepción:</strong> Este módulo simula la entrada de combustible al tanque. 
                    Selecciona el producto y observa cómo el volumen aumenta automáticamente.
                </div>
                
                <form action="{{ route('tanques.store') }}" method="POST" id="tanqueForm">
                    @csrf
                    <input type="hidden" id="instalacion_id" name="instalacion_id" value="{{ old('instalacion_id') }}">
                    <input type="hidden" id="tipo_medicion" name="tipo_medicion" value="dinamica">
                    
                    <!-- Selección de Producto para Recepción -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-droplet-half me-2"></i>
                                        Configuración de Recepción de Combustible
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="productoRecepcion" class="form-label text-primary fw-bold">
                                                <i class="bi bi-cup-straw me-1"></i> Producto a Recepcionar *
                                            </label>
                                            <select id="productoRecepcion" class="form-select form-select-lg border-primary" required>
                                                <option value="">Seleccione el producto...</option>
                                                @foreach($productos as $prod)
                                                    <option value="{{ $prod['id'] ?? $prod->id ?? '' }}">
                                                        {{ $prod['nombre'] ?? $prod->nombre ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="instalacionRecepcion" class="form-label text-primary fw-bold">
                                                <i class="bi bi-building me-1"></i> Instalación *
                                            </label>
                                            <select id="instalacionRecepcion" class="form-select form-select-lg border-primary" required>
                                                <option value="">Seleccione...</option>
                                                @foreach($instalaciones as $inst)
                                                    <option value="{{ $inst['id'] ?? $inst->id ?? '' }}">
                                                        {{ $inst['nombre'] ?? $inst->nombre ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-success btn-lg w-100" onclick="iniciarRecepcion()">
                                                <i class="bi bi-play-fill me-2"></i>
                                                INICIAR RECEPCIÓN
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Panel de Monitoreo en Tiempo Real -->
                    <div class="row mb-4" id="panelRecepcion" style="display: none;">
                        <div class="col-md-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="bi bi-graph-up-arrow me-2"></i>
                                        Monitoreo de Recepción en Tiempo Real
                                    </h6>
                                    <span class="badge bg-light text-success" id="estadoRecepcion">
                                        <i class="bi bi-hourglass-split me-1"></i> RECEPCIÓN EN CURSO...
                                    </span>
                                </div>
                                <div class="card-body">
                                    <!-- Indicadores principales -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card bg-primary text-white text-center py-3">
                                                <h7 class="mb-2">Volumen Inicial</h7>
                                                <h3 class="mb-0" id="volInicialDisplay">0 L</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-warning text-dark text-center py-3">
                                                <h7 class="mb-2">Volumen Entrante</h7>
                                                <h3 class="mb-0" id="volEntranteDisplay">0 L</h3>
                                                <small id="velocidadDisplay">0 L/min</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-success text-white text-center py-3">
                                                <h7 class="mb-2">Volumen Final</h7>
                                                <h3 class="mb-0" id="volFinalDisplay">0 L</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-info text-white text-center py-3">
                                                <h7 class="mb-2">Nivel del Tanque</h7>
                                                <h3 class="mb-0" id="nivelDisplay">0%</h3>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Barra de progreso -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Progreso de Recepción:</label>
                                            <div class="progress" style="height: 30px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                                     id="barraProgreso" role="progressbar" style="width: 0%;">
                                                    <span id="textoProgreso" class="fw-bold">0%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Botones de control -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-danger btn-lg w-100" onclick="detenerRecepcion()">
                                                <i class="bi bi-stop-fill me-2"></i>
                                                DETENER RECEPCIÓN
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-primary btn-lg w-100" onclick="obtenerDatosRecepcion()">
                                                <i class="bi bi-arrow-repeat me-2"></i>
                                                ACTUALIZAR DATOS
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Datos del tanque (para guardar) -->
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-database me-2"></i>Datos para Registrar el Tanque
                    </h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="identificador" class="form-label">Identificador *</label>
                            <input type="text" class="form-control" id="identificador" name="identificador" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="numero_serie" class="form-label">Número de Serie</label>
                            <input type="text" class="form-control bg-light" id="numero_serie" name="numero_serie" readonly>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="material" class="form-label">Material *</label>
                            <input type="text" class="form-control" id="material" name="material" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="capacidad_total" class="form-label">Capacidad Total (L) *</label>
                            <input type="number" step="any" class="form-control" id="capacidad_total" name="capacidad_total" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="capacidad_util" class="form-label">Capacidad Util (L) *</label>
                            <input type="number" step="any" class="form-control" id="capacidad_util" name="capacidad_util" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="capacidad_operativa" class="form-label">Capacidad Operativa (L) *</label>
                            <input type="number" step="any" class="form-control" id="capacidad_operativa" name="capacidad_operativa" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="capacidad_minima" class="form-label">Capacidad Mínima (L) *</label>
                            <input type="number" step="any" class="form-control" id="capacidad_minima" name="capacidad_minima" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="temperatura_referencia" class="form-label">Temperatura Referencia (°C) *</label>
                            <input type="number" step="any" class="form-control" id="temperatura_referencia" name="temperatura_referencia" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="presion_referencia" class="form-label">Presión Referencia (bar) *</label>
                            <input type="number" step="any" class="form-control" id="presion_referencia" name="presion_referencia" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado *</label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="OPERATIVO" selected>Operativo</option>
                                <option value="MANTENIMIENTO">Mantenimiento</option>
                                <option value="FUERA_SERVICIO">Fuera de Servicio</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save"></i> Guardar Tanque
                        </button>
                        <a href="{{ route('tanques.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let apiBaseUrl = 'http://127.0.0.1:8000';
let tanqueIdActual = null;
let intervaloActualizacion = null;

function iniciarRecepcion() {
    const productoId = document.getElementById('productoRecepcion').value;
    const instalacionId = document.getElementById('instalacionRecepcion').value;
    
    if (!productoId || !instalacionId) {
        alert('Por favor seleccione el producto y la instalación');
        return;
    }
    
    // Obtener datos iniciales del tanque desde el emulador
    fetch(apiBaseUrl + '/api/emulador/tanque/datos-automaticos')
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                
                // Llenar datos del tanque
                document.getElementById('identificador').value = data.identificador;
                document.getElementById('material').value = data.material;
                document.getElementById('fabricante').value = data.fabricante;
                document.getElementById('capacidad_total').value = data.capacidad_total;
                document.getElementById('capacidad_util').value = data.capacidad_util;
                document.getElementById('capacidad_operativa').value = data.capacidad_operativa;
                document.getElementById('capacidad_minima').value = data.capacidad_minima;
                document.getElementById('temperatura_referencia').value = data.temperatura_referencia;
                document.getElementById('presion_referencia').value = data.presion_referencia;
                
                // Generar número de serie
                fetch(apiBaseUrl + '/api/emulador/tanque/serial/' + instalacionId)
                    .then(r => r.json())
                    .then(serieRes => {
                        if (serieRes.success) {
                            document.getElementById('numero_serie').value = serieRes.data.numero_serie;
                        }
                    });
                
                // Iniciar simulación de llenado
                fetch(apiBaseUrl + '/api/emulador/tanque/simular-llenado', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        tanque_id: data.identificador,
                        volumen_inicial: data.volumen_actual,
                        tipo_operacion: 'RECEPCION'
                    })
                })
                .then(r => r.json())
                .then(llenadoRes => {
                    if (llenadoRes.success) {
                        const ld = llenadoRes.data;
                        
                        // Mostrar panel de recepción
                        document.getElementById('panelRecepcion').style.display = 'block';
                        
                        // Actualizar indicadores
                        document.getElementById('volInicialDisplay').textContent = ld.volumen_inicial + ' L';
                        document.getElementById('volEntranteDisplay').textContent = ld.volumen_entrado + ' L';
                        document.getElementById('velocidadDisplay').textContent = ld.velocidad_flujo + ' L/min';
                        document.getElementById('volFinalDisplay').textContent = ld.volumen_final + ' L';
                        
                        // Calcular nivel porcentaje
                        let capacidad = parseFloat(data.capacidad_total);
                        let nivel = (parseFloat(ld.volumen_final) / capacidad) * 100;
                        document.getElementById('nivelDisplay').textContent = nivel.toFixed(1) + '%';
                        
                        // Actualizar barra de progreso
                        document.getElementById('barraProgreso').style.width = nivel + '%';
                        document.getElementById('textoProgreso').textContent = nivel.toFixed(1) + '%';
                        
                        // Cambiar estado
                        document.getElementById('estadoRecepcion').innerHTML = '<i class="bi bi-check-circle me-1"></i> RECEPCIÓN COMPLETADA';
                        
                        alert('¡Recepción de combustible completada!\n\n' +
                              'Volumen Inicial: ' + ld.volumen_inicial + ' L\n' +
                              'Volumen Entrante: ' + ld.volumen_entrado + ' L\n' +
                              'Volumen Final: ' + ld.volumen_final + ' L\n' +
                              'Nivel del Tanque: ' + nivel.toFixed(1) + '%');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al iniciar la recepción');
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el emulador');
        });
}

function obtenerDatosRecepcion() {
    if (!tanqueIdActual) {
        alert('No hay una recepción en curso');
        return;
    }
    
    fetch(apiBaseUrl + '/api/emulador/tanque/estado-llenado/' + tanqueIdActual)
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                document.getElementById('volEntranteDisplay').textContent = data.volumen_entrado + ' L';
                document.getElementById('volFinalDisplay').textContent = data.volumen_actual + ' L';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function detenerRecepcion() {
    if (intervaloActualizacion) {
        clearInterval(intervaloActualizacion);
    }
    
    fetch(apiBaseUrl + '/api/emulador/tanque/detener-llenado/' + tanqueIdActual, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            alert('Recepción detenida.\nVolumen final: ' + response.data.volumen_final + ' L');
            document.getElementById('panelRecepcion').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2 si está disponible
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }
    
    // Actualizar campo oculto instalacion_id cuando se selecciona una instalación
    document.getElementById('instalacionRecepcion').addEventListener('change', function() {
        document.getElementById('instalacion_id').value = this.value;
    });
    
    // Datos por defecto
    document.getElementById('temperatura_referencia').value = 20;
    document.getElementById('presion_referencia').value = 1.01325;
    document.getElementById('capacidad_total').value = 10000;
    document.getElementById('capacidad_util').value = 9000;
    document.getElementById('capacidad_operativa').value = 8500;
    document.getElementById('capacidad_minima').value = 500;
});
</script>
@endpush