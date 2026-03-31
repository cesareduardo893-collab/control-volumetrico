@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('header', 'Registrar Nuevo Producto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #F7C331 0%, #FF6B35 100%);">
                <h5 class="card-title mb-0"><i class="bi bi-droplet-fill me-2"></i>Información del Combustible</h5>
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
                
                <form method="POST" action="{{ route('productos.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clave_sat" class="form-label">Clave SAT *</label>
                            <input type="text" class="form-control" id="clave_sat" name="clave_sat" 
                                   value="{{ old('clave_sat') }}" maxlength="10" required>
                            <small class="text-muted">Clave del producto según el SAT (10 dígitos)</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código Interno *</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" 
                                   value="{{ old('codigo') }}" maxlength="20" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clave_identificacion" class="form-label">Clave de Identificación *</label>
                            <input type="text" class="form-control" id="clave_identificacion" name="clave_identificacion" 
                                   value="{{ old('clave_identificacion') }}" maxlength="10" required>
                            <small class="text-muted">Clave para identificación en el sistema</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="3">{{ old('descripcion') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="unidad_medida" class="form-label">Unidad de Medida *</label>
                            <input type="text" class="form-control" id="unidad_medida" name="unidad_medida" 
                                   value="{{ old('unidad_medida', 'LITRO') }}" required>
                            <small class="text-muted">Ej: LITRO, KILOGRAMO, etc.</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_hidrocarburo" class="form-label">Tipo de Hidrocarburo *</label>
                            <select class="form-select" id="tipo_hidrocarburo" name="tipo_hidrocarburo" required>
                                <option value="">Seleccione...</option>
                                <option value="petroleo" {{ old('tipo_hidrocarburo') == 'petroleo' ? 'selected' : '' }}>Petróleo</option>
                                <option value="gas_natural" {{ old('tipo_hidrocarburo') == 'gas_natural' ? 'selected' : '' }}>Gas Natural</option>
                                <option value="condensados" {{ old('tipo_hidrocarburo') == 'condensados' ? 'selected' : '' }}>Condensados</option>
                                <option value="gasolina" {{ old('tipo_hidrocarburo') == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                <option value="diesel" {{ old('tipo_hidrocarburo') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="turbosina" {{ old('tipo_hidrocarburo') == 'turbosina' ? 'selected' : '' }}>Turbosina</option>
                                <option value="gas_lp" {{ old('tipo_hidrocarburo') == 'gas_lp' ? 'selected' : '' }}>Gas LP</option>
                                <option value="propano" {{ old('tipo_hidrocarburo') == 'propano' ? 'selected' : '' }}>Propano</option>
                                <option value="otro" {{ old('tipo_hidrocarburo') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="densidad_referencia" class="form-label">Densidad de Referencia</label>
                            <div class="input-group">
                                <input type="number" step="0.0001" min="0" class="form-control" 
                                       id="densidad_referencia" name="densidad_referencia" value="{{ old('densidad_referencia') }}">
                                <span class="input-group-text">kg/L</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="temperatura_referencia" class="form-label">Temperatura de Referencia</label>
                            <div class="input-group">
                                <input type="number" step="0.1" class="form-control" 
                                       id="temperatura_referencia" name="temperatura_referencia" value="{{ old('temperatura_referencia', 15) }}">
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="factor_conversion" class="form-label">Factor de Conversión</label>
                            <input type="number" step="0.001" min="0" class="form-control" 
                                   id="factor_conversion" name="factor_conversion" value="{{ old('factor_conversion', 1) }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="octanaje" class="form-label">Octanaje (para gasolinas)</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="octanaje" name="octanaje" value="{{ old('octanaje') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="numero_octano" class="form-label">Número de Octano</label>
                            <input type="number" step="0.1" class="form-control" 
                                   id="numero_octano" name="numero_octano" value="{{ old('numero_octano') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Producto Activo</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Producto
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
    // Valores por defecto para cada tipo de hidrocarburo
    const defaultValues = {
        'gasolina': {
            clave_sat: '15101507',
            nombre: 'Gasolina Magna',
            descripcion: 'Gasolina de 87 octanos',
            unidad_medida: 'LITRO',
            densidad_referencia: 0.7500,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: 87,
            numero_octano: 87
        },
        'diesel': {
            clave_sat: '15101505',
            nombre: 'Diésel',
            descripcion: 'Combustible diésel ultrabajo en azufre',
            unidad_medida: 'LITRO',
            densidad_referencia: 0.8500,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: null,
            numero_octano: null
        },
        'gas_lp': {
            clave_sat: '15111500',
            nombre: 'Gas LP',
            descripcion: 'Gas licuado de petróleo',
            unidad_medida: 'LITRO',
            densidad_referencia: 0.5500,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: null,
            numero_octano: null
        },
        'gas_natural': {
            clave_sat: '15111501',
            nombre: 'Gas Natural',
            descripcion: 'Gas natural comprimido',
            unidad_medida: 'METRO CUBICO',
            densidad_referencia: 0.7170,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: null,
            numero_octano: null
        },
        'turbosina': {
            clave_sat: '15101509',
            nombre: 'Turbosina',
            descripcion: 'Combustible para aviación Jet-A1',
            unidad_medida: 'LITRO',
            densidad_referencia: 0.8100,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: null,
            numero_octano: null
        },
        'propano': {
            clave_sat: '15111502',
            nombre: 'Propano',
            descripcion: 'Gas propano',
            unidad_medida: 'LITRO',
            densidad_referencia: 0.5100,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: null,
            numero_octano: null
        },
        'petroleo': {
            clave_sat: '15101501',
            nombre: 'Petróleo Crudo',
            descripcion: 'Petróleo crudo',
            unidad_medida: 'BARRIL',
            densidad_referencia: 0.8500,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: null,
            numero_octano: null
        },
        'condensados': {
            clave_sat: '15101502',
            nombre: 'Condensados',
            descripcion: 'Hidrocarburos condensados',
            unidad_medida: 'BARRIL',
            densidad_referencia: 0.7200,
            temperatura_referencia: 15,
            factor_conversion: 1,
            octanaje: null,
            numero_octano: null
        }
    };

    // Función para auto-completar campos
    $('#tipo_hidrocarburo').on('change', function() {
        const tipoSeleccionado = $(this).val();
        
        if (tipoSeleccionado && defaultValues[tipoSeleccionado]) {
            const defaults = defaultValues[tipoSeleccionado];
            
            // Auto-completar solo campos técnicos (NO los campos de identificación)
            if (defaults.unidad_medida) $('#unidad_medida').val(defaults.unidad_medida);
            if (defaults.densidad_referencia) $('#densidad_referencia').val(defaults.densidad_referencia);
            if (defaults.temperatura_referencia) $('#temperatura_referencia').val(defaults.temperatura_referencia);
            if (defaults.factor_conversion) $('#factor_conversion').val(defaults.factor_conversion);
            
            // Manejar octanaje (solo para gasolina)
            if (defaults.octanaje !== null) {
                $('#octanaje').val(defaults.octanaje);
                $('#numero_octano').val(defaults.numero_octano);
            } else {
                $('#octanaje').val('');
                $('#numero_octano').val('');
            }
            
            // Mostrar notificación
            showToast('Campos técnicos auto-completados para ' + defaults.nombre, 'info');
        }
    });
    
    // Función para mostrar notificaciones
    function showToast(message, type) {
        const toast = $('<div>')
            .addClass('alert alert-' + type + ' position-fixed top-0 end-0 m-3')
            .css('z-index', '9999')
            .html('<i class="bi bi-check-circle me-2"></i>' + message)
            .appendTo('body');
        
        setTimeout(function() {
            toast.fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>
@endpush
