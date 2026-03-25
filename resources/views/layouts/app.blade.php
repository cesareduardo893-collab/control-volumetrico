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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed: 70px;
            --navbar-height: 65px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-soft: 0 8px 32px rgba(31, 38, 135, 0.15);
            --shadow-medium: 0 15px 35px rgba(31, 38, 135, 0.2);
            --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Navbar Moderno */
        .navbar {
            height: var(--navbar-height);
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: var(--shadow-soft);
            transition: var(--transition-smooth);
            z-index: 1000;
        }

        .navbar:hover {
            box-shadow: var(--shadow-medium);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: var(--transition-smooth);
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        /* Sidebar Moderno */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            bottom: 0;
            left: 0;
            z-index: 999;
            width: var(--sidebar-width);
            background: var(--dark-gradient);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition-smooth);
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-content {
            padding: 1.5rem 0;
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        /* Sidebar Navigation */
        .sidebar .nav-section {
            margin-bottom: 1.5rem;
        }

        .sidebar .nav-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
            transition: var(--transition-smooth);
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            transform: translateX(-10px);
        }

        .sidebar .nav-item {
            margin: 0.25rem 0.75rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1rem;
            font-weight: 500;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            opacity: 0;
            transition: var(--transition-smooth);
            border-radius: 12px;
        }

        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            opacity: 1;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            margin-right: 12px;
            position: relative;
            z-index: 1;
            transition: var(--transition-smooth);
        }

        .sidebar .nav-link span {
            position: relative;
            z-index: 1;
            transition: var(--transition-smooth);
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            transform: translateX(-10px);
        }

        .sidebar .nav-link .chevron {
            margin-left: auto;
            transition: var(--transition-smooth);
        }

        .sidebar.collapsed .nav-link .chevron {
            opacity: 0;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: calc(var(--navbar-height) + 1rem);
            left: calc(var(--sidebar-width) - 15px);
            z-index: 1001;
            width: 30px;
            height: 30px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .sidebar.collapsed ~ .sidebar-toggle {
            left: calc(var(--sidebar-collapsed) - 15px);
        }

        .sidebar-toggle i {
            transition: var(--transition-smooth);
        }

        .sidebar.collapsed ~ .sidebar-toggle i {
            transform: rotate(180deg);
        }

        /* Collapse Menu */
        .sidebar .collapse-menu {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin: 0.5rem 0;
            overflow: hidden;
        }

        .sidebar .collapse-menu .nav-link {
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            font-size: 0.85rem;
        }

        .sidebar .collapse-menu .nav-link::before {
            border-radius: 8px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height));
            transition: var(--transition-smooth);
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Page Header Moderno */
        .page-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            padding: 1.75rem 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            margin-bottom: 2rem;
            border: 1px solid var(--glass-border);
            transition: var(--transition-smooth);
        }

        .page-header:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-2px);
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        /* Cards Modernas */
        .card {
            border: none;
            border-radius: 16px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-soft);
            transition: var(--transition-smooth);
            overflow: hidden;
            border: 1px solid var(--glass-border);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-medium);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-card {
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .stats-card.success::before {
            background: var(--success-gradient);
        }

        .stats-card.warning::before {
            background: var(--secondary-gradient);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: var(--primary-gradient);
            transition: var(--transition-smooth);
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a2e;
            line-height: 1;
        }

        .stats-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        /* Module Cards */
        .module-card {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .module-card .card {
            text-align: center;
            padding: 1.5rem;
            cursor: pointer;
        }

        .module-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .module-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            opacity: 0.1;
            transition: var(--transition-smooth);
        }

        .module-card:hover .module-icon {
            transform: scale(1.15) rotate(-5deg);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .module-card:hover .module-icon::before {
            opacity: 0.2;
        }

        .module-title {
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Buttons Modernos */
        .btn {
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            transition: var(--transition-smooth);
            border: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        /* Tables Modernas */
        .table {
            font-size: 0.875rem;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: var(--transition-smooth);
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
        }

        /* Forms Modernos */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: var(--transition-smooth);
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        /* Badges Modernos */
        .badge {
            font-weight: 500;
            padding: 0.5em 0.85em;
            border-radius: 8px;
            font-size: 0.75rem;
        }

        /* Alerts Modernas */
        .alert {
            border: none;
            border-radius: 12px;
            backdrop-filter: blur(20px);
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .fade-in-left {
            animation: fadeInLeft 0.6s ease-out forwards;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .page-header {
                padding: 1.25rem;
            }

            .stats-number {
                font-size: 1.5rem;
            }

            .module-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Tooltip Custom */
        .tooltip-custom {
            position: relative;
        }

        .tooltip-custom::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #1a1a2e;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: var(--transition-smooth);
            margin-left: 10px;
            z-index: 1000;
        }

        .tooltip-custom:hover::after {
            opacity: 1;
        }

        .sidebar.collapsed .tooltip-custom::after {
            display: block;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('layouts.navbar')
    
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-content">
            @php
                $userRoles = session('user_roles', []);
                $hasAccess = !empty($userRoles);
                $hasAdminAccess = !empty($userRoles) && (in_array('admin', array_map('strtolower', $userRoles)) || in_array('administrador', array_map('strtolower', $userRoles)) || in_array('supervisor', array_map('strtolower', $userRoles)));
            @endphp
            
            <!-- Main Menu -->
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>
            
            @if($hasAccess)
            <!-- Operations -->
            <div class="nav-section">
                <div class="nav-section-title">Operaciones</div>
                <div class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#operacionesMenu" role="button">
                        <i class="bi bi-collection-play-fill"></i>
                        <span>Operaciones</span>
                        <i class="bi bi-chevron-down chevron"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('registros-volumetricos.*') || request()->routeIs('existencias.*') || request()->routeIs('alarmas.*') ? 'show' : '' }}" id="operacionesMenu">
                        <div class="collapse-menu">
                            <a class="nav-link {{ request()->routeIs('registros-volumetricos.*') ? 'active' : '' }}" href="{{ route('registros-volumetricos.index') }}">
                                <i class="bi bi-graph-up"></i>
                                <span>Registros Volumétricos</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('existencias.*') ? 'active' : '' }}" href="{{ route('existencias.index') }}">
                                <i class="bi bi-box-seam"></i>
                                <span>Existencias</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('alarmas.*') ? 'active' : '' }}" href="{{ route('alarmas.index') }}">
                                <i class="bi bi-exclamation-triangle"></i>
                                <span>Alarmas</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Installations -->
            <div class="nav-section">
                <div class="nav-section-title">Instalaciones</div>
                <div class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#instalacionesMenu" role="button">
                        <i class="bi bi-building"></i>
                        <span>Instalaciones</span>
                        <i class="bi bi-chevron-down chevron"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('contribuyentes.*') || request()->routeIs('instalaciones.*') || request()->routeIs('tanques.*') || request()->routeIs('medidores.*') || request()->routeIs('dispensarios.*') || request()->routeIs('mangueras.*') ? 'show' : '' }}" id="instalacionesMenu">
                        <div class="collapse-menu">
                            <a class="nav-link {{ request()->routeIs('contribuyentes.*') ? 'active' : '' }}" href="{{ route('contribuyentes.index') }}">
                                <i class="bi bi-people"></i>
                                <span>Contribuyentes</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('instalaciones.*') ? 'active' : '' }}" href="{{ route('instalaciones.index') }}">
                                <i class="bi bi-geo-alt"></i>
                                <span>Instalaciones</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('tanques.*') ? 'active' : '' }}" href="{{ route('tanques.index') }}">
                                <i class="bi bi-droplet"></i>
                                <span>Tanques</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('medidores.*') ? 'active' : '' }}" href="{{ route('medidores.index') }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Medidores</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('dispensarios.*') ? 'active' : '' }}" href="{{ route('dispensarios.index') }}">
                                <i class="bi bi-fuel-pump"></i>
                                <span>Dispensarios</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('mangueras.*') ? 'active' : '' }}" href="{{ route('mangueras.index') }}">
                                <i class="bi bi-pip"></i>
                                <span>Mangueras</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Catalogs -->
            <div class="nav-section">
                <div class="nav-section-title">Catálogos</div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                        <i class="bi bi-cup-straw"></i>
                        <span>Productos</span>
                    </a>
                </div>
            </div>
            
            <!-- Quality -->
            <div class="nav-section">
                <div class="nav-section-title">Calidad</div>
                <div class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#calidadMenu" role="button">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Calidad</span>
                        <i class="bi bi-chevron-down chevron"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('dictamenes.*') || request()->routeIs('certificados-verificacion.*') ? 'show' : '' }}" id="calidadMenu">
                        <div class="collapse-menu">
                            <a class="nav-link {{ request()->routeIs('dictamenes.*') ? 'active' : '' }}" href="{{ route('dictamenes.index') }}">
                                <i class="bi bi-file-text"></i>
                                <span>Dictámenes</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('certificados-verificacion.*') ? 'active' : '' }}" href="{{ route('certificados-verificacion.index') }}">
                                <i class="bi bi-patch-check"></i>
                                <span>Certificados</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fiscal -->
            <div class="nav-section">
                <div class="nav-section-title">Fiscal</div>
                <div class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#fiscalMenu" role="button">
                        <i class="bi bi-calculator"></i>
                        <span>Fiscal</span>
                        <i class="bi bi-chevron-down chevron"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('cfdi.*') || request()->routeIs('reportes-sat.*') ? 'show' : '' }}" id="fiscalMenu">
                        <div class="collapse-menu">
                            <a class="nav-link {{ request()->routeIs('cfdi.*') ? 'active' : '' }}" href="{{ route('cfdi.index') }}">
                                <i class="bi bi-receipt"></i>
                                <span>CFDI</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('reportes-sat.*') ? 'active' : '' }}" href="{{ route('reportes-sat.index') }}">
                                <i class="bi bi-envelope-paper"></i>
                                <span>Reportes SAT</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Foreign Trade -->
            <div class="nav-section">
                <div class="nav-section-title">Comercio</div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pedimentos.*') ? 'active' : '' }}" href="{{ route('pedimentos.index') }}">
                        <i class="bi bi-truck"></i>
                        <span>Comercio Exterior</span>
                    </a>
                </div>
            </div>
            @endif
            
            @if($hasAdminAccess)
            <!-- Administration -->
            <div class="nav-section">
                <div class="nav-section-title">Administración</div>
                <div class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#adminMenu" role="button">
                        <i class="bi bi-gear"></i>
                        <span>Administración</span>
                        <i class="bi bi-chevron-down chevron"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'show' : '' }}" id="adminMenu">
                        <div class="collapse-menu">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i>
                                <span>Usuarios</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                                <i class="bi bi-shield"></i>
                                <span>Roles</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                                <i class="bi bi-key"></i>
                                <span>Permisos</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Log -->
            <div class="nav-section">
                <div class="nav-section-title">Sistema</div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bitacora.*') ? 'active' : '' }}" href="{{ route('bitacora.index') }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Bitácora</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-chevron-left"></i>
    </button>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3 fade-in-up">
            <div>
                <h1 class="h3 mb-0">@yield('header', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="mt-2">
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
        <div class="fade-in-up stagger-1">
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
        
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const navbarToggler = document.querySelector('.navbar-toggler');
            
            // Toggle sidebar collapse
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    // Save state to localStorage
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                });
            }
            
            // Restore sidebar state
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }
            
            // Mobile sidebar toggle
            if (navbarToggler) {
                navbarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    if (!sidebar.contains(e.target) && !navbarToggler.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
            
            // Add animation classes to elements
            const animateElements = document.querySelectorAll('.card, .stats-card, .module-card');
            animateElements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 + (index * 50));
            });
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add ripple effect to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    
    @stack('scripts')
</body>
</html>