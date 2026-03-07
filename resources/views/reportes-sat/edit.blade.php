@extends('layouts.app')

@section('title', 'Editar Reporte SAT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Reporte SAT #{{ $reporte['id'] }}</h6>
            </div>
            <div class="card-body">
                @if(!in_array($reporte['estado'], ['generado', 'error']))
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    Este reporte no puede ser editado porque se encuentra en estado "{{ $reporte['estado'] }}".
                </div>
                @endif
                
                <form method="POST" action="{{ route('reportes-sat.update', $reporte['id']) }}" id="reporteForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del Reporte</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instalacion_id" class="form-label">Instalación <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('instalacion_id') is-invalid @enderror" 
                                        id="instalacion_id" 
                                        name="instalacion_id" 
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione una instalación...</option>
                                    @foreach($instalaciones['data'] ?? [] as $instalacion)
                                        <option value="{{ $instalacion['id'] }}" 
                                            {{ old('instalacion_id', $reporte['instalacion_id']) == $instalacion['id'] ? 'selected' : '' }}>
                                            {{ $instalacion['clave_instalacion'] }} - {{ $instalacion['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']))
                                    <input type="hidden" name="instalacion_id" value="{{ $reporte['instalacion_id'] }}">
                                @endif
                                @error('instalacion_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="periodo" class="form-label">Periodo <span class="text-danger">*</span></label>
                                <select class="form-select @error('periodo') is-invalid @enderror" 
                                        id="periodo" 
                                        name="periodo" 
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="diario" {{ old('periodo', $reporte['periodo']) == 'diario' ? 'selected' : '' }}>Diario</option>
                                    <option value="semanal" {{ old('periodo', $reporte['periodo']) == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                    <option value="quincenal" {{ old('periodo', $reporte['periodo']) == 'quincenal' ? 'selected' : '' }}>Quincenal</option>
                                    <option value="mensual" {{ old('periodo', $reporte['periodo']) == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']))
                                    <input type="hidden" name="periodo" value="{{ $reporte['periodo'] }}">
                                @endif
                                @error('periodo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Período de tiempo -->
                    <h5 class="mb-3 mt-4">Período del Reporte</h5>
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="anio" class="form-label">Año <span class="text-danger">*</span></label>
                                <select class="form-select @error('anio') is-invalid @enderror" 
                                        id="anio" 
                                        name="anio" 
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione...</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ old('anio', $reporte['anio']) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']))
                                    <input type="hidden" name="anio" value="{{ $reporte['anio'] }}">
                                @endif
                                @error('anio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mes" class="form-label">Mes <span class="text-danger">*</span></label>
                                <select class="form-select @error('mes') is-invalid @enderror" 
                                        id="mes" 
                                        name="mes" 
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('mes', $reporte['mes']) == $i ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']))
                                    <input type="hidden" name="mes" value="{{ $reporte['mes'] }}">
                                @endif
                                @error('mes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="semana" class="form-label">Semana</label>
                                <input type="number" 
                                       class="form-control @error('semana') is-invalid @enderror" 
                                       id="semana" 
                                       name="semana" 
                                       value="{{ old('semana', $reporte['semana']) }}" 
                                       min="1" 
                                       max="52"
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}>
                                @error('semana')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dia" class="form-label">Día</label>
                                <input type="number" 
                                       class="form-control @error('dia') is-invalid @enderror" 
                                       id="dia" 
                                       name="dia" 
                                       value="{{ old('dia', $reporte['dia']) }}" 
                                       min="1" 
                                       max="31"
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}>
                                @error('dia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fechas -->
                    <h5 class="mb-3 mt-4">Fechas del Período</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_inicio') is-invalid @enderror" 
                                       id="fecha_inicio" 
                                       name="fecha_inicio" 
                                       value="{{ old('fecha_inicio', $reporte['fecha_inicio']) }}" 
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}
                                       required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_fin" class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_fin') is-invalid @enderror" 
                                       id="fecha_fin" 
                                       name="fecha_fin" 
                                       value="{{ old('fecha_fin', $reporte['fecha_fin']) }}" 
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}
                                       required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estadísticas de registros -->
                    <h5 class="mb-3 mt-4">Estadísticas de Registros</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="registros_generados" class="form-label">Registros Generados <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('registros_generados') is-invalid @enderror" 
                                       id="registros_generados" 
                                       name="registros_generados" 
                                       value="{{ old('registros_generados', $reporte['registros_generados']) }}" 
                                       min="0" 
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}
                                       required>
                                @error('registros_generados')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="registros_validos" class="form-label">Registros Válidos <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('registros_validos') is-invalid @enderror" 
                                       id="registros_validos" 
                                       name="registros_validos" 
                                       value="{{ old('registros_validos', $reporte['registros_validos']) }}" 
                                       min="0" 
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}
                                       required>
                                @error('registros_validos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="registros_invalidos" class="form-label">Registros Inválidos <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('registros_invalidos') is-invalid @enderror" 
                                       id="registros_invalidos" 
                                       name="registros_invalidos" 
                                       value="{{ old('registros_invalidos', $reporte['registros_invalidos']) }}" 
                                       min="0" 
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}
                                       required>
                                @error('registros_invalidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Usuarios -->
                    <h5 class="mb-3 mt-4">Usuarios Responsables</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usuario_generacion" class="form-label">Usuario Generación <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('usuario_generacion') is-invalid @enderror" 
                                        id="usuario_generacion" 
                                        name="usuario_generacion" 
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}
                                        required>
                                    <option value="">Seleccione un usuario...</option>
                                    @foreach($usuarios ?? [] as $usuario)
                                        <option value="{{ $usuario['id'] }}" 
                                            {{ old('usuario_generacion', $reporte['usuario_generacion']) == $usuario['id'] ? 'selected' : '' }}>
                                            {{ $usuario['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']))
                                    <input type="hidden" name="usuario_generacion" value="{{ $reporte['usuario_generacion'] }}">
                                @endif
                                @error('usuario_generacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usuario_firma" class="form-label">Usuario Firma</label>
                                <select class="form-select select2 @error('usuario_firma') is-invalid @enderror" 
                                        id="usuario_firma" 
                                        name="usuario_firma"
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}>
                                    <option value="">Seleccione un usuario...</option>
                                    @foreach($usuarios ?? [] as $usuario)
                                        <option value="{{ $usuario['id'] }}" 
                                            {{ old('usuario_firma', $reporte['usuario_firma']) == $usuario['id'] ? 'selected' : '' }}>
                                            {{ $usuario['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']) && $reporte['usuario_firma'])
                                    <input type="hidden" name="usuario_firma" value="{{ $reporte['usuario_firma'] }}">
                                @endif
                                @error('usuario_firma')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="usuario_envio" class="form-label">Usuario Envío</label>
                                <select class="form-select select2 @error('usuario_envio') is-invalid @enderror" 
                                        id="usuario_envio" 
                                        name="usuario_envio"
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}>
                                    <option value="">Seleccione un usuario...</option>
                                    @foreach($usuarios ?? [] as $usuario)
                                        <option value="{{ $usuario['id'] }}" 
                                            {{ old('usuario_envio', $reporte['usuario_envio']) == $usuario['id'] ? 'selected' : '' }}>
                                            {{ $usuario['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']) && $reporte['usuario_envio'])
                                    <input type="hidden" name="usuario_envio" value="{{ $reporte['usuario_envio'] }}">
                                @endif
                                @error('usuario_envio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <h5 class="mb-3 mt-4">Estado</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" 
                                        name="estado" 
                                        {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}
                                        required>
                                    <option value="generado" {{ old('estado', $reporte['estado']) == 'generado' ? 'selected' : '' }}>Generado</option>
                                    <option value="firmado" {{ old('estado', $reporte['estado']) == 'firmado' ? 'selected' : '' }}>Firmado</option>
                                    <option value="enviado" {{ old('estado', $reporte['estado']) == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                    <option value="recibido" {{ old('estado', $reporte['estado']) == 'recibido' ? 'selected' : '' }}>Recibido</option>
                                    <option value="error" {{ old('estado', $reporte['estado']) == 'error' ? 'selected' : '' }}>Error</option>
                                </select>
                                @if(!in_array($reporte['estado'], ['generado', 'error']))
                                    <input type="hidden" name="estado" value="{{ $reporte['estado'] }}">
                                @endif
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-check mt-4">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="activo" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', $reporte['activo']) ? 'checked' : '' }}
                                       {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'disabled' : '' }}>
                                <label class="form-check-label" for="activo">Registro Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Observaciones -->
                    <h5 class="mb-3 mt-4">Observaciones</h5>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          id="observaciones" 
                                          name="observaciones" 
                                          rows="3"
                                          {{ !in_array($reporte['estado'], ['generado', 'error']) ? 'readonly' : '' }}>{{ old('observaciones', $reporte['observaciones']) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    @if(in_array($reporte['estado'], ['generado', 'error']))
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('reportes-sat.show', $reporte['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('reportes-sat.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('reportes-sat.show', $reporte['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('reportes-sat.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Volver
                            </a>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if(in_array($reporte['estado'], ['generado', 'error']))
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Validar que fecha_fin sea posterior a fecha_inicio
    $('#fecha_inicio, #fecha_fin').on('change', function() {
        var inicio = new Date($('#fecha_inicio').val());
        var fin = new Date($('#fecha_fin').val());
        
        if (fin <= inicio) {
            $('#fecha_fin').addClass('is-invalid');
            $('#fecha_fin').next('.invalid-feedback').text('La fecha fin debe ser posterior a la fecha inicio');
        } else {
            $('#fecha_fin').removeClass('is-invalid');
        }
    });
    
    // Validar que registros_validos + registros_invalidos = registros_generados
    $('#registros_generados, #registros_validos, #registros_invalidos').on('input', function() {
        var generados = parseInt($('#registros_generados').val()) || 0;
        var validos = parseInt($('#registros_validos').val()) || 0;
        var invalidos = parseInt($('#registros_invalidos').val()) || 0;
        
        if (validos + invalidos != generados) {
            $('#registros_validos, #registros_invalidos').addClass('is-invalid');
            if (validos + invalidos > generados) {
                $('#registros_validos').next('.invalid-feedback').text('La suma de válidos e inválidos no puede exceder los generados');
            }
        } else {
            $('#registros_validos, #registros_invalidos').removeClass('is-invalid');
        }
    });
    
    // Trigger validación inicial
    $('#fecha_inicio, #fecha_fin').trigger('change');
    $('#registros_generados, #registros_validos, #registros_invalidos').trigger('input');
    @endif
});
</script>
@endpush