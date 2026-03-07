@extends('layouts.app')

@section('title', 'Nuevo Pedimento')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Pedimento</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pedimentos.store') }}" id="pedimentoForm">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del Pedimento</h5>
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
                                <label for="producto_id" class="form-label">Producto <span class="text-danger">*</span></label>
                                <select class="form-select select2 @error('producto_id') is-invalid @enderror" 
                                        id="producto_id" 
                                        name="producto_id" 
                                        required>
                                    <option value="">Seleccione un producto...</option>
                                    @foreach($productos['data'] ?? [] as $producto)
                                        <option value="{{ $producto['id'] }}" {{ old('producto_id') == $producto['id'] ? 'selected' : '' }}>
                                            {{ $producto['clave_producto'] }} - {{ $producto['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero_pedimento" class="form-label">Número de Pedimento <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('numero_pedimento') is-invalid @enderror" 
                                       id="numero_pedimento" 
                                       name="numero_pedimento" 
                                       value="{{ old('numero_pedimento') }}" 
                                       maxlength="21"
                                       placeholder="XX XX XXXX XXXXXXX"
                                       required>
                                @error('numero_pedimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="aduana" class="form-label">Aduana <span class="text-danger">*</span></label>
                                <select class="form-select @error('aduana') is-invalid @enderror" 
                                        id="aduana" 
                                        name="aduana" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="201" {{ old('aduana') == '201' ? 'selected' : '' }}>201 - Aguascalientes</option>
                                    <option value="202" {{ old('aduana') == '202' ? 'selected' : '' }}>202 - Chihuahua</option>
                                    <option value="203" {{ old('aduana') == '203' ? 'selected' : '' }}>203 - Ciudad Juárez</option>
                                    <option value="204" {{ old('aduana') == '204' ? 'selected' : '' }}>204 - Nuevo Laredo</option>
                                    <option value="205" {{ old('aduana') == '205' ? 'selected' : '' }}>205 - Tijuana</option>
                                    <option value="206" {{ old('aduana') == '206' ? 'selected' : '' }}>206 - Veracruz</option>
                                    <option value="207" {{ old('aduana') == '207' ? 'selected' : '' }}>207 - Manzanillo</option>
                                </select>
                                @error('aduana')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="patente" class="form-label">Patente <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('patente') is-invalid @enderror" 
                                       id="patente" 
                                       name="patente" 
                                       value="{{ old('patente') }}" 
                                       maxlength="4"
                                       required>
                                @error('patente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ejercicio" class="form-label">Ejercicio <span class="text-danger">*</span></label>
                                <select class="form-select @error('ejercicio') is-invalid @enderror" 
                                        id="ejercicio" 
                                        name="ejercicio" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ old('ejercicio') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('ejercicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_importacion" class="form-label">Fecha Importación <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control datepicker @error('fecha_importacion') is-invalid @enderror" 
                                       id="fecha_importacion" 
                                       name="fecha_importacion" 
                                       value="{{ old('fecha_importacion') }}" 
                                       required>
                                @error('fecha_importacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Datos de Importación -->
                    <h5 class="mb-3 mt-4">Datos de Importación</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo_cambio" class="form-label">Tipo de Cambio <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('tipo_cambio') is-invalid @enderror" 
                                       id="tipo_cambio" 
                                       name="tipo_cambio" 
                                       value="{{ old('tipo_cambio') }}" 
                                       min="0" 
                                       step="0.0001"
                                       required>
                                @error('tipo_cambio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="peso_bruto" class="form-label">Peso Bruto (kg) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('peso_bruto') is-invalid @enderror" 
                                       id="peso_bruto" 
                                       name="peso_bruto" 
                                       value="{{ old('peso_bruto') }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('peso_bruto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="peso_neto" class="form-label">Peso Neto (kg) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('peso_neto') is-invalid @enderror" 
                                       id="peso_neto" 
                                       name="peso_neto" 
                                       value="{{ old('peso_neto') }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('peso_neto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="volumen" class="form-label">Volumen (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('volumen') is-invalid @enderror" 
                                       id="volumen" 
                                       name="volumen" 
                                       value="{{ old('volumen') }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('volumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cantidades -->
                    <h5 class="mb-3 mt-4">Cantidades</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cantidad_importada" class="form-label">Cantidad Importada (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('cantidad_importada') is-invalid @enderror" 
                                       id="cantidad_importada" 
                                       name="cantidad_importada" 
                                       value="{{ old('cantidad_importada') }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('cantidad_importada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cantidad_despachada" class="form-label">Cantidad Despachada (L) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('cantidad_despachada') is-invalid @enderror" 
                                       id="cantidad_despachada" 
                                       name="cantidad_despachada" 
                                       value="{{ old('cantidad_despachada') }}" 
                                       min="0" 
                                       step="0.001"
                                       required>
                                @error('cantidad_despachada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cantidad_pendiente" class="form-label">Cantidad Pendiente (L)</label>
                                <input type="number" 
                                       class="form-control @error('cantidad_pendiente') is-invalid @enderror" 
                                       id="cantidad_pendiente" 
                                       name="cantidad_pendiente" 
                                       value="{{ old('cantidad_pendiente') }}" 
                                       min="0" 
                                       step="0.001"
                                       readonly>
                                @error('cantidad_pendiente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Observaciones y Estado -->
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
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" 
                                        name="estado" 
                                        required>
                                    <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="liquidado" {{ old('estado') == 'liquidado' ? 'selected' : '' }}>Liquidado</option>
                                    <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
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
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('pedimentos.index') }}" class="btn btn-secondary">
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
    
    // Formato automático para número de pedimento
    $('#numero_pedimento').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0,2) + ' ' + value.substring(2);
        }
        if (value.length >= 5) {
            value = value.substring(0,5) + ' ' + value.substring(5);
        }
        if (value.length >= 8) {
            value = value.substring(0,8) + ' ' + value.substring(8);
        }
        $(this).val(value);
    });
    
    // Calcular cantidad pendiente
    function calcularPendiente() {
        var importada = parseFloat($('#cantidad_importada').val()) || 0;
        var despachada = parseFloat($('#cantidad_despachada').val()) || 0;
        var pendiente = importada - despachada;
        
        if (pendiente < 0) pendiente = 0;
        $('#cantidad_pendiente').val(pendiente.toFixed(3));
    }
    
    $('#cantidad_importada, #cantidad_despachada').on('input', calcularPendiente);
    
    // Validar que despachada no sea mayor a importada
    $('#cantidad_despachada').on('input', function() {
        var importada = parseFloat($('#cantidad_importada').val()) || 0;
        var despachada = parseFloat($(this).val()) || 0;
        
        if (despachada > importada) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('La cantidad despachada no puede ser mayor a la importada');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endpush