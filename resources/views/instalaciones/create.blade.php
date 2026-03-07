@extends('layouts.app')

@section('title', 'Nueva Instalación')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nueva Instalación</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('instalaciones.store') }}" id="instalacionForm">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información Básica</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contribuyente_id" class="form-label">Contribuyente <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('contribuyente_id') is-invalid @enderror" 
                                        id="contribuyente_id" 
                                        name="contribuyente_id" 
                                        required>
                                    <option value="">Seleccione un contribuyente...</option>
                                    @foreach($contribuyentes['data'] ?? [] as $contribuyente)
                                        <option value="{{ $contribuyente['id'] }}" {{ old('contribuyente_id') == $contribuyente['id'] ? 'selected' : '' }}>
                                            {{ $contribuyente['rfc'] }} - {{ $contribuyente['razon_social'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contribuyente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="clave_instalacion" class="form-label">Clave Instalación <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('clave_instalacion') is-invalid @enderror" 
                                       id="clave_instalacion" 
                                       name="clave_instalacion" 
                                       value="{{ old('clave_instalacion') }}" 
                                       maxlength="50"
                                       required>
                                @error('clave_instalacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre') }}" 
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_instalacion" class="form-label">Tipo de Instalación <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_instalacion') is-invalid @enderror" 
                                        id="tipo_instalacion" 
                                        name="tipo_instalacion" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="estacion_servicio" {{ old('tipo_instalacion') == 'estacion_servicio' ? 'selected' : '' }}>Estación de Servicio</option>
                                    <option value="almacenamiento" {{ old('tipo_instalacion') == 'almacenamiento' ? 'selected' : '' }}>Almacenamiento</option>
                                    <option value="transporte" {{ old('tipo_instalacion') == 'transporte' ? 'selected' : '' }}>Transporte</option>
                                </select>
                                @error('tipo_instalacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="domicilio" class="form-label">Domicilio <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('domicilio') is-invalid @enderror" 
                                       id="domicilio" 
                                       name="domicilio" 
                                       value="{{ old('domicilio') }}" 
                                       required>
                                @error('domicilio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="codigo_postal" class="form-label">Código Postal <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('codigo_postal') is-invalid @enderror" 
                                       id="codigo_postal" 
                                       name="codigo_postal" 
                                       value="{{ old('codigo_postal') }}" 
                                       maxlength="5"
                                       required>
                                @error('codigo_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" 
                                       class="form-control @error('latitud') is-invalid @enderror" 
                                       id="latitud" 
                                       name="latitud" 
                                       value="{{ old('latitud') }}" 
                                       step="any">
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" 
                                       class="form-control @error('longitud') is-invalid @enderror" 
                                       id="longitud" 
                                       name="longitud" 
                                       value="{{ old('longitud') }}" 
                                       step="any">
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horario_atencion" class="form-label">Horario de Atención</label>
                                <input type="text" 
                                       class="form-control @error('horario_atencion') is-invalid @enderror" 
                                       id="horario_atencion" 
                                       name="horario_atencion" 
                                       value="{{ old('horario_atencion') }}">
                                @error('horario_atencion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Configuración de Red -->
                    <h5 class="mb-3 mt-4">Configuración de Red</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ip_servidor" class="form-label">IP Servidor</label>
                                <input type="text" 
                                       class="form-control @error('ip_servidor') is-invalid @enderror" 
                                       id="ip_servidor" 
                                       name="ip_servidor" 
                                       value="{{ old('ip_servidor') }}">
                                @error('ip_servidor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="puerto_servidor" class="form-label">Puerto</label>
                                <input type="number" 
                                       class="form-control @error('puerto_servidor') is-invalid @enderror" 
                                       id="puerto_servidor" 
                                       name="puerto_servidor" 
                                       value="{{ old('puerto_servidor') }}" 
                                       min="1" 
                                       max="65535">
                                @error('puerto_servidor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="protocolo_comunicacion" class="form-label">Protocolo</label>
                                <select class="form-select @error('protocolo_comunicacion') is-invalid @enderror" 
                                        id="protocolo_comunicacion" 
                                        name="protocolo_comunicacion">
                                    <option value="">Seleccione...</option>
                                    <option value="TCP" {{ old('protocolo_comunicacion') == 'TCP' ? 'selected' : '' }}>TCP</option>
                                    <option value="UDP" {{ old('protocolo_comunicacion') == 'UDP' ? 'selected' : '' }}>UDP</option>
                                    <option value="HTTP" {{ old('protocolo_comunicacion') == 'HTTP' ? 'selected' : '' }}>HTTP</option>
                                    <option value="HTTPS" {{ old('protocolo_comunicacion') == 'HTTPS' ? 'selected' : '' }}>HTTPS</option>
                                </select>
                                @error('protocolo_comunicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="intervalo_comunicacion" class="form-label">Intervalo (min)</label>
                                <input type="number" 
                                       class="form-control @error('intervalo_comunicacion') is-invalid @enderror" 
                                       id="intervalo_comunicacion" 
                                       name="intervalo_comunicacion" 
                                       value="{{ old('intervalo_comunicacion') }}" 
                                       min="1">
                                @error('intervalo_comunicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="timeout_comunicacion" class="form-label">Timeout (seg)</label>
                                <input type="number" 
                                       class="form-control @error('timeout_comunicacion') is-invalid @enderror" 
                                       id="timeout_comunicacion" 
                                       name="timeout_comunicacion" 
                                       value="{{ old('timeout_comunicacion') }}" 
                                       min="1">
                                @error('timeout_comunicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Umbrales de Alarma -->
                    <h5 class="mb-3 mt-4">Umbrales de Alarma</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_temperatura_min" class="form-label">Temp. Mín (°C)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_temperatura_min') is-invalid @enderror" 
                                       id="umbral_temperatura_min" 
                                       name="umbral_temperatura_min" 
                                       value="{{ old('umbral_temperatura_min') }}" 
                                       step="any">
                                @error('umbral_temperatura_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_temperatura_max" class="form-label">Temp. Máx (°C)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_temperatura_max') is-invalid @enderror" 
                                       id="umbral_temperatura_max" 
                                       name="umbral_temperatura_max" 
                                       value="{{ old('umbral_temperatura_max') }}" 
                                       step="any">
                                @error('umbral_temperatura_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_presion_min" class="form-label">Presión Mín (psi)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_presion_min') is-invalid @enderror" 
                                       id="umbral_presion_min" 
                                       name="umbral_presion_min" 
                                       value="{{ old('umbral_presion_min') }}" 
                                       step="any">
                                @error('umbral_presion_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_presion_max" class="form-label">Presión Máx (psi)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_presion_max') is-invalid @enderror" 
                                       id="umbral_presion_max" 
                                       name="umbral_presion_max" 
                                       value="{{ old('umbral_presion_max') }}" 
                                       step="any">
                                @error('umbral_presion_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_nivel_min" class="form-label">Nivel Mín (%)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_nivel_min') is-invalid @enderror" 
                                       id="umbral_nivel_min" 
                                       name="umbral_nivel_min" 
                                       value="{{ old('umbral_nivel_min') }}" 
                                       min="0" 
                                       max="100">
                                @error('umbral_nivel_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_nivel_max" class="form-label">Nivel Máx (%)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_nivel_max') is-invalid @enderror" 
                                       id="umbral_nivel_max" 
                                       name="umbral_nivel_max" 
                                       value="{{ old('umbral_nivel_max') }}" 
                                       min="0" 
                                       max="100">
                                @error('umbral_nivel_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_flujo_min" class="form-label">Flujo Mín (L/min)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_flujo_min') is-invalid @enderror" 
                                       id="umbral_flujo_min" 
                                       name="umbral_flujo_min" 
                                       value="{{ old('umbral_flujo_min') }}" 
                                       min="0">
                                @error('umbral_flujo_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="umbral_flujo_max" class="form-label">Flujo Máx (L/min)</label>
                                <input type="number" 
                                       class="form-control @error('umbral_flujo_max') is-invalid @enderror" 
                                       id="umbral_flujo_max" 
                                       name="umbral_flujo_max" 
                                       value="{{ old('umbral_flujo_max') }}" 
                                       min="0">
                                @error('umbral_flujo_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="activo" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
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
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Validación de código postal
    $('#codigo_postal').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });
});
</script>
@endpush