<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Control Volumétrico</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --navbar-height: 60px;
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 0.9rem;
            background-color: #f5f6fa;
        }
        
        /* Navbar */
        .navbar {
            height: var(--navbar-height);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            overflow-y: auto;
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
            width: var(--sidebar-width);
            transition: transform 0.3s ease;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        
        .sidebar .nav-link {
            color: #374151;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
        }
        
        .sidebar .nav-link.active {
            background-color: #e8f0fe;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--navbar-height));
        }
        
        /* Page header */
        .page-header {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }
        
        /* Buttons */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
        }
        
        /* Tables */
        .table {
            font-size: 0.875rem;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 2px solid #e5e7eb;
        }
        
        /* Forms */
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #d1d5db;
            padding: 0.5rem 0.75rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        
        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 4px;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 8px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Utility */
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('layouts.navbar')
    
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="py-3">
            <ul class="nav flex-column">
                @php
                    $userRoles = session('user_roles', []);
                    $hasAccess = !empty($userRoles);
                    $hasAdminAccess = !empty($userRoles) && (in_array('admin', array_map('strtolower', $userRoles)) || in_array('administrador', array_map('strtolower', $userRoles)) || in_array('supervisor', array_map('strtolower', $userRoles)));
                @endphp
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                
                @if($hasAccess)
                <!-- Operaciones -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#operacionesMenu" role="button">
                        <i class="bi bi-collection-play"></i> Operaciones
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="operacionesMenu">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('registros-volumetricos.*') ? 'active' : '' }}" href="{{ route('registros-volumetricos.index') }}">
                                    <i class="bi bi-graph-up"></i> Registros Volumétricos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('existencias.*') ? 'active' : '' }}" href="{{ route('existencias.index') }}">
                                    <i class="bi bi-box-seam"></i> Existencias
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('alarmas.*') ? 'active' : '' }}" href="{{ route('alarmas.index') }}">
                                    <i class="bi bi-exclamation-triangle"></i> Alarmas
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <!-- Instalaciones -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#instalacionesMenu" role="button">
                        <i class="bi bi-building"></i> Instalaciones
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="instalacionesMenu">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('contribuyentes.*') ? 'active' : '' }}" href="{{ route('contribuyentes.index') }}">
                                    <i class="bi bi-people"></i> Contribuyentes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('instalaciones.*') ? 'active' : '' }}" href="{{ route('instalaciones.index') }}">
                                    <i class="bi bi-geo-alt"></i> Instalaciones
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tanques.*') ? 'active' : '' }}" href="{{ route('tanques.index') }}">
                                    <i class="bi bi-droplet"></i> Tanques
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('medidores.*') ? 'active' : '' }}" href="{{ route('medidores.index') }}">
                                    <i class="bi bi-speedometer2"></i> Medidores
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
                    </div>
                </li>
                
                <!-- Catálogos -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#catalogosMenu" role="button">
                        <i class="bi bi-book"></i> Catálogos
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="catalogosMenu">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                                    <i class="bi bi-cup-straw"></i> Productos
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <!-- Calidad -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#calidadMenu" role="button">
                        <i class="bi bi-clipboard-check"></i> Calidad
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="calidadMenu">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dictamenes.*') ? 'active' : '' }}" href="{{ route('dictamenes.index') }}">
                                    <i class="bi bi-file-text"></i> Dictámenes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('certificados-verificacion.*') ? 'active' : '' }}" href="{{ route('certificados-verificacion.index') }}">
                                    <i class="bi bi-patch-check"></i> Certificados
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <!-- Fiscal -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#fiscalMenu" role="button">
                        <i class="bi bi-calculator"></i> Fiscal
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="fiscalMenu">
                        <ul class="nav flex-column ps-3">
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
                    </div>
                </li>
                
                <!-- Comercio Exterior -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pedimentos.*') ? 'active' : '' }}" href="{{ route('pedimentos.index') }}">
                        <i class="bi bi-truck"></i> Comercio Exterior
                    </a>
                </li>
                @endif
                
                @if($hasAdminAccess)
                <!-- Administración -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#adminMenu" role="button">
                        <i class="bi bi-gear"></i> Administración
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="adminMenu">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="bi bi-people"></i> Usuarios
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
                    </div>
                </li>
                @endif
                
                <!-- Bitácora -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bitacora.*') ? 'active' : '' }}" href="{{ route('bitacora.index') }}">
                        <i class="bi bi-journal-text"></i> Bitácora
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h3 mb-0">@yield('header', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="mt-1">
                        <ol class="breadcrumb mb-0">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif
            </div>
            <div class="btn-toolbar">
                @yield('actions')
            </div>
        </div>
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <!-- Content -->
        <div class="fade-in">
            @yield('content')
        </div>
    </main>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.es.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const navbarToggler = document.querySelector('.navbar-toggler');
            
            if (navbarToggler) {
                navbarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>