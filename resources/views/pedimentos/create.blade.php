@extends('layouts.app')

@section('title', 'Nuevo Pedimento')
@section('header', 'Registrar Nuevo Pedimento')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #006847 0%, #004E98 100%);">
                <h5 class="card-title mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Información del Pedimento
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
                
                <form method="POST" action="{{ route('pedimentos.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contribuyente_id" class="form-label">Contribuyente *</label>
                            <select class="form-select select2" id="contribuyente_id" name="contribuyente_id" required>
                                <option value="">Seleccione...</option>
                                @forelse($contribuyentes as $contribuyente)
                                    <option value="{{ $contribuyente['id'] ?? $contribuyente['id'] ?? '' }}">
                                        {{ $contribuyente['razon_social'] ?? $contribuyente['nombre'] ?? '' }}
                                    </option>
                                @empty
                                    <option value="">No hay contribuyentes</option>
                                @endforelse
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="numero_pedimento" class="form-label">Número de Pedimento *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="numero_pedimento" name="numero_pedimento" 
                                       value="{{ old('numero_pedimento') }}" required placeholder="1901 0001 00001">
                                <button type="button" class="btn btn-outline-secondary" onclick="generarPedimento()" title="Generar">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <small class="text-muted">Formato: AA (año) MM 0001 00001</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="numero_importacion" class="form-label">Número Importación *</label>
                            <input type="text" class="form-control" id="numero_importacion" name="numero_importacion" 
                                   value="{{ old('numero_importacion') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_operacion" class="form-label">Tipo de Operación *</label>
                            <select class="form-select" id="tipo_operacion" name="tipo_operacion" required>
                                <option value="">Seleccione...</option>
                                <option value="IMPORTACION" {{ old('tipo_operacion') == 'IMPORTACION' ? 'selected' : '' }}>Importación</option>
                                <option value="EXPORTACION" {{ old('tipo_operacion') == 'EXPORTACION' ? 'selected' : '' }}>Exportación</option>
                                <option value="TRANSITO" {{ old('tipo_operacion') == 'TRANSITO' ? 'selected' : '' }}>Tránsito</option>
                                <option value="RETROCEDE" {{ old('tipo_operacion') == 'RETROCEDE' ? 'selected' : '' }}>Retrocede</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="ACTIVO" {{ old('estado', 'ACTIVO') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                                <option value="UTILIZADO" {{ old('estado') == 'UTILIZADO' ? 'selected' : '' }}>Utilizado</option>
                                <option value="CANCELADO" {{ old('estado') == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                                <option value="VENCIDO" {{ old('estado') == 'VENCIDO' ? 'selected' : '' }}>Vencido</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-calendar me-2"></i>Fechas y Volúmenes
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio *</label>
                            <input type="date" class="form-control datepicker" id="fecha_inicio" name="fecha_inicio" 
                                   value="{{ old('fecha_inicio') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha Fin *</label>
                            <input type="date" class="form-control datepicker" id="fecha_fin" name="fecha_fin" 
                                   value="{{ old('fecha_fin') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="volumen_importado" class="form-label">Volumen Importado (L)</label>
                            <input type="number" step="0.001" min="0" class="form-control" id="volumen_importado" name="volumen_importado" 
                                   value="{{ old('volumen_importado') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pais_origen" class="form-label">País Origen *</label>
                            <select class="form-select select2" id="pais_origen" name="pais_origen" required>
                                <option value="">Seleccione...</option>
                                <option value="MEXICO" selected>MÉXICO</option>
                                <option value="USA">Estados Unidos</option>
                                <option value="CANADA">Canadá</option>
                                <option value="CHINA">China</option>
                                <option value="JAPON">Japón</option>
                                <option value="ALEMANIA">Alemania</option>
                                <option value="REINO UNIDO">Reino Unido</option>
                                <option value="BRASIL">Brasil</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="pais_destino" class="form-label">País Destino</label>
                            <select class="form-select select2" id="pais_destino" name="pais_destino">
                                <option value="MEXICO" selected>MÉXICO</option>
                                <option value="USA">Estados Unidos</option>
                                <option value="CANADA">Canadá</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="producto_id" class="form-label">Producto *</label>
                            <select class="form-select select2" id="producto_id" name="producto_id" required>
                                <option value="">Seleccione...</option>
                                @forelse($productos as $producto)
                                    <option value="{{ $producto['id'] ?? '' }}">
                                        {{ $producto['nombre'] ?? ($producto['clave_sat'] ?? '') ?? '' }}
                                    </option>
                                @empty
                                    <option value="">No hay productos</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="bi bi-currency-dollar me-2"></i>Datos Financieros
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="valor_aduana" class="form-label">Valor Aduana</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="valor_aduana" name="valor_aduana" 
                                       value="{{ old('valor_aduana') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="impuestos" class="form-label">Impuestos</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="impuestos" name="impuestos" 
                                       value="{{ old('impuestos') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="flete" class="form-label">Flete</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="flete" name="flete" 
                                       value="{{ old('flete') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pedimentos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Pedimento
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
function generarPedimento() {
    const fecha = new Date();
    const año = String(fecha.getFullYear()).slice(-2);
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const numero = String(Math.floor(Math.random() * 99999) + 1).padStart(5, '0');
    document.getElementById('numero_pedimento').value = `${año}${mes} 0001 ${numero}`;
}

$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
    
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Generar pedimento automáticamente al cargar
    generarPedimento();
    
    // Establecer fechas por defecto
    const hoy = new Date();
    const fechaInicio = new Date();
    fechaInicio.setFullYear(fechaInicio.getFullYear() - 1);
    
    const fechaFin = new Date();
    fechaFin.setFullYear(fechaFin.getFullYear() + 1);
    
    document.getElementById('fecha_inicio').value = fechaInicio.toISOString().split('T')[0];
    document.getElementById('fecha_fin').value = fechaFin.toISOString().split('T')[0];
});
</script>
@endpush