<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Gestión') - Sistema de Control Volumétrico</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
    
    @stack('styles')
</head>
<body>
    @include('layouts.navbar')
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        @can('ver_operaciones_cotidianas')
                        <li class="nav-item">
                            <a class="nav-link" href="#operacionesSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="bi bi-calendar-check"></i> Operaciones Cotidianas
                            </a>
                            <ul class="collapse nav flex-column ms-3" id="operacionesSubmenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('registros-volumetricos.*') ? 'active' : '' }}" href="{{ route('registros-volumetricos.index') }}">
                                        <i class="bi bi-bar-chart"></i> Registros Volumétricos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('existencias.*') ? 'active' : '' }}" href="{{ route('existencias.index') }}">
                                        <i class="bi bi-box"></i> Existencias
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('alarmas.*') ? 'active' : '' }}" href="{{ route('alarmas.index') }}">
                                        <i class="bi bi-exclamation-triangle"></i> Alarmas
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        
                        @can('ver_instalaciones')
                        <li class="nav-item">
                            <a class="nav-link" href="#instalacionesSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="bi bi-building"></i> Instalaciones
                            </a>
                            <ul class="collapse nav flex-column ms-3" id="instalacionesSubmenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('instalaciones.*') ? 'active' : '' }}" href="{{ route('instalaciones.index') }}">
                                        <i class="bi bi-geo-alt"></i> Instalaciones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('tanques.*') ? 'active' : '' }}" href="{{ route('tanques.index') }}">
                                        <i class="bi bi-barrel"></i> Tanques
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('medidores.*') ? 'active' : '' }}" href="{{ route('medidores.index') }}">
                                        <i class="bi bi-speedometer"></i> Medidores
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dispensarios.*') ? 'active' : '' }}" href="{{ route('dispensarios.index') }}">
                                        <i class="bi bi-fuel-pump"></i> Dispensarios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('mangueras.*') ? 'active' : '' }}" href="{{ route('mangueras.index') }}">
                                        <i class="bi bi-pip"></i> Mangueras
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        
                        @can('ver_catalogos')
                        <li class="nav-item">
                            <a class="nav-link" href="#catalogosSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="bi bi-book"></i> Catálogos
                            </a>
                            <ul class="collapse nav flex-column ms-3" id="catalogosSubmenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('contribuyentes.*') ? 'active' : '' }}" href="{{ route('contribuyentes.index') }}">
                                        <i class="bi bi-people"></i> Contribuyentes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                                        <i class="bi bi-cup"></i> Productos
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        
                        @can('ver_calidad')
                        <li class="nav-item">
                            <a class="nav-link" href="#calidadSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="bi bi-clipboard-check"></i> Calidad
                            </a>
                            <ul class="collapse nav flex-column ms-3" id="calidadSubmenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dictamenes.*') ? 'active' : '' }}" href="{{ route('dictamenes.index') }}">
                                        <i class="bi bi-file-text"></i> Dictámenes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('certificados-verificacion.*') ? 'active' : '' }}" href="{{ route('certificados-verificacion.index') }}">
                                        <i class="bi bi-certificate"></i> Certificados de Verificación
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        
                        @can('ver_fiscal')
                        <li class="nav-item">
                            <a class="nav-link" href="#fiscalSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="bi bi-calculator"></i> Fiscal
                            </a>
                            <ul class="collapse nav flex-column ms-3" id="fiscalSubmenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('cfdi.*') ? 'active' : '' }}" href="{{ route('cfdi.index') }}">
                                        <i class="bi bi-receipt"></i> CFDI
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reportes-sat.*') ? 'active' : '' }}" href="{{ route('reportes-sat.index') }}">
                                        <i class="bi bi-envelope-paper"></i> Reportes SAT
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        
                        @can('ver_comercio_exterior')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pedimentos.*') ? 'active' : '' }}" href="{{ route('pedimentos.index') }}">
                                <i class="bi bi-truck"></i> Pedimentos
                            </a>
                        </li>
                        @endcan
                        
                        @can('ver_administracion')
                        <li class="nav-item">
                            <a class="nav-link" href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="bi bi-gear"></i> Administración
                            </a>
                            <ul class="collapse nav flex-column ms-3" id="adminSubmenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="bi bi-person-badge"></i> Usuarios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                                        <i class="bi bi-shield"></i> Roles
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                                        <i class="bi bi-key"></i> Permisos
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        
                        @can('ver_bitacora')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bitacora.*') ? 'active' : '' }}" href="{{ route('bitacora.index') }}">
                                <i class="bi bi-journal-text"></i> Bitácora
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </nav>
            
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('header', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('actions')
                    </div>
                </div>
                
                <!-- Alertas -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.es.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Configuración global
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Inicializar popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
    
    @stack('scripts')
</body>
</html>