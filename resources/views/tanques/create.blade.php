@extends('layouts.app')

@section('title', 'Nuevo Tanque')
@section('header', 'Nuevo Tanque')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('tanques.store') }}" method="POST">
      @csrf
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="instalacion_id" class="form-label">Instalación</label>
          <select id="instalacion_id" name="instalacion_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach ($instalaciones as $instalacion)
              @php
                $idValue = is_array($instalacion) ? ($instalacion['id'] ?? $instalacion['ID'] ?? null) : (is_object($instalacion) ? ($instalacion->id ?? $instalacion->ID ?? null) : $instalacion);
                $displayName = is_array($instalacion) ? ($instalacion['nombre'] ?? $instalacion['name'] ?? '') : (is_object($instalacion) ? ($instalacion->nombre ?? $instalacion->name ?? '') : '');
              @endphp
              <option value="{{ $idValue }}" {{ (old('instalacion_id') == $idValue) ? 'selected' : '' }}>
                {{ $displayName ?: ($idValue ?? '') }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label for="identificador" class="form-label">Identificador</label>
          <input type="text" class="form-control" id="identificador" name="identificador" value="{{ old('identificador') }}" maxlength="255" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="material" class="form-label">Material</label>
          <input type="text" class="form-control" id="material" name="material" value="{{ old('material') }}" maxlength="100" required>
        </div>
        <div class="col-md-6">
          <label for="capacidad_total" class="form-label">Capacidad Total (L)</label>
          <input type="number" step="any" class="form-control" id="capacidad_total" name="capacidad_total" value="{{ old('capacidad_total') }}" min="0" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="capacidad_util" class="form-label">Capacidad Util (L)</label>
          <input type="number" step="any" class="form-control" id="capacidad_util" name="capacidad_util" value="{{ old('capacidad_util') }}" min="0" required>
        </div>
        <div class="col-md-6">
          <label for="capacidad_operativa" class="form-label">Capacidad Operativa (L)</label>
          <input type="number" step="any" class="form-control" id="capacidad_operativa" name="capacidad_operativa" value="{{ old('capacidad_operativa') }}" min="0" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="capacidad_minima" class="form-label">Capacidad Mínima (L)</label>
          <input type="number" step="any" class="form-control" id="capacidad_minima" name="capacidad_minima" value="{{ old('capacidad_minima') }}" min="0" required>
        </div>
        <div class="col-md-3">
          <label for="temperatura_referencia" class="form-label">Temperatura de Referencia (°C)</label>
          <input type="number" step="any" class="form-control" id="temperatura_referencia" name="temperatura_referencia" value="{{ old('temperatura_referencia') }}" required>
        </div>
        <div class="col-md-3">
          <label for="presion_referencia" class="form-label">Presión de Referencia (bar)</label>
          <input type="number" step="any" class="form-control" id="presion_referencia" name="presion_referencia" value="{{ old('presion_referencia') }}" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="tipo_medicion" class="form-label">Tipo de Medicion</label>
          <select id="tipo_medicion" name="tipo_medicion" class="form-select" required>
            <option value="estatica" {{ old('tipo_medicion') == 'estatica' ? 'selected' : '' }}>Estatica</option>
            <option value="dinamica" {{ old('tipo_medicion') == 'dinamica' ? 'selected' : '' }}>Dinámica</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="estado" class="form-label">Estado</label>
          <select id="estado" name="estado" class="form-select" required>
            <option value="OPERATIVO" {{ old('estado') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
            <option value="MANTENIMIENTO" {{ old('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
            <option value="FUERA_SERVICIO" {{ old('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
            <option value="CALIBRACION" {{ old('estado') == 'CALIBRACION' ? 'selected' : '' }}>Calibración</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <button type="submit" class="btn btn-primary">Crear Tanque</button>
        <a href="{{ route('tanques.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
