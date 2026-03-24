@extends('layouts.app')

@section('title', 'Roles')
@section('header', 'Administración de Roles')

@section('actions')
<a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Rol
</a>
<div class="btn-group">
    <a href="{{ route('roles.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('roles.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
<a href="{{ route('roles.matriz-permisos') }}" class="btn btn-sm btn-info">
    <i class="bi bi-grid-3x3-gap-fill"></i> Matriz de Permisos
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('roles.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre del Rol</label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                       value="{{ request('nombre') }}" placeholder="Buscar por nombre">
            </div>
            
            <div class="col-md-2">
                <label for="nivel_minimo" class="form-label">Nivel Mínimo</label>
                <input type="number" class="form-control" id="nivel_minimo" name="nivel_minimo" 
                       value="{{ request('nivel_minimo') }}" placeholder="Ej: 1" min="1" max="100">
            </div>
            
            <div class="col-md-2">
                <label for="es_administrador" class="form-label">Es Admin</label>
                <select class="form-select" id="es_administrador" name="es_administrador">
                    <option value="">Todos</option>
                    <option value="1" {{ request('es_administrador') == '1' ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ request('es_administrador') == '0' ? 'selected' : '' }}>No</option>
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
            
            <div class="col-md-2">
                <label for="per_page" class="form-label">Registros por página</label>
                <select class="form-select" id="per_page" name="per_page">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de roles -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Nivel Jerárquico</th>
                        <th>Permisos</th>
                        <th>Usuarios</th>
                        <th>Es Admin</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $rol)
                        <tr>
                            <td><strong>{{ $rol['nombre'] }}</strong></td>
                            <td>{{ $rol['descripcion'] ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $rol['nivel_jerarquico'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ count($rol['permissions'] ?? []) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $rol['usuarios_count'] ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                @if($rol['es_administrador'])
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($rol['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('roles.show', $rol['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('roles.edit', $rol['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="confirmarClonar({{ $rol['id'] }})" title="Clonar">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay roles registrados</td>
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

<!-- Modal de clonación -->
<div class="modal fade" id="clonarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Clonar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="clonarForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Nuevo Rol</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="incluir_permisos" 
                                   name="incluir_permisos" value="1" checked>
                            <label class="form-check-label" for="incluir_permisos">
                                Incluir permisos del rol original
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Clonar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmarClonar(id) {
    $('#clonarForm').attr('action', '{{ url('roles') }}' + '/' + id + '/clonar');
    new bootstrap.Modal(document.getElementById('clonarModal')).show();
}
</script>
@endpush