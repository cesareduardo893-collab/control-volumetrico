@extends('layouts.app')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Configuración del Sistema</h6>
                <div>
                    <a href="{{ route('configuracion.logs') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-history"></i> Ver Logs
                    </a>
                    <a href="{{ route('configuracion.exportar') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Exportar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('configuracion.update') }}" id="configForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información General -->
                    <h5 class="mb-3">Información General</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_sistema" class="form-label">Nombre del Sistema <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nombre_sistema') is-invalid @enderror" 
                                       id="nombre_sistema" 
                                       name="nombre_sistema" 
                                       value="{{ old('nombre_sistema', $configuracion['nombre_sistema'] ?? 'Sistema de Gestión Volumétrica') }}" 
                                       required>
                                @error('nombre_sistema')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="version_sistema" class="form-label">Versión <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('version_sistema') is-invalid @enderror" 
                                       id="version_sistema" 
                                       name="version_sistema" 
                                       value="{{ old('version_sistema', $configuracion['version_sistema'] ?? '1.0.0') }}" 
                                       required>
                                @error('version_sistema')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="empresa" class="form-label">Empresa <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('empresa') is-invalid @enderror" 
                                       id="empresa" 
                                       name="empresa" 
                                       value="{{ old('empresa', $configuracion['empresa'] ?? '') }}" 
                                       required>
                                @error('empresa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contacto -->
                    <h5 class="mb-3 mt-4">Contacto</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" 
                                       class="form-control @error('direccion') is-invalid @enderror" 
                                       id="direccion" 
                                       name="direccion" 
                                       value="{{ old('direccion', $configuracion['direccion'] ?? '') }}">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono', $configuracion['telefono'] ?? '') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email" class="form-label">Email de contacto</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $configuracion['email'] ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personalización -->
                    <h5 class="mb-3 mt-4">Personalización</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="color_principal" class="form-label">Color Principal</label>
                                <div class="input-group">
                                    <input type="color" 
                                           class="form-control form-control-color" 
                                           id="color_principal" 
                                           name="color_principal" 
                                           value="{{ old('color_principal', $configuracion['color_principal'] ?? '#0d6efd') }}"
                                           style="width: 50px;">
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ old('color_principal', $configuracion['color_principal'] ?? '#0d6efd') }}"
                                           readonly>
                                </div>
                                @error('color_principal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="color_secundario" class="form-label">Color Secundario</label>
                                <div class="input-group">
                                    <input type="color" 
                                           class="form-control form-control-color" 
                                           id="color_secundario" 
                                           name="color_secundario" 
                                           value="{{ old('color_secundario', $configuracion['color_secundario'] ?? '#6c757d') }}"
                                           style="width: 50px;">
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ old('color_secundario', $configuracion['color_secundario'] ?? '#6c757d') }}"
                                           readonly>
                                </div>
                                @error('color_secundario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logo" class="form-label">Logo (URL o Base64)</label>
                                <input type="text" 
                                       class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" 
                                       name="logo" 
                                       value="{{ old('logo', $configuracion['logo'] ?? '') }}">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Configuración General -->
                    <h5 class="mb-3 mt-4">Configuración General</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="maximo_registros" class="form-label">Máx. registros por página</label>
                                <input type="number" 
                                       class="form-control @error('maximo_registros') is-invalid @enderror" 
                                       id="maximo_registros" 
                                       name="maximo_registros" 
                                       value="{{ old('maximo_registros', $configuracion['maximo_registros'] ?? 25) }}" 
                                       min="10" 
                                       max="100">
                                @error('maximo_registros')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tiempo_sesion" class="form-label">Tiempo de sesión (minutos)</label>
                                <input type="number" 
                                       class="form-control @error('tiempo_sesion') is-invalid @enderror" 
                                       id="tiempo_sesion" 
                                       name="tiempo_sesion" 
                                       value="{{ old('tiempo_sesion', $configuracion['tiempo_sesion'] ?? 120) }}" 
                                       min="15" 
                                       max="480">
                                @error('tiempo_sesion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="auditoria_activa" 
                                       name="auditoria_activa" 
                                       value="1" 
                                       {{ old('auditoria_activa', $configuracion['auditoria_activa'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="auditoria_activa">
                                    Activar auditoría (registro en bitácora)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Backup -->
                    <h5 class="mb-3 mt-4">Configuración de Backup</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="backup_automatico" 
                                       name="backup_automatico" 
                                       value="1" 
                                       {{ old('backup_automatico', $configuracion['backup_automatico'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="backup_automatico">
                                    Backup automático
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="intervalo_backup" class="form-label">Intervalo (días)</label>
                                <input type="number" 
                                       class="form-control @error('intervalo_backup') is-invalid @enderror" 
                                       id="intervalo_backup" 
                                       name="intervalo_backup" 
                                       value="{{ old('intervalo_backup', $configuracion['intervalo_backup'] ?? 7) }}" 
                                       min="1" 
                                       max="30">
                                @error('intervalo_backup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ruta_backup" class="form-label">Ruta de backup</label>
                                <input type="text" 
                                       class="form-control @error('ruta_backup') is-invalid @enderror" 
                                       id="ruta_backup" 
                                       name="ruta_backup" 
                                       value="{{ old('ruta_backup', $configuracion['ruta_backup'] ?? storage_path('backups')) }}">
                                @error('ruta_backup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notificaciones -->
                    <h5 class="mb-3 mt-4">Configuración de Notificaciones</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="notificaciones_activas" 
                                       name="notificaciones_activas" 
                                       value="1" 
                                       {{ old('notificaciones_activas', $configuracion['notificaciones_activas'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notificaciones_activas">
                                    Activar notificaciones
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email_notificaciones" class="form-label">Email para notificaciones</label>
                                <input type="email" 
                                       class="form-control @error('email_notificaciones') is-invalid @enderror" 
                                       id="email_notificaciones" 
                                       name="email_notificaciones" 
                                       value="{{ old('email_notificaciones', $configuracion['email_notificaciones'] ?? '') }}">
                                @error('email_notificaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Configuración SMTP -->
                    <div id="smtpConfig" style="{{ old('notificaciones_activas', $configuracion['notificaciones_activas'] ?? false) ? '' : 'display: none;' }}">
                        <h5 class="mb-3 mt-4">Configuración SMTP</h5>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="smtp_host" class="form-label">Host SMTP</label>
                                    <input type="text" 
                                           class="form-control @error('smtp_host') is-invalid @enderror" 
                                           id="smtp_host" 
                                           name="smtp_host" 
                                           value="{{ old('smtp_host', $configuracion['smtp_host'] ?? '') }}">
                                    @error('smtp_host')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="smtp_port" class="form-label">Puerto</label>
                                    <input type="number" 
                                           class="form-control @error('smtp_port') is-invalid @enderror" 
                                           id="smtp_port" 
                                           name="smtp_port" 
                                           value="{{ old('smtp_port', $configuracion['smtp_port'] ?? 587) }}" 
                                           min="1" 
                                           max="65535">
                                    @error('smtp_port')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="smtp_encryption" class="form-label">Encriptación</label>
                                    <select class="form-select @error('smtp_encryption') is-invalid @enderror" 
                                            id="smtp_encryption" 
                                            name="smtp_encryption">
                                        <option value="none" {{ old('smtp_encryption', $configuracion['smtp_encryption'] ?? '') == 'none' ? 'selected' : '' }}>Ninguna</option>
                                        <option value="tls" {{ old('smtp_encryption', $configuracion['smtp_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('smtp_encryption', $configuracion['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                    @error('smtp_encryption')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="smtp_user" class="form-label">Usuario</label>
                                    <input type="text" 
                                           class="form-control @error('smtp_user') is-invalid @enderror" 
                                           id="smtp_user" 
                                           name="smtp_user" 
                                           value="{{ old('smtp_user', $configuracion['smtp_user'] ?? '') }}">
                                    @error('smtp_user')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="smtp_pass" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('smtp_pass') is-invalid @enderror" 
                                               id="smtp_pass" 
                                               name="smtp_pass" 
                                               value="{{ old('smtp_pass', $configuracion['smtp_pass'] ?? '') }}">
                                        <button class="btn btn-outline-secondary" type="button" id="toggleSmtpPass">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('smtp_pass')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Configuración
                            </button>
                            <button type="button" class="btn btn-warning" id="btnBackupManual">
                                <i class="fas fa-database"></i> Backup Manual
                            </button>
                            <button type="button" class="btn btn-info" id="btnLimpiarCache">
                                <i class="fas fa-trash-alt"></i> Limpiar Caché
                            </button>
                            <a href="{{ route('configuracion.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Importar -->
<div class="modal fade" id="importarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="importarForm" method="POST" action="{{ route('configuracion.importar') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Importar Configuración</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="config_file" class="form-label">Archivo de configuración (JSON)</label>
                        <input type="file" class="form-control" id="config_file" name="config_file" accept=".json" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mostrar/ocultar configuración SMTP
    $('#notificaciones_activas').change(function() {
        if ($(this).is(':checked')) {
            $('#smtpConfig').slideDown();
        } else {
            $('#smtpConfig').slideUp();
        }
    });
    
    // Toggle password visibility
    $('#toggleSmtpPass').click(function() {
        var passwordInput = $('#smtp_pass');
        var icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Backup manual
    $('#btnBackupManual').click(function() {
        Swal.fire({
            title: '¿Iniciar backup manual?',
            text: 'Se creará una copia de seguridad de la base de datos',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, iniciar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("configuracion.backup-manual") }}', {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Backup completado',
                        text: response.message || 'Backup creado exitosamente'
                    });
                }).fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Error al crear backup'
                    });
                });
            }
        });
    });
    
    // Limpiar caché
    $('#btnLimpiarCache').click(function() {
        Swal.fire({
            title: '¿Limpiar caché?',
            text: 'Se eliminarán los archivos temporales y caché del sistema',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("configuracion.limpiar-cache") }}', {
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Caché limpiado',
                        text: response.message || 'Caché limpiado exitosamente'
                    });
                }).fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Error al limpiar caché'
                    });
                });
            }
        });
    });
});
</script>
@endpush