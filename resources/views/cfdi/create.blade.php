@extends('layouts.app')

@section('title', 'Nuevo CFDI')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo CFDI</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('cfdi.store') }}" id="cfdiForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del CFDI</h5>
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
                                <label for="uuid" class="form-label">UUID <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('uuid') is-invalid @enderror" 
                                       id="uuid" 
                                       name="uuid" 
                                       value="{{ old('uuid') }}" 
                                       maxlength="36"
                                       placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX"
                                       required>
                                @error('uuid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_cfdi" class="form-label">Tipo de CFDI <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_cfdi') is-invalid @enderror" 
                                        id="tipo_cfdi" 
                                        name="tipo_cfdi" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="ingreso" {{ old('tipo_cfdi') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                                    <option value="egreso" {{ old('tipo_cfdi') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                                    <option value="traslado" {{ old('tipo_cfdi') == 'traslado' ? 'selected' : '' }}>Traslado</option>
                                    <option value="pago" {{ old('tipo_cfdi') == 'pago' ? 'selected' : '' }}>Pago</option>
                                </select>
                                @error('tipo_cfdi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- RFCs -->
                    <h5 class="mb-3 mt-4">RFCs</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rfc_emisor" class="form-label">RFC Emisor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('rfc_emisor') is-invalid @enderror" 
                                       id="rfc_emisor" 
                                       name="rfc_emisor" 
                                       value="{{ old('rfc_emisor') }}" 
                                       maxlength="13"
                                       required>
                                @error('rfc_emisor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rfc_receptor" class="form-label">RFC Receptor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('rfc_receptor') is-invalid @enderror" 
                                       id="rfc_receptor" 
                                       name="rfc_receptor" 
                                       value="{{ old('rfc_receptor') }}" 
                                       maxlength="13"
                                       required>
                                @error('rfc_receptor')
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
                                       class="form-control datetimepicker @error('fecha_emision') is-invalid @enderror" 
                                       id="fecha_emision" 
                                       name="fecha_emision" 
                                       value="{{ old('fecha_emision') }}" 
                                       required>
                                @error('fecha_emision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_certificacion" class="form-label">Fecha de Certificación <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datetimepicker @error('fecha_certificacion') is-invalid @enderror" 
                                       id="fecha_certificacion" 
                                       name="fecha_certificacion" 
                                       value="{{ old('fecha_certificacion') }}" 
                                       required>
                                @error('fecha_certificacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Montos -->
                    <h5 class="mb-3 mt-4">Montos</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('subtotal') is-invalid @enderror" 
                                           id="subtotal" 
                                           name="subtotal" 
                                           value="{{ old('subtotal') }}" 
                                           min="0" 
                                           step="0.01"
                                           required>
                                </div>
                                @error('subtotal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="iva" class="form-label">IVA <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('iva') is-invalid @enderror" 
                                           id="iva" 
                                           name="iva" 
                                           value="{{ old('iva') }}" 
                                           min="0" 
                                           step="0.01"
                                           required>
                                </div>
                                @error('iva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total" class="form-label">Total <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('total') is-invalid @enderror" 
                                           id="total" 
                                           name="total" 
                                           value="{{ old('total') }}" 
                                           min="0" 
                                           step="0.01"
                                           readonly>
                                </div>
                                @error('total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Archivos XML y PDF -->
                    <h5 class="mb-3 mt-4">Archivos</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="xml_file" class="form-label">Archivo XML</label>
                                <input type="file" 
                                       class="form-control @error('xml_file') is-invalid @enderror" 
                                       id="xml_file" 
                                       name="xml_file" 
                                       accept=".xml">
                                <small class="text-muted">Opcional si pega el contenido XML abajo</small>
                                @error('xml_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pdf_file" class="form-label">Archivo PDF</label>
                                <input type="file" 
                                       class="form-control @error('pdf_file') is-invalid @enderror" 
                                       id="pdf_file" 
                                       name="pdf_file" 
                                       accept=".pdf">
                                @error('pdf_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="xml_content" class="form-label">Contenido XML</label>
                                <textarea class="form-control @error('xml_content') is-invalid @enderror" 
                                          id="xml_content" 
                                          name="xml_content" 
                                          rows="10">{{ old('xml_content') }}</textarea>
                                <small class="text-muted">Pegue el contenido XML aquí o suba un archivo</small>
                                @error('xml_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sellos y Certificados -->
                    <h5 class="mb-3 mt-4">Sellos y Certificados</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sello_sat" class="form-label">Sello SAT <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('sello_sat') is-invalid @enderror" 
                                          id="sello_sat" 
                                          name="sello_sat" 
                                          rows="3"
                                          required>{{ old('sello_sat') }}</textarea>
                                @error('sello_sat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sello_cfdi" class="form-label">Sello CFDI <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('sello_cfdi') is-invalid @enderror" 
                                          id="sello_cfdi" 
                                          name="sello_cfdi" 
                                          rows="3"
                                          required>{{ old('sello_cfdi') }}</textarea>
                                @error('sello_cfdi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificado_sat" class="form-label">Certificado SAT <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('certificado_sat') is-invalid @enderror" 
                                       id="certificado_sat" 
                                       name="certificado_sat" 
                                       value="{{ old('certificado_sat') }}" 
                                       required>
                                @error('certificado_sat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificado_cfdi" class="form-label">Certificado CFDI <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('certificado_cfdi') is-invalid @enderror" 
                                       id="certificado_cfdi" 
                                       name="certificado_cfdi" 
                                       value="{{ old('certificado_cfdi') }}" 
                                       required>
                                @error('certificado_cfdi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_certificado_sat" class="form-label">No. Certificado SAT <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('no_certificado_sat') is-invalid @enderror" 
                                       id="no_certificado_sat" 
                                       name="no_certificado_sat" 
                                       value="{{ old('no_certificado_sat') }}" 
                                       maxlength="20"
                                       required>
                                @error('no_certificado_sat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_certificado_cfdi" class="form-label">No. Certificado CFDI <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('no_certificado_cfdi') is-invalid @enderror" 
                                       id="no_certificado_cfdi" 
                                       name="no_certificado_cfdi" 
                                       value="{{ old('no_certificado_cfdi') }}" 
                                       maxlength="20"
                                       required>
                                @error('no_certificado_cfdi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cadena_original" class="form-label">Cadena Original <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('cadena_original') is-invalid @enderror" 
                                          id="cadena_original" 
                                          name="cadena_original" 
                                          rows="3"
                                          required>{{ old('cadena_original') }}</textarea>
                                @error('cadena_original')
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
                                          rows="3">{{ old('observaciones') }}</textarea>
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
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Registro Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="estado" value="vigente">
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('cfdi.index') }}" class="btn btn-secondary">
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

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Inicializar datetimepicker
    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        locale: "es",
        time_24hr: true
    });
    
    // Calcular total automáticamente
    function calcularTotal() {
        var subtotal = parseFloat($('#subtotal').val()) || 0;
        var iva = parseFloat($('#iva').val()) || 0;
        var total = subtotal + iva;
        $('#total').val(total.toFixed(2));
    }
    
    $('#subtotal, #iva').on('input', calcularTotal);
    
    // Leer archivo XML
    $('#xml_file').change(function() {
        var file = this.files[0];
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('#xml_content').val(e.target.result);
            
            // Parsear XML para extraer información
            var parser = new DOMParser();
            var xmlDoc = parser.parseFromString(e.target.result, "text/xml");
            
            // Extraer UUID
            var uuid = xmlDoc.querySelector('UUID');
            if (uuid) {
                $('#uuid').val(uuid.textContent);
            }
            
            // Extraer RFCs
            var emisor = xmlDoc.querySelector('Emisor rfc');
            if (emisor) {
                $('#rfc_emisor').val(emisor.textContent);
            }
            
            var receptor = xmlDoc.querySelector('Receptor rfc');
            if (receptor) {
                $('#rfc_receptor').val(receptor.textContent);
            }
            
            // Extraer fechas
            var fecha = xmlDoc.querySelector('Fecha');
            if (fecha) {
                $('#fecha_emision').val(fecha.textContent.replace('T', ' '));
            }
            
            // Extraer montos
            var subtotal = xmlDoc.querySelector('SubTotal');
            if (subtotal) {
                $('#subtotal').val(parseFloat(subtotal.textContent));
            }
            
            var total = xmlDoc.querySelector('Total');
            if (total) {
                $('#total').val(parseFloat(total.textContent));
            }
            
            // Extraer sellos
            var sello = xmlDoc.querySelector('Sello');
            if (sello) {
                $('#sello_cfdi').val(sello.textContent);
            }
            
            var certificado = xmlDoc.querySelector('Certificado');
            if (certificado) {
                $('#certificado_cfdi').val(certificado.textContent);
                $('#no_certificado_cfdi').val(certificado.textContent.substring(0, 20));
            }
            
            calcularTotal();
        };
        
        reader.readAsText(file);
    });
    
    // Validar formato UUID
    $('#uuid').on('input', function() {
        var uuid = $(this).val();
        var uuidPattern = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
        
        if (uuid && !uuidPattern.test(uuid)) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('El UUID no tiene un formato válido');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Validar RFCs
    $('#rfc_emisor, #rfc_receptor').on('input', function() {
        var rfc = $(this).val().toUpperCase();
        $(this).val(rfc);
        
        var rfcPattern = /^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
        if (rfc && rfc.length >= 12 && !rfcPattern.test(rfc)) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endpush