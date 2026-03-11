@extends('layouts.app')

@section('title', 'Productos por Tipo')
@section('header', 'Productos Agrupados por Tipo de Hidrocarburo')

@section('actions')
<a href="{{ route('productos.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-list"></i> Ver Todos
</a>
<a href="{{ route('productos.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Producto
</a>
@endsection

@section('content')
<div class="row">
    @php
        $tipos = [
            'petroleo' => 'Petróleo',
            'gas_natural' => 'Gas Natural',
            'condensados' => 'Condensados',
            'gasolina' => 'Gasolina',
            'diesel' => 'Diesel',
            'turbosina' => 'Turbosina',
            'gas_lp' => 'Gas LP',
            'propano' => 'Propano',
            'otro' => 'Otros'
        ];
    @endphp
    
    @foreach($tipos as $tipo => $nombreTipo)
        @php
            $productosTipo = collect($productos)->where('tipo_hidrocarburo', $tipo);
        @endphp
        
        @if($productosTipo->count() > 0)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            {{ $nombreTipo }}
                            <span class="badge bg-light text-dark float-end">{{ $productosTipo->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Clave SAT</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Unidad</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productosTipo as $producto)
                                        <tr>
                                            <td><strong>{{ $producto['clave_sat'] }}</strong></td>
                                            <td>{{ $producto['codigo'] }}</td>
                                            <td>{{ $producto['nombre'] }}</td>
                                            <td>{{ $producto['unidad_medida'] }}</td>
                                            <td>
                                                @if($producto['activo'])
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('productos.show', $producto['id']) }}" 
                                                       class="btn btn-sm btn-info" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('productos.edit', $producto['id']) }}" 
                                                       class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    
    @if(empty(array_filter($tipos, function($tipo) use ($productos) {
        return collect($productos)->where('tipo_hidrocarburo', $tipo)->count() > 0;
    })))
        <div class="col-12">
            <div class="alert alert-info">
                No hay productos registrados en ningún tipo de hidrocarburo.
            </div>
        </div>
    @endif
</div>
@endsection