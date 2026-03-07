<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ContribuyenteController;
use App\Http\Controllers\InstalacionController;
use App\Http\Controllers\TanqueController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MedidorController;
use App\Http\Controllers\RegistroVolumetricoController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\AlarmaController;
use App\Http\Controllers\PedimentoController;
use App\Http\Controllers\CfdiController;
use App\Http\Controllers\DictamenController;
use App\Http\Controllers\CertificadoVerificacionController;
use App\Http\Controllers\ReporteSatController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\ConfiguracionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (sin autenticación)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Registro
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // Recuperación de contraseña
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgot'])->name('forgot-password.post');
    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/tiempo-real', [DashboardController::class, 'tiempoReal'])->name('dashboard.tiempo-real');
    Route::get('/dashboard/grafica-movimientos', [DashboardController::class, 'graficaMovimientos'])->name('dashboard.grafica-movimientos');
    Route::post('/notificaciones/{id}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notificaciones.read');
    
    // Perfil de usuario
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('change-password');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password.post');
    });
    
    // Usuarios
    Route::resource('usuarios', UserController::class)->parameters([
        'usuarios' => 'id'
    ]);
    
    // Roles y Permisos
    Route::resource('roles', RoleController::class)->parameters([
        'roles' => 'id'
    ]);
    Route::post('roles/{id}/asignar-permisos', [RoleController::class, 'asignarPermisos'])->name('roles.asignar-permisos');
    Route::get('roles/{id}/permisos', [RoleController::class, 'getPermisos'])->name('roles.permisos');
    
    Route::resource('permissions', PermissionController::class)->parameters([
        'permissions' => 'id'
    ]);
    
    // Contribuyentes
    Route::prefix('contribuyentes')->name('contribuyentes.')->group(function () {
        Route::get('/', [ContribuyenteController::class, 'index'])->name('index');
        Route::get('/create', [ContribuyenteController::class, 'create'])->name('create');
        Route::post('/', [ContribuyenteController::class, 'store'])->name('store');
        Route::get('/{id}', [ContribuyenteController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ContribuyenteController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ContribuyenteController::class, 'update'])->name('update');
        Route::delete('/{id}', [ContribuyenteController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [ContribuyenteController::class, 'restore'])->name('restore');
        Route::get('/{id}/instalaciones', [ContribuyenteController::class, 'instalaciones'])->name('instalaciones');
        Route::get('/{id}/cumplimiento', [ContribuyenteController::class, 'cumplimiento'])->name('cumplimiento');
    });
    
    // Instalaciones
    Route::prefix('instalaciones')->name('instalaciones.')->group(function () {
        Route::get('/', [InstalacionController::class, 'index'])->name('index');
        Route::get('/create', [InstalacionController::class, 'create'])->name('create');
        Route::post('/', [InstalacionController::class, 'store'])->name('store');
        Route::get('/{id}', [InstalacionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [InstalacionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InstalacionController::class, 'update'])->name('update');
        Route::delete('/{id}', [InstalacionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/tanques', [InstalacionController::class, 'tanques'])->name('tanques');
        Route::get('/{id}/medidores', [InstalacionController::class, 'medidores'])->name('medidores');
        Route::get('/{id}/dispensarios', [InstalacionController::class, 'dispensarios'])->name('dispensarios');
        Route::get('/{id}/verificar-comunicacion', [InstalacionController::class, 'verificarComunicacion'])->name('verificar-comunicacion');
        Route::get('/{id}/resumen-operativo', [InstalacionController::class, 'resumenOperativo'])->name('resumen-operativo');
        Route::put('/{id}/configuracion-red', [InstalacionController::class, 'actualizarConfiguracionRed'])->name('configuracion-red');
        Route::put('/{id}/umbrales-alarma', [InstalacionController::class, 'actualizarUmbralesAlarma'])->name('umbrales-alarma');
        Route::get('/{id}/reporte-cumplimiento', [InstalacionController::class, 'reporteCumplimientoNormativo'])->name('reporte-cumplimiento');
    });
    
    // Tanques
    Route::prefix('tanques')->name('tanques.')->group(function () {
        Route::get('/', [TanqueController::class, 'index'])->name('index');
        Route::get('/create', [TanqueController::class, 'create'])->name('create');
        Route::post('/', [TanqueController::class, 'store'])->name('store');
        Route::get('/{id}', [TanqueController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TanqueController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TanqueController::class, 'update'])->name('update');
        Route::delete('/{id}', [TanqueController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/existencias', [TanqueController::class, 'existencias'])->name('existencias');
        Route::get('/{id}/ultima-existencia', [TanqueController::class, 'ultimaExistencia'])->name('ultima-existencia');
    });
    
    // Productos
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('index');
        Route::get('/create', [ProductoController::class, 'create'])->name('create');
        Route::post('/', [ProductoController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductoController::class, 'destroy'])->name('destroy');
        Route::get('/tipo/{tipo}', [ProductoController::class, 'byTipo'])->name('by-tipo');
    });
    
    // Medidores
    Route::prefix('medidores')->name('medidores.')->group(function () {
        Route::get('/', [MedidorController::class, 'index'])->name('index');
        Route::get('/create', [MedidorController::class, 'create'])->name('create');
        Route::post('/', [MedidorController::class, 'store'])->name('store');
        Route::get('/{id}', [MedidorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MedidorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MedidorController::class, 'update'])->name('update');
        Route::delete('/{id}', [MedidorController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/calibrar', [MedidorController::class, 'calibrar'])->name('calibrar');
        Route::get('/tanques/{instalacionId}', [MedidorController::class, 'getTanquesByInstalacion'])->name('tanques-by-instalacion');
    });
    
    // Registros Volumétricos
    Route::prefix('registros-volumetricos')->name('registros-volumetricos.')->group(function () {
        Route::get('/', [RegistroVolumetricoController::class, 'index'])->name('index');
        Route::get('/create', [RegistroVolumetricoController::class, 'create'])->name('create');
        Route::post('/', [RegistroVolumetricoController::class, 'store'])->name('store');
        Route::get('/{id}', [RegistroVolumetricoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RegistroVolumetricoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RegistroVolumetricoController::class, 'update'])->name('update');
        Route::delete('/{id}', [RegistroVolumetricoController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/validar', [RegistroVolumetricoController::class, 'validar'])->name('validar');
        Route::post('/{id}/asociar-cfdi', [RegistroVolumetricoController::class, 'asociarCfdi'])->name('asociar-cfdi');
        Route::post('/{id}/asociar-pedimento', [RegistroVolumetricoController::class, 'asociarPedimento'])->name('asociar-pedimento');
        Route::post('/{id}/marcar-con-alarma', [RegistroVolumetricoController::class, 'marcarConAlarma'])->name('marcar-con-alarma');
        Route::post('/{id}/cancelar', [RegistroVolumetricoController::class, 'cancelar'])->name('cancelar');
        Route::get('/{id}/resumen-diario', [RegistroVolumetricoController::class, 'resumenDiario'])->name('resumen-diario');
        Route::get('/{id}/estadisticas-mensuales', [RegistroVolumetricoController::class, 'estadisticasMensuales'])->name('estadisticas-mensuales');
    });
    
    // Existencias
    Route::prefix('existencias')->name('existencias.')->group(function () {
        Route::get('/', [ExistenciaController::class, 'index'])->name('index');
        Route::get('/create', [ExistenciaController::class, 'create'])->name('create');
        Route::post('/', [ExistenciaController::class, 'store'])->name('store');
        Route::get('/{id}', [ExistenciaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ExistenciaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ExistenciaController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExistenciaController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/validar', [ExistenciaController::class, 'validar'])->name('validar');
        Route::post('/{id}/asociar-cfdi', [ExistenciaController::class, 'asociarCfdi'])->name('asociar-cfdi');
        Route::post('/{id}/asociar-pedimento', [ExistenciaController::class, 'asociarPedimento'])->name('asociar-pedimento');
        Route::get('/reporte/inventario-diario', [ExistenciaController::class, 'inventarioDiario'])->name('inventario-diario');
    });
    
    // Alarmas
    Route::prefix('alarmas')->name('alarmas.')->group(function () {
        Route::get('/', [AlarmaController::class, 'index'])->name('index');
        Route::get('/dashboard', [AlarmaController::class, 'dashboard'])->name('dashboard');
        Route::get('/estadisticas', [AlarmaController::class, 'estadisticas'])->name('estadisticas');
        Route::get('/{id}', [AlarmaController::class, 'show'])->name('show');
        Route::put('/{id}', [AlarmaController::class, 'update'])->name('update');
        Route::delete('/{id}', [AlarmaController::class, 'destroy'])->name('destroy');
    });
    
    // Pedimentos
    Route::prefix('pedimentos')->name('pedimentos.')->group(function () {
        Route::get('/', [PedimentoController::class, 'index'])->name('index');
        Route::get('/create', [PedimentoController::class, 'create'])->name('create');
        Route::post('/', [PedimentoController::class, 'store'])->name('store');
        Route::get('/{id}', [PedimentoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PedimentoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PedimentoController::class, 'update'])->name('update');
        Route::delete('/{id}', [PedimentoController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/asociar-registro', [PedimentoController::class, 'asociarRegistro'])->name('asociar-registro');
    });
    
    // CFDI
    Route::prefix('cfdi')->name('cfdi.')->group(function () {
        Route::get('/', [CfdiController::class, 'index'])->name('index');
        Route::get('/create', [CfdiController::class, 'create'])->name('create');
        Route::post('/', [CfdiController::class, 'store'])->name('store');
        Route::get('/{id}', [CfdiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CfdiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CfdiController::class, 'update'])->name('update');
        Route::delete('/{id}', [CfdiController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/cancelar', [CfdiController::class, 'cancelar'])->name('cancelar');
        Route::get('/{id}/xml', [CfdiController::class, 'getXml'])->name('xml');
        Route::get('/{id}/pdf', [CfdiController::class, 'getPdf'])->name('pdf');
        Route::get('/{id}/descargar-xml', [CfdiController::class, 'descargarXml'])->name('descargar-xml');
        Route::get('/{id}/descargar-pdf', [CfdiController::class, 'descargarPdf'])->name('descargar-pdf');
    });
    
    // Dictámenes
    Route::prefix('dictamenes')->name('dictamenes.')->group(function () {
        Route::get('/', [DictamenController::class, 'index'])->name('index');
        Route::get('/create', [DictamenController::class, 'create'])->name('create');
        Route::post('/', [DictamenController::class, 'store'])->name('store');
        Route::get('/{id}', [DictamenController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DictamenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DictamenController::class, 'update'])->name('update');
        Route::delete('/{id}', [DictamenController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/pdf', [DictamenController::class, 'generarPdf'])->name('pdf');
    });
    
    // Certificados de Verificación
    Route::prefix('certificados-verificacion')->name('certificados-verificacion.')->group(function () {
        Route::get('/', [CertificadoVerificacionController::class, 'index'])->name('index');
        Route::get('/create', [CertificadoVerificacionController::class, 'create'])->name('create');
        Route::post('/', [CertificadoVerificacionController::class, 'store'])->name('store');
        Route::get('/{id}', [CertificadoVerificacionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CertificadoVerificacionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CertificadoVerificacionController::class, 'update'])->name('update');
        Route::delete('/{id}', [CertificadoVerificacionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/pdf', [CertificadoVerificacionController::class, 'generarPdf'])->name('pdf');
    });
    
    // Reportes SAT
    Route::prefix('reportes-sat')->name('reportes-sat.')->group(function () {
        Route::get('/', [ReporteSatController::class, 'index'])->name('index');
        Route::get('/create', [ReporteSatController::class, 'create'])->name('create');
        Route::post('/', [ReporteSatController::class, 'store'])->name('store');
        Route::get('/{id}', [ReporteSatController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReporteSatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReporteSatController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReporteSatController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/firmar', [ReporteSatController::class, 'firmar'])->name('firmar');
        Route::post('/{id}/enviar', [ReporteSatController::class, 'enviar'])->name('enviar');
        Route::get('/{id}/descargar-xml', [ReporteSatController::class, 'descargarXml'])->name('descargar-xml');
        Route::get('/{id}/descargar-pdf', [ReporteSatController::class, 'descargarPdf'])->name('descargar-pdf');
        Route::get('/{id}/acuse', [ReporteSatController::class, 'acuse'])->name('acuse');
    });
    
    // Bitácora
    Route::prefix('bitacora')->name('bitacora.')->group(function () {
        Route::get('/', [BitacoraController::class, 'index'])->name('index');
        Route::get('/dashboard', [BitacoraController::class, 'dashboard'])->name('dashboard');
        Route::get('/filtrar', [BitacoraController::class, 'filtrar'])->name('filtrar');
        Route::get('/exportar-csv', [BitacoraController::class, 'exportarCsv'])->name('exportar-csv');
        Route::get('/{id}', [BitacoraController::class, 'show'])->name('show');
    });
    
    // Configuración
    Route::prefix('configuracion')->name('configuracion.')->group(function () {
        Route::get('/', [ConfiguracionController::class, 'index'])->name('index');
        Route::put('/', [ConfiguracionController::class, 'update'])->name('update');
        Route::post('/backup-manual', [ConfiguracionController::class, 'backupManual'])->name('backup-manual');
        Route::post('/limpiar-cache', [ConfiguracionController::class, 'limpiarCache'])->name('limpiar-cache');
        Route::get('/logs', [ConfiguracionController::class, 'logs'])->name('logs');
        Route::get('/logs/view', [ConfiguracionController::class, 'viewLog'])->name('logs.view');
        Route::get('/logs/download', [ConfiguracionController::class, 'downloadLog'])->name('logs.download');
        Route::post('/logs/clear', [ConfiguracionController::class, 'clearLog'])->name('logs.clear');
        Route::get('/exportar', [ConfiguracionController::class, 'exportar'])->name('exportar');
        Route::post('/importar', [ConfiguracionController::class, 'importar'])->name('importar');
    });
});

// Rutas de API internas (para consumo AJAX)
Route::prefix('api')->name('api.')->middleware('auth')->group(function () {
    // Tanques
    Route::get('/tanques/por-instalacion/{instalacionId}', [TanqueController::class, 'getByInstalacion'])->name('tanques.por-instalacion');
    Route::get('/tanques/{id}/capacidad-disponible', [TanqueController::class, 'getCapacidadDisponible'])->name('tanques.capacidad-disponible');
    
    // Medidores
    Route::get('/medidores/por-instalacion/{instalacionId}', [MedidorController::class, 'getByInstalacion'])->name('medidores.por-instalacion');
    Route::get('/medidores/por-tanque/{tanqueId}', [MedidorController::class, 'getByTanque'])->name('medidores.por-tanque');
    
    // Dispensarios
    Route::get('/dispensarios/por-instalacion/{instalacionId}', [DispensarioController::class, 'getByInstalacion'])->name('dispensarios.por-instalacion');
    
    // Mangueras
    Route::get('/mangueras/por-dispensario/{dispensarioId}', [MangueraController::class, 'getByDispensario'])->name('mangueras.por-dispensario');
    
    // Existencias
    Route::get('/existencias/disponibles', [ExistenciaController::class, 'getDisponibles'])->name('existencias.disponibles');
    
    // CFDI
    Route::get('/cfdi/disponibles', [CfdiController::class, 'getDisponibles'])->name('cfdi.disponibles');
    
    // Pedimentos
    Route::get('/pedimentos/disponibles', [PedimentoController::class, 'getDisponibles'])->name('pedimentos.disponibles');
    
    // Registros Volumétricos
    Route::get('/registros-volumetricos/disponibles', [RegistroVolumetricoController::class, 'getDisponibles'])->name('registros-volumetricos.disponibles');
});

// Fallback route
Route::fallback(function () {
    return redirect('/');
});