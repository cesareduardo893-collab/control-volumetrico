@extends('layouts.app')

@section('title', 'Logs del Sistema')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Logs del Sistema</h6>
                <a href="{{ route('configuracion.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <!-- Selector de archivo de log -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select" id="logFile">
                            <option value="laravel">Laravel Log</option>
                            <option value="app">Application Log</option>
                            <option value="api">API Log</option>
                            <option value="cron">Cron Log</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <button class="btn btn-primary btn-sm" id="btnRefresh">
                            <i class="fas fa-sync"></i> Refrescar
                        </button>
                        <button class="btn btn-success btn-sm" id="btnDownload">
                            <i class="fas fa-download"></i> Descargar
                        </button>
                        <button class="btn btn-danger btn-sm" id="btnClear">
                            <i class="fas fa-trash"></i> Limpiar
                        </button>
                    </div>
                </div>
                
                <!-- Contenido del log -->
                <div class="row">
                    <div class="col-12">
                        <div class="border rounded p-3 bg-light" style="max-height: 600px; overflow: auto;">
                            <pre id="logContent" class="mb-0" style="font-size: 12px;">{{ $logs }}</pre>
                        </div>
                    </div>
                </div>
                
                <!-- Información del log -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Tamaño:</strong> <span id="logSize">{{ number_format(strlen($logs) / 1024, 2) }} KB</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Líneas:</strong> <span id="logLines">{{ substr_count($logs, "\n") + 1 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal confirmar limpieza -->
<div class="modal fade" id="clearLogModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Limpiar Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de limpiar el archivo de log?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmClear">Limpiar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.log-error { color: #dc3545; }
.log-warning { color: #ffc107; }
.log-info { color: #0dcaf0; }
.log-debug { color: #6c757d; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Cargar log inicial
    loadLog();
    
    // Refrescar log
    $('#btnRefresh').click(function() {
        loadLog();
    });
    
    // Cambiar archivo de log
    $('#logFile').change(function() {
        loadLog();
    });
    
    // Descargar log
    $('#btnDownload').click(function() {
        var logFile = $('#logFile').val();
        window.location.href = '{{ url("configuracion/logs/download") }}?file=' + logFile;
    });
    
    // Limpiar log
    $('#btnClear').click(function() {
        $('#clearLogModal').modal('show');
    });
    
    $('#confirmClear').click(function() {
        var logFile = $('#logFile').val();
        
        $.ajax({
            url: '{{ route("configuracion.logs.clear") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                file: logFile
            },
            success: function(response) {
                $('#clearLogModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Log limpiado',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    loadLog();
                });
            },
            error: function(xhr) {
                $('#clearLogModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Error al limpiar el log'
                });
            }
        });
    });
    
    // Función para cargar log
    function loadLog() {
        var logFile = $('#logFile').val();
        
        $.get('{{ url("configuracion/logs/view") }}', { file: logFile }, function(response) {
            var content = response.content;
            var formattedContent = formatLog(content);
            
            $('#logContent').html(formattedContent);
            $('#logSize').text((response.size / 1024).toFixed(2) + ' KB');
            $('#logLines').text(response.lines);
        }).fail(function(xhr) {
            $('#logContent').text('Error al cargar el log: ' + (xhr.responseJSON?.message || 'Error desconocido'));
        });
    }
    
    // Función para formatear log con colores
    function formatLog(content) {
        var lines = content.split('\n');
        var formatted = '';
        
        $.each(lines, function(index, line) {
            var className = '';
            
            if (line.includes('.ERROR:')) {
                className = 'log-error';
            } else if (line.includes('.WARNING:')) {
                className = 'log-warning';
            } else if (line.includes('.INFO:')) {
                className = 'log-info';
            } else if (line.includes('.DEBUG:')) {
                className = 'log-debug';
            }
            
            formatted += '<div class="' + className + '">' + escapeHtml(line) + '</div>';
        });
        
        return formatted;
    }
    
    // Función para escapar HTML
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});
</script>
@endpush