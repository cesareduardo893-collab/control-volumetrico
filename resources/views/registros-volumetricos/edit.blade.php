@extends('layouts.app')

@section('title', 'Editar Registro Volumétrico')
@section('header', 'Editar Registro Volumétrico')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil"></i> Editar Registro Volumétrico
                </h5>
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
                
                <form method="POST" action="{{ route('registros-volumetricos.update', $registro['id']) }}" id="registroForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @forelse($instalaciones as $instalacion)
                                    <option value="{{ $instalacion['id'] ?? $instalacion->id }}" 
                                            {{ (old('instalacion_id', $registro['instalacion_id'] ?? '') == ($instalacion['id'] ?? $instalacion->id)) ? 'selected' : '' }}>
                                        {{ $instalacion['nombre'] ?? $instalacion->nombre ?? 'Instalación' }}
                                    </option>
                                @empty
                                    <option value="">No hay instalaciones</option>
                                @endforelse
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tanque_id" class="form-label">Tanque *</label>
                            <select class="form-select select2" id="tanque_id" name="tanque_id" required>
                                <option value="">Seleccione...</option>
                                @forelse($tanques as $tanque)
                                    <option value="{{ $tanque['id'] ?? $tanque->id }}"
                                            {{ (old('tanque_id', $registro['tanque_id'] ?? '') == ($tanque['id'] ?? $tanque->id)) ? 'selected' : '' }}>
                                        {{ $tanque['identificador'] ?? $tanque->identificador ?? ('Tanque ' . ($tanque['id'] ?? $tanque->id)) }}
                                    </option>
                                @empty
                                    <option value="">No hay tanques</option>
                                @endforelse
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="producto_id" class="form-label">Producto *</label>
                            <select class="form-select select2" id="producto_id" name="producto_id" required>
                                <option value="">Seleccione...</option>
                                @forelse($productos as $producto)
                                    <option value="{{ $producto['id'] ?? $producto->id }}"
                                            {{ (old('producto_id', $registro['producto_id'] ?? '') == ($producto['id'] ?? $producto->id)) ? 'selected' : '' }}>
                                        {{ $producto['nombre'] ?? $producto->nombre ?? 'Producto' }}
                                    </option>
                                @empty
                                    <option value="">No hay productos</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="numero_registro" class="form-label">Número de Registro *</label>
                            <input type="text" class="form-control" id="numero_registro" name="numero_registro" 
                                   value="{{ old('numero_registro', $registro['numero_registro'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" 
                                   value="{{ old('fecha', $registro['fecha'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="hora_inicio" class="form-label">Hora Inicio *</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                                   value="{{ old('hora_inicio', $registro['hora_inicio'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="hora_fin" class="form-label">Hora Fin *</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                                   value="{{ old('hora_fin', $registro['hora_fin'] ?? '') }}" required>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-speedometer2"></i> Volúmenes
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="volumen_inicial" class="form-label">Volumen Inicial (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_inicial" name="volumen_inicial" 
                                   value="{{ old('volumen_inicial', $registro['volumen_inicial'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_final" class="form-label">Volumen Final (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_final" name="volumen_final" 
                                   value="{{ old('volumen_final', $registro['volumen_final'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_operacion" class="form-label">Volumen Operación (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_operacion" name="volumen_operacion" 
                                   value="{{ old('volumen_operacion', $registro['volumen_operacion'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="volumen_corregido" class="form-label">Volumen Corregido (L) *</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="volumen_corregido" name="volumen_corregido" 
                                   value="{{ old('volumen_corregido', $registro['volumen_corregido'] ?? '') }}" required>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-thermometer"></i> Condiciones
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="temperatura_inicial" class="form-label">Temp. Inicial (°C) *</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="temperatura_inicial" name="temperatura_inicial" 
                                   value="{{ old('temperatura_inicial', $registro['temperatura_inicial'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="temperatura_final" class="form-label">Temp. Final (°C) *</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="temperatura_final" name="temperatura_final" 
                                   value="{{ old('temperatura_final', $registro['temperatura_final'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="presion_inicial" class="form-label">Presión Inicial (bar)</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="presion_inicial" name="presion_inicial" 
                                   value="{{ old('presion_inicial', $registro['presion_inicial'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="presion_final" class="form-label">Presión Final (bar)</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="presion_final" name="presion_final" 
                                   value="{{ old('presion_final', $registro['presion_final'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="densidad" class="form-label">Densidad *</label>
                            <input type="number" step="0.0001" min="0" class="form-control" 
                                   id="densidad" name="densidad" 
                                   value="{{ old('densidad', $registro['densidad'] ?? '') }}" required>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="factor_correccion" class="form-label">Factor Corrección *</label>
                            <input type="number" step="0.000001" min="0" class="form-control" 
                                   id="factor_correccion" name="factor_correccion" 
                                   value="{{ old('factor_correccion', $registro['factor_correccion'] ?? '') }}" required>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_registro" class="form-label">Tipo de Registro *</label>
                            <select class="form-select" id="tipo_registro" name="tipo_registro" required>
                                <option value="operacion" {{ (old('tipo_registro', $registro['tipo_registro'] ?? '') == 'operacion') ? 'selected' : '' }}>Operación</option>
                                <option value="acumulado" {{ (old('tipo_registro', $registro['tipo_registro'] ?? '') == 'acumulado') ? 'selected' : '' }}>Acumulado</option>
                                <option value="existencias" {{ (old('tipo_registro', $registro['tipo_registro'] ?? '') == 'existencias') ? 'selected' : '' }}>Existencias</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="operacion" class="form-label">Operación *</label>
                            <select class="form-select" id="operacion" name="operacion" required>
                                <option value="recepcion" {{ (old('operacion', $registro['operacion'] ?? '') == 'recepcion') ? 'selected' : '' }}>Recepción</option>
                                <option value="entrega" {{ (old('operacion', $registro['operacion'] ?? '') == 'entrega') ? 'selected' : '' }}>Entrega</option>
                                <option value="inventario_inicial" {{ (old('operacion', $registro['operacion'] ?? '') == 'inventario_inicial') ? 'selected' : '' }}>Inventario Inicial</option>
                                <option value="inventario_final" {{ (old('operacion', $registro['operacion'] ?? '') == 'inventario_final') ? 'selected' : '' }}>Inventario Final</option>
                                <option value="venta" {{ (old('operacion', $registro['operacion'] ?? '') == 'venta') ? 'selected' : '' }}>Venta</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="PENDIENTE" {{ (old('estado', $registro['estado'] ?? '') == 'PENDIENTE') ? 'selected' : '' }}>Pendiente</option>
                                <option value="PROCESADO" {{ (old('estado', $registro['estado'] ?? '') == 'PROCESADO') ? 'selected' : '' }}>Procesado</option>
                                <option value="VALIDADO" {{ (old('estado', $registro['estado'] ?? '') == 'VALIDADO') ? 'selected' : '' }}>Validado</option>
                                <option value="CON_ALARMA" {{ (old('estado', $registro['estado'] ?? '') == 'CON_ALARMA') ? 'selected' : '' }}>Con Alarma</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medidor_id" class="form-label">Medidor (opcional)</label>
                            <select class="form-select" id="medidor_id" name="medidor_id">
                                <option value="">Sin medidor</option>
                                @forelse($medidores as $medidor)
                                    <option value="{{ $medidor['id'] ?? $medidor->id }}"
                                            {{ (old('medidor_id', $registro['medidor_id'] ?? '') == ($medidor['id'] ?? $medidor->id)) ? 'selected' : '' }}>
                                        {{ $medidor['numero_serie'] ?? $medidor->numero_serie ?? ('Medidor ' . ($medidor['id'] ?? $medidor->id)) }}
                                    </option>
                                @empty
                                    <option value="">No hay medidores</option>
                                @endforelse
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="usuario_registro_id" class="form-label">Usuario Registro</label>
                            <select class="form-select" id="usuario_registro_id" name="usuario_registro_id">
                                <option value="">Seleccione...</option>
                                @forelse($usuarios as $usuario)
                                    <option value="{{ $usuario['id'] ?? $usuario->id }}"
                                            {{ (old('usuario_registro_id', $registro['usuario_registro_id'] ?? '') == ($usuario['id'] ?? $usuario->id)) ? 'selected' : '' }}>
                                        {{ $usuario['name'] ?? $usuario->name ?? 'Usuario' }}
                                    </option>
                                @empty
                                    <option value="">No hay usuarios</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2">{{ old('observaciones', $registro['observaciones'] ?? '') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Registro
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
});
</script>
@endpush