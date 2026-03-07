@extends('layouts.app')

@section('title', 'Editar Certificado de Verificación')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Certificado: {{ $certificado['numero_certificado'] }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('certificados-verificacion.update', $certificado['id']) }}" id="certificadoForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del Certificado</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instalacion_id" class="form-label">Instalación <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('instalacion_id') is-invalid @enderror" 
                                        id="instalacion_id" 
                                        name="instalacion_id" 
                                        required>
                                    <option value="">Seleccione una instalación...</option>
                                    @foreach($instalaciones['data'] ?? [] as $instalacion)
                                        <option value="{{ $instalacion['id'] }}" 
                                            {{ old('instalacion_id', $certificado['instalacion_id']) == $instalacion['id'] ? 'selected' : '' }}>
                                            {{ $instalacion['clave_instalacion'] }} - {{ $instalacion['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instalacion_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero_certificado" class="form-label">Número de Certificado <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('numero_certificado') is-invalid @enderror" 
                                       id="numero_certificado" 
                                       name="numero_certificado" 
                                       value="{{ old('numero_certificado', $certificado['numero_certificado']) }}" 
                                       maxlength="50"
                                       required>
                                @error('numero_certificado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_certificado" class="form-label">Tipo de Certificado <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_certificado') is-invalid @enderror" 
                                        id="tipo_certificado" 
                                        name="tipo_certificado" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="inicial" {{ old('tipo_certificado', $certificado['tipo_certificado']) == 'inicial' ? 'selected' : '' }}>Inicial</option>
                                    <option value="periodico" {{ old('tipo_certificado', $certificado['tipo_certificado']) == 'periodico' ? 'selected' : '' }}>Periódico</option>
                                    <option value="extraordinario" {{ old('tipo_certificado', $certificado['tipo_certificado']) == 'extraordinario' ? 'selected' : '' }}>Extraordinario</option>
                                </select>
                                @error('tipo_certificado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fechas -->
                    <h5 class="mb-3 mt-4">Fechas</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_emision" class="form-label">Fecha de Emisión <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_emision') is-invalid @enderror" 
                                       id="fecha_emision" 
                                       name="fecha_emision" 
                                       value="{{ old('fecha_emision', $certificado['fecha_emision']) }}" 
                                       required>
                                @error('fecha_emision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_vigencia" class="form-label">Fecha de Vigencia <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_vigencia') is-invalid @enderror" 
                                       id="fecha_vigencia" 
                                       name="fecha_vigencia" 
                                       value="{{ old('fecha_vigencia', $certificado['fecha_vigencia']) }}" 
                                       required>
                                @error('fecha_vigencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resultados -->
                    <h5 class="mb-3 mt-4">Resultados</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estatus" class="form-label">Estatus <span class="text-danger">*</span></label>
                                <select class="form-select @error('estatus') is-invalid @enderror" 
                                        id="estatus" 
                                        name="estatus" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="aprobado" {{ old('estatus', $certificado['estatus']) == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="rechazado" {{ old('estatus', $certificado['estatus']) == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    <option value="pendiente" {{ old('estatus', $certificado['estatus']) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                </select>
                                @error('estatus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="resultado" class="form-label">Resultado <span class="text-danger">*</span></label>
                                <select class="form-select @error('resultado') is-invalid @enderror" 
                                        id="resultado" 
                                        name="resultado" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="aprobado" {{ old('resultado', $certificado['resultado']) == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="rechazado" {{ old('resultado', $certificado['resultado']) == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                    <option value="observaciones" {{ old('resultado', $certificado['resultado']) == 'observaciones' ? 'selected' : '' }}>Con Observaciones</option>
                                </select>
                                @error('resultado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Puntos de Verificación -->
                    <h5 class="mb-3 mt-4">Puntos de Verificación</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="puntos_criticos" class="form-label">Puntos Críticos</label>
                                <input type="number" 
                                       class="form-control @error('puntos_criticos') is-invalid @enderror" 
                                       id="puntos_criticos" 
                                       name="puntos_criticos" 
                                       value="{{ old('puntos_criticos', $certificado['puntos_criticos']) }}" 
                                       min="0" 
                                       step="1">
                                @error('puntos_criticos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="puntos_atencion" class="form-label">Puntos de Atención</label>
                                <input type="number" 
                                       class="form-control @error('puntos_atencion') is-invalid @enderror" 
                                       id="puntos_atencion" 
                                       name="puntos_atencion" 
                                       value="{{ old('puntos_atencion', $certificado['puntos_atencion']) }}" 
                                       min="0" 
                                       step="1">
                                @error('puntos_atencion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="puntos_leves" class="form-label">Puntos Leves</label>
                                <input type="number" 
                                       class="form-control @error('puntos_leves') is-invalid @enderror" 
                                       id="puntos_leves" 
                                       name="puntos_leves" 
                                       value="{{ old('puntos_leves', $certificado['puntos_leves']) }}" 
                                       min="0" 
                                       step="1">
                                @error('puntos_leves')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Usuarios -->
                    <h5 class="mb-3 mt-4">Usuarios</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_elaboracion" class="form-label">Usuario Elaboración <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('usuario_elaboracion') is-invalid @enderror" 
                                        id="usuario_elaboracion" 
                                        name="usuario_elaboracion" 
                                        required>
                                    <option value="">Seleccione un usuario...</option>
                                    @foreach($usuarios ?? [] as $usuario)
                                        <option value="{{ $usuario['id'] }}" 
                                            {{ old('usuario_elaboracion', $certificado['usuario_elaboracion']) == $usuario['id'] ? 'selected' : '' }}>
                                            {{ $usuario['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('usuario_elaboracion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario_autorizacion" class="form-label">Usuario Autorización</label>
                                <select class="form-select select2 @error('usuario_autorizacion') is-invalid @enderror" 
                                        id="usuario_autorizacion" 
                                        name="usuario_autorizacion">
                                    <option value="">Seleccione un usuario...</option>
                                    @foreach($usuarios ?? [] as $usuario)
                                        <option value="{{ $usuario['id'] }}" 
                                            {{ old('usuario_autorizacion', $certificado['usuario_autorizacion']) == $usuario['id'] ? 'selected' : '' }}>
                                            {{ $usuario['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('usuario_autorizacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                          rows="4">{{ old('observaciones', $certificado['observaciones']) }}</textarea>
                                @error('observaciones')
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
                                       {{ old('activo', $certificado['activo']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Registro Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('certificados-verificacion.show', $certificado['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('certificados-verificacion.index') }}" class="btn btn-secondary">
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
    
    // Validar que fecha_vigencia sea posterior a fecha_emision
    $('#fecha_emision, #fecha_vigencia').on('change', function() {
        var emision = new Date($('#fecha_emision').val());
        var vigencia = new Date($('#fecha_vigencia').val());
        
        if (vigencia <= emision) {
            $('#fecha_vigencia').addClass('is-invalid');
            $('#fecha_vigencia').next('.invalid-feedback').text('La fecha de vigencia debe ser posterior a la fecha de emisión');
        } else {
            $('#fecha_vigencia').removeClass('is-invalid');
        }
    });
    
    // Trigger validación inicial
    $('#fecha_emision, #fecha_vigencia').trigger('change');
});
</script>
@endpush