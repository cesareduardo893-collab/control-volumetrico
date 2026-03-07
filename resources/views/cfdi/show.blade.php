@extends('layouts.app')

@section('title', 'Detalle del CFDI')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del CFDI: {{ $cfdi['uuid'] }}</h6>
                <div>
                    <a href="{{ route('cfdi.edit', $cfdi['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    @if($cfdi['estado'] == 'vigente')
                    <button type="button" class="btn btn-danger btn-sm btn-cancelar" data-id="{{ $cfdi['id'] }}">
                        <i class="fas fa-ban"></i> Cancelar
                    </button>
                    @endif
                    <a href="{{ route('cfdi.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Información General -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Información General
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">UUID</small>
                            <h6 class="mb-0">{{ $cfdi['uuid'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Contribuyente</small>
                            <h6 class="mb-0">{{ $cfdi['contribuyente']['razon_social'] ?? 'N/A' }}</h6>
                            <small>{{ $cfdi['contribuyente']['rfc'] ?? '' }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo de CFDI</small>
                            <h6 class="mb-0">
                                @switch($cfdi['tipo_cfdi'])
                                    @case('ingreso')
                                        <span class="badge bg-success">Ingreso</span>
                                        @break
                                    @case('egreso')
                                        <span class="badge bg-danger">Egreso</span>
                                        @break
                                    @case('traslado')
                                        <span class="badge bg-info">Traslado</span>
                                        @break
                                    @case('pago')
                                        <span class="badge bg-warning">Pago</span>
                                        @break
                                @endswitch
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- RFCs -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-id-card"></i> RFCs
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">RFC Emisor</small>
                            <h6 class="mb-0">{{ $cfdi['rfc_emisor'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">RFC Receptor</small>
                            <h6 class="mb-0">{{ $cfdi['rfc_receptor'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-calendar-alt"></i> Fechas
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha de Emisión</small>
                            <h6 class="mb-0">{{ $cfdi['fecha_emision'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha de Certificación</small>
                            <h6 class="mb-0">{{ $cfdi['fecha_certificacion'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Montos -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-dollar-sign"></i> Montos
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Subtotal</small>
                            <h6 class="mb-0">${{ number_format($cfdi['subtotal'], 2) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">IVA</small>
                            <h6 class="mb-0">${{ number_format($cfdi['iva'], 2) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Total</small>
                            <h6 class="mb-0 text-primary">${{ number_format($cfdi['total'], 2) }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Sellos y Certificados -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-certificate"></i> Sellos y Certificados
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Sello SAT</small>
                            <p class="mb-0 small">{{ $cfdi['sello_sat'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Sello CFDI</small>
                            <p class="mb-0 small">{{ $cfdi['sello_cfdi'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Certificado SAT</small>
                            <h6 class="mb-0">{{ $cfdi['certificado_sat'] }}</h6>
                            <small>No. {{ $cfdi['no_certificado_sat'] }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Certificado CFDI</small>
                            <h6 class="mb-0">{{ $cfdi['certificado_cfdi'] }}</h6>
                            <small>No. {{ $cfdi['no_certificado_cfdi'] }}</small>
                        </div>
                    </div>
                </div>
                
                <!-- Cadena Original -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-link"></i> Cadena Original
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <p class="mb-0 small">{{ $cfdi['cadena_original'] }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Observaciones -->
                @if($cfdi['observaciones'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-comment"></i> Observaciones
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <p class="mb-0">{{ $cfdi['observaciones'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($cfdi['estado'] == 'vigente')
                                    <span class="badge bg-success">Vigente</span>
                                @else
                                    <span class="badge bg-danger">Cancelado</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $cfdi['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $cfdi['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones adicionales -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Ver XML</h6>
                    </div>
                    <i class="fas fa-file-code fa-2x"></i>
                </div>
                <button type="button" class="btn btn-light btn-sm btn-xml" data-id="{{ $cfdi['id'] }}" style="position: relative; z-index: 2;">
                    <i class="fas fa-eye"></i> Ver XML
                </button>
            </div>
        </div>
    </div>
    
    @if($cfdi['pdf_content'])
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Ver PDF</h6>
                    </div>
                    <i class="fas fa-file-pdf fa-2x"></i>
                </div>
                <button type="button" class="btn btn-light btn-sm btn-pdf" data-id="{{ $cfdi['id'] }}" style="position: relative; z-index: 2;">
                    <i class="fas fa-eye"></i> Ver PDF
                </button>
            </div>
        </div>
    </div>
    @endif
    
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Descargar XML</h6>
                    </div>
                    <i class="fas fa-download fa-2x"></i>
                </div>
                <a href="{{ route('cfdi.descargar-xml', $cfdi['id']) }}" class="btn btn-light btn-sm" target="_blank">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>
    </div>
    
    @if($cfdi['pdf_content'])
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Descargar PDF</h6>
                    </div>
                    <i class="fas fa-file-pdf fa-2x"></i>
                </div>
                <a href="{{ route('cfdi.descargar-pdf', $cfdi['id']) }}" class="btn btn-light btn-sm" target="_blank">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Registros asociados -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registros Volumétricos Asociados</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Instalación</th>
                                <th>Tanque</th>
                                <th>Volumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cfdi['registros_volumetricos'] ?? [] as $registro)
                            <tr>
                                <td>{{ $registro['id'] }}</td>
                                <td>{{ $registro['fecha_movimiento'] }}</td>
                                <td>{{ $registro['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ $registro['tanque']['nombre'] ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format($registro['volumen_neto'], 2) }} L</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay registros asociados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<div class="modal fade" id="xmlModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">XML del CFDI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="xmlContent" style="max-height: 400px; overflow: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;"></pre>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PDF del CFDI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfFrame" style="width: 100%; height: 500px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cancelarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Cancelar CFDI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de cancelar este CFDI?</p>
                    <p class="text-muted">Una vez cancelado, no podrá ser revertido.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Botón XML
    $('.btn-xml').click(function() {
        var id = $(this).data('id');
        
        $.get('/cfdi/' + id + '/xml', function(data) {
            var xmlContent = data.xml_content;
            var formattedXml = formatXml(xmlContent);
            $('#xmlContent').text(formattedXml);
            $('#xmlModal').modal('show');
        });
    });

    // Botón PDF
    $('.btn-pdf').click(function() {
        var id = $(this).data('id');
        $('#pdfFrame').attr('src', '/cfdi/' + id + '/pdf');
        $('#pdfModal').modal('show');
    });

    // Botón cancelar
    $('.btn-cancelar').click(function() {
        var id = $(this).data('id');
        var form = $('#cancelarForm');
        form.attr('action', '{{ url("cfdi") }}/' + id + '/cancelar');
        $('#cancelarModal').modal('show');
    });

    // Función para formatear XML
    function formatXml(xml) {
        var formatted = '';
        var reg = /(>)(<)(\/*)/g;
        xml = xml.replace(reg, '$1\r\n$2$3');
        var pad = 0;
        jQuery.each(xml.split('\r\n'), function(index, node) {
            var indent = 0;
            if (node.match(/.+<\/\w[^>]*>$/)) {
                indent = 0;
            } else if (node.match(/^<\/\w/)) {
                if (pad != 0) {
                    pad -= 1;
                }
            } else if (node.match(/^<\w[^>]*[^\/]>.*$/)) {
                indent = 1;
            } else {
                indent = 0;
            }

            var padding = '';
            for (var i = 0; i < pad; i++) {
                padding += '  ';
            }

            formatted += padding + node + '\r\n';
            pad += indent;
        });

        return formatted;
    }
});
</script>
@endpush