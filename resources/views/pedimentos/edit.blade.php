@extends('layouts.app')

@section('title', 'Editar Pedimento')
@section('header', 'Editar Pedimento')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Pedimento</h5>
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
                
                <form method="POST" action="{{ route('pedimentos.update', $pedimento['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_pedimento" class="form-label">Número de Pedimento</label>
                            <input type="text" class="form-control" id="numero_pedimento" 
                                   value="{{ $pedimento['numero_pedimento'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_pedimento" class="form-label">Fecha del Pedimento</label>
                            <input type="date" class="form-control" id="fecha_pedimento" 
                                   value="{{ $pedimento['fecha_pedimento'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contribuyente_id" class="form-label">Contribuyente</label>
                            <input type="text" class="form-control" 
                                   value="{{ $pedimento['contribuyente']['razon_social'] ?? $pedimento['contribuyente_id'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="producto_id" class="form-label">Producto</label>
                            <input type="text" class="form-control" 
                                   value="{{ $pedimento['producto']['nombre'] ?? $pedimento['producto_id'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pais_origen" class="form-label">País Origen</label>
                            <input type="text" class="form-control" value="{{ $pedimento['pais_origen'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="pais_destino" class="form-label">País Destino</label>
                            <input type="text" class="form-control" value="{{ $pedimento['pais_destino'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="volumen" class="form-label">Volumen</label>
                            <input type="text" class="form-control" 
                                   value="{{ number_format($pedimento['volumen'], 3) }} {{ $pedimento['unidad_medida'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_arribo" class="form-label">Fecha de Arribo</label>
                            <input type="date" class="form-control datepicker" id="fecha_arribo" 
                                   name="fecha_arribo" value="{{ old('fecha_arribo', $pedimento['fecha_arribo'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                            <input type="date" class="form-control datepicker" id="fecha_pago" 
                                   name="fecha_pago" value="{{ old('fecha_pago', $pedimento['fecha_pago'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="registro_volumetrico_id" class="form-label">Registro Volumétrico</label>
                            <input type="number" class="form-control" id="registro_volumetrico_id" 
                                   name="registro_volumetrico_id" value="{{ old('registro_volumetrico_id', $pedimento['registro_volumetrico_id'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="ACTIVO" {{ old('estado', $pedimento['estado']) == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                                <option value="UTILIZADO" {{ old('estado', $pedimento['estado']) == 'UTILIZADO' ? 'selected' : '' }}>Utilizado</option>
                                <option value="CANCELADO" {{ old('estado', $pedimento['estado']) == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones', $pedimento['observaciones'] ?? '') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pedimentos.show', $pedimento['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Pedimento
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
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
});
</script>
@endpush