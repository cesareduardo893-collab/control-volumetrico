@extends('layouts.app')

@section('title', 'Productos')
@section('header', 'Catálogo de Productos')

@section('actions')
@if(hasPermission('productos.manage'))
<a href="{{ route('productos.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Producto
</a>
@endif
<div class="btn-group">
    <a href="{{ route('productos.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('productos.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
@if(request()->filled('tipo_hidrocarburo'))
    <a href="{{ route('productos.por-tipo', request('tipo_hidrocarburo')) }}" class="btn btn-sm btn-info">
        <i class="bi bi-grid-3x3-gap-fill"></i> Ver por Tipo
    </a>
@else
    <button type="button" class="btn btn-sm btn-info" disabled title="Filtra por tipo para habilitar">
        <i class="bi bi-grid-3x3-gap-fill"></i> Ver por Tipo
    </button>
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('productos.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="clave_sat" class="form-label">Clave SAT</label>
                <input type="text" class="form-control" id="clave_sat" name="clave_sat" 
                       value="{{ request('clave_sat') }}" placeholder="Ej: 15101500">
            </div>
            
            <div class="col-md-2">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" 
                       value="{{ request('codigo') }}" placeholder="Código interno">
            </div>
            
            <div class="col-md-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                       value="{{ request('nombre') }}" placeholder="Buscar por nombre">
            </div>
            
            <div class="col-md-2">
                <label for="tipo_hidrocarburo" class="form-label">Tipo Hidrocarburo</label>
                <select class="form-select" id="tipo_hidrocarburo" name="tipo_hidrocarburo">
                    <option value="">Todos</option>
                    <option value="petroleo" {{ request('tipo_hidrocarburo') == 'petroleo' ? 'selected' : '' }}>Petróleo</option>
                    <option value="gas_natural" {{ request('tipo_hidrocarburo') == 'gas_natural' ? 'selected' : '' }}>Gas Natural</option>
                    <option value="condensados" {{ request('tipo_hidrocarburo') == 'condensados' ? 'selected' : '' }}>Condensados</option>
                    <option value="gasolina" {{ request('tipo_hidrocarburo') == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                    <option value="diesel" {{ request('tipo_hidrocarburo') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                    <option value="turbosina" {{ request('tipo_hidrocarburo') == 'turbosina' ? 'selected' : '' }}>Turbosina</option>
                    <option value="gas_lp" {{ request('tipo_hidrocarburo') == 'gas_lp' ? 'selected' : '' }}>Gas LP</option>
                    <option value="propano" {{ request('tipo_hidrocarburo') == 'propano' ? 'selected' : '' }}>Propano</option>
                    <option value="otro" {{ request('tipo_hidrocarburo') == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="activo" class="form-label">Activo</label>
                <select class="form-select" id="activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de productos -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Clave SAT</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Clave Identificación</th>
                        <th>Unidad Medida</th>
                        <th>Tipo Hidrocarburo</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td><strong>{{ $producto['clave_sat'] }}</strong></td>
                            <td>{{ $producto['codigo'] }}</td>
                            <td>
                                {{ $producto['nombre'] }}
                                @if(!empty($producto['descripcion']))
                                    <br><small class="text-muted">{{ Str::limit($producto['descripcion'], 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $producto['clave_identificacion'] }}</td>
                            <td>{{ $producto['unidad_medida'] }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $producto['tipo_hidrocarburo'])) }}</span>
                            </td>
                            <td>
                                @if($producto['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('productos.show', $producto['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay productos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if(isset($meta))
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Mostrando {{ $meta['from'] ?? 0 }} - {{ $meta['to'] ?? 0 }} de {{ $meta['total'] ?? 0 }} registros
            </div>
            <nav>
                <ul class="pagination">
                    @if(isset($links['prev']))
                        <li class="page-item">
                            <a class="page-link" href="{{ $links['prev'] }}" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif
                    
                    @for($i = 1; $i <= ($meta['last_page'] ?? 1); $i++)
                        <li class="page-item {{ $i == ($meta['current_page'] ?? 1) ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    
                    @if(isset($links['next']))
                        <li class="page-item">
                            <a class="page-link" href="{{ $links['next'] }}" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection
