<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlarmaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\CertificadoVerificacionController;
use App\Http\Controllers\CfdiController;
use App\Http\Controllers\ContribuyenteController;
use App\Http\Controllers\DictamenController;
use App\Http\Controllers\DispensarioController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\InstalacionController;
use App\Http\Controllers\MangueraController;
use App\Http\Controllers\MedidorController;
use App\Http\Controllers\PedimentoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RegistroVolumetricoController;
use App\Http\Controllers\ReporteSatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TanqueController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (accesibles sin autenticación)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Rutas protegidas (requieren token en sesión)
Route::middleware('api.auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Perfil de usuario y cambio de contraseña
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
        Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    });

    // ==================== ALARMAS ====================
    Route::prefix('alarmas')->name('alarmas.')->group(function () {
        Route::get('/', [AlarmaController::class, 'index'])->name('index');
        Route::get('/create', [AlarmaController::class, 'create'])->name('create');
        Route::post('/', [AlarmaController::class, 'store'])->name('store');
        Route::get('/{id}', [AlarmaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AlarmaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AlarmaController::class, 'update'])->name('update');
        Route::delete('/{id}', [AlarmaController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/atender', [AlarmaController::class, 'atenderForm'])->name('atender.form');
        Route::post('/{id}/atender', [AlarmaController::class, 'atender'])->name('atender');
        Route::get('/{id}/actualizar-estado', [AlarmaController::class, 'actualizarEstadoForm'])->name('actualizar-estado.form');
        Route::post('/{id}/actualizar-estado', [AlarmaController::class, 'actualizarEstado'])->name('actualizar-estado');
        Route::get('/estadisticas', [AlarmaController::class, 'estadisticas'])->name('estadisticas');
        Route::get('/activas/list', [AlarmaController::class, 'activas'])->name('activas');
    });

    // ==================== BITÁCORA ====================
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
    Route::get('/bitacora/resumen-actividad', [BitacoraController::class, 'resumenActividad'])->name('bitacora.resumen');
    Route::get('/bitacora/actividad-usuario/{usuarioId}', [BitacoraController::class, 'actividadUsuario'])->name('bitacora.actividad-usuario');
    Route::get('/bitacora/actividad-modulo/{modulo}', [BitacoraController::class, 'actividadModulo'])->name('bitacora.actividad-modulo');
    Route::get('/bitacora/actividad-tabla/{tabla}/{registroId?}', [BitacoraController::class, 'actividadTabla'])->name('bitacora.actividad-tabla');
    Route::get('/bitacora/exportar', [BitacoraController::class, 'exportar'])->name('bitacora.exportar');

    // ==================== CERTIFICADOS DE VERIFICACIÓN ====================
    Route::prefix('certificados-verificacion')->name('certificados-verificacion.')->group(function () {
        Route::get('/', [CertificadoVerificacionController::class, 'index'])->name('index');
        Route::get('/create', [CertificadoVerificacionController::class, 'create'])->name('create');
        Route::post('/', [CertificadoVerificacionController::class, 'store'])->name('store');
        Route::get('/{id}', [CertificadoVerificacionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CertificadoVerificacionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CertificadoVerificacionController::class, 'update'])->name('update');
        Route::delete('/{id}', [CertificadoVerificacionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/verificar-vigencia', [CertificadoVerificacionController::class, 'verificarVigencia'])->name('verificar-vigencia');
        Route::get('/estadisticas', [CertificadoVerificacionController::class, 'estadisticas'])->name('estadisticas');
    });

    // ==================== CFDI ====================
    Route::prefix('cfdi')->name('cfdi.')->group(function () {
        Route::get('/', [CfdiController::class, 'index'])->name('index');
        Route::get('/create', [CfdiController::class, 'create'])->name('create');
        Route::post('/', [CfdiController::class, 'store'])->name('store');
        Route::get('/{id}', [CfdiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CfdiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CfdiController::class, 'update'])->name('update');
        Route::delete('/{id}', [CfdiController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/cancelar', [CfdiController::class, 'cancelar'])->name('cancelar');
        Route::get('/rfc/{rfc}', [CfdiController::class, 'porRfc'])->name('por-rfc');
        Route::get('/resumen/fiscal', [CfdiController::class, 'resumenFiscal'])->name('resumen-fiscal');
    });

    // ==================== CONTRIBUYENTES ====================
    Route::prefix('contribuyentes')->name('contribuyentes.')->group(function () {
        Route::get('/', [ContribuyenteController::class, 'index'])->name('index');
        Route::get('/create', [ContribuyenteController::class, 'create'])->name('create');
        Route::post('/', [ContribuyenteController::class, 'store'])->name('store');
        Route::get('/{id}', [ContribuyenteController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ContribuyenteController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ContribuyenteController::class, 'update'])->name('update');
        Route::delete('/{id}', [ContribuyenteController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/instalaciones', [ContribuyenteController::class, 'instalaciones'])->name('instalaciones');
        Route::get('/{id}/cumplimiento', [ContribuyenteController::class, 'cumplimiento'])->name('cumplimiento');
        Route::get('/catalogo/list', [ContribuyenteController::class, 'catalogo'])->name('catalogo');
    });

    // ==================== DICTÁMENES ====================
    Route::prefix('dictamenes')->name('dictamenes.')->group(function () {
        Route::get('/', [DictamenController::class, 'index'])->name('index');
        Route::get('/create', [DictamenController::class, 'create'])->name('create');
        Route::post('/', [DictamenController::class, 'store'])->name('store');
        Route::get('/{id}', [DictamenController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DictamenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DictamenController::class, 'update'])->name('update');
        Route::delete('/{id}', [DictamenController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/cancelar', [DictamenController::class, 'cancelar'])->name('cancelar');
        Route::get('/{id}/verificar-vigencia', [DictamenController::class, 'verificarVigencia'])->name('verificar-vigencia');
        Route::get('/estadisticas', [DictamenController::class, 'estadisticas'])->name('estadisticas');
        Route::get('/producto/{productoId}', [DictamenController::class, 'porProducto'])->name('por-producto');
    });

    // ==================== DISPENSARIOS ====================
    Route::prefix('dispensarios')->name('dispensarios.')->group(function () {
        Route::get('/', [DispensarioController::class, 'index'])->name('index');
        Route::get('/create', [DispensarioController::class, 'create'])->name('create');
        Route::post('/', [DispensarioController::class, 'store'])->name('store');
        Route::get('/{id}', [DispensarioController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DispensarioController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DispensarioController::class, 'update'])->name('update');
        Route::delete('/{id}', [DispensarioController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/mangueras', [DispensarioController::class, 'mangueras'])->name('mangueras');
        Route::get('/{id}/verificar-estado', [DispensarioController::class, 'verificarEstado'])->name('verificar-estado');
    });

    // ==================== EXISTENCIAS ====================
    Route::prefix('existencias')->name('existencias.')->group(function () {
        Route::get('/', [ExistenciaController::class, 'index'])->name('index');
        Route::get('/create', [ExistenciaController::class, 'create'])->name('create');
        Route::post('/', [ExistenciaController::class, 'store'])->name('store');
        Route::get('/{id}', [ExistenciaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ExistenciaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ExistenciaController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExistenciaController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/validar', [ExistenciaController::class, 'validar'])->name('validar');
        Route::get('/inventario-actual/{tanqueId}', [ExistenciaController::class, 'inventarioActual'])->name('inventario-actual');
        Route::get('/historico/{tanqueId}', [ExistenciaController::class, 'historico'])->name('historico');
        Route::get('/reporte/mermas', [ExistenciaController::class, 'reporteMermas'])->name('reporte-mermas');
        Route::get('/por-fecha', [ExistenciaController::class, 'porFecha'])->name('por-fecha');
    });

    // ==================== INSTALACIONES ====================
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
        Route::get('/{id}/resumen-operativo', [InstalacionController::class, 'resumenOperativo'])->name('resumen-operativo');
    });

    // ==================== MANGUERAS ====================
    Route::prefix('mangueras')->name('mangueras.')->group(function () {
        Route::get('/', [MangueraController::class, 'index'])->name('index');
        Route::get('/create', [MangueraController::class, 'create'])->name('create');
        Route::post('/', [MangueraController::class, 'store'])->name('store');
        Route::get('/{id}', [MangueraController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MangueraController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MangueraController::class, 'update'])->name('update');
        Route::delete('/{id}', [MangueraController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/asignar-medidor', [MangueraController::class, 'asignarMedidor'])->name('asignar-medidor');
        Route::post('/{id}/quitar-medidor', [MangueraController::class, 'quitarMedidor'])->name('quitar-medidor');
    });

    // ==================== MEDIDORES ====================
    Route::prefix('medidores')->name('medidores.')->group(function () {
        Route::get('/', [MedidorController::class, 'index'])->name('index');
        Route::get('/create', [MedidorController::class, 'create'])->name('create');
        Route::post('/', [MedidorController::class, 'store'])->name('store');
        Route::get('/{id}', [MedidorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MedidorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MedidorController::class, 'update'])->name('update');
        Route::delete('/{id}', [MedidorController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/calibrar', [MedidorController::class, 'registrarCalibracion'])->name('registrar-calibracion');
        Route::get('/{id}/probar-comunicacion', [MedidorController::class, 'probarComunicacion'])->name('probar-comunicacion');
        Route::get('/{id}/verificar-estado', [MedidorController::class, 'verificarEstado'])->name('verificar-estado');
        Route::get('/{id}/historial-calibraciones', [MedidorController::class, 'historialCalibraciones'])->name('historial-calibraciones');
    });

    // ==================== PEDIMENTOS ====================
    Route::prefix('pedimentos')->name('pedimentos.')->group(function () {
        Route::get('/', [PedimentoController::class, 'index'])->name('index');
        Route::get('/create', [PedimentoController::class, 'create'])->name('create');
        Route::post('/', [PedimentoController::class, 'store'])->name('store');
        Route::get('/{id}', [PedimentoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PedimentoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PedimentoController::class, 'update'])->name('update');
        Route::delete('/{id}', [PedimentoController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/cancelar', [PedimentoController::class, 'cancelar'])->name('cancelar');
        Route::post('/{id}/utilizado', [PedimentoController::class, 'marcarUtilizado'])->name('utilizado');
        Route::get('/resumen/comercio-exterior', [PedimentoController::class, 'resumenComercioExterior'])->name('resumen-comercio-exterior');
    });

    // ==================== PERMISOS ====================
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/por-modulo/list', [PermissionController::class, 'porModulo'])->name('por-modulo');
        Route::get('/verificar/permiso', [PermissionController::class, 'verificar'])->name('verificar');
    });

    // ==================== PRODUCTOS ====================
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('index');
        Route::get('/create', [ProductoController::class, 'create'])->name('create');
        Route::post('/', [ProductoController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductoController::class, 'destroy'])->name('destroy');
        Route::get('/tipo/{tipo}', [ProductoController::class, 'porTipo'])->name('por-tipo');
        Route::get('/catalogo/list', [ProductoController::class, 'catalogo'])->name('catalogo');
        Route::get('/buscar/clave-sat/{claveSat}', [ProductoController::class, 'buscarPorClaveSat'])->name('buscar-clave-sat');
    });

    // ==================== REGISTROS VOLUMÉTRICOS ====================
    Route::prefix('registros-volumetricos')->name('registros-volumetricos.')->group(function () {
        Route::get('/', [RegistroVolumetricoController::class, 'index'])->name('index');
        Route::get('/create', [RegistroVolumetricoController::class, 'create'])->name('create');
        Route::post('/', [RegistroVolumetricoController::class, 'store'])->name('store');
        Route::get('/{id}', [RegistroVolumetricoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RegistroVolumetricoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RegistroVolumetricoController::class, 'update'])->name('update');
        Route::delete('/{id}', [RegistroVolumetricoController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/validar', [RegistroVolumetricoController::class, 'validar'])->name('validar');
        Route::post('/{id}/cancelar', [RegistroVolumetricoController::class, 'cancelar'])->name('cancelar');
        Route::get('/resumen/diario', [RegistroVolumetricoController::class, 'resumenDiario'])->name('resumen-diario');
        Route::get('/estadisticas/mensuales', [RegistroVolumetricoController::class, 'estadisticasMensuales'])->name('estadisticas-mensuales');
        Route::post('/{id}/asociar-dictamen', [RegistroVolumetricoController::class, 'asociarDictamen'])->name('asociar-dictamen');
    });

    // ==================== REPORTES SAT ====================
    Route::prefix('reportes-sat')->name('reportes-sat.')->group(function () {
        Route::get('/', [ReporteSatController::class, 'index'])->name('index');
        Route::get('/create', [ReporteSatController::class, 'create'])->name('create');
        Route::post('/', [ReporteSatController::class, 'store'])->name('store');
        Route::get('/{id}', [ReporteSatController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReporteSatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReporteSatController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReporteSatController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/enviar', [ReporteSatController::class, 'enviar'])->name('enviar');
        Route::post('/{id}/firmar', [ReporteSatController::class, 'firmar'])->name('firmar');
        Route::post('/{id}/cancelar', [ReporteSatController::class, 'cancelar'])->name('cancelar');
        Route::get('/historial/envios/{instalacionId}', [ReporteSatController::class, 'historialEnvios'])->name('historial-envios');
    });

    // ==================== ROLES ====================
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/asignar-permisos', [RoleController::class, 'asignarPermisos'])->name('asignar-permisos');
        Route::post('/{id}/clonar', [RoleController::class, 'clonar'])->name('clonar');
        Route::get('/matriz/permisos', [RoleController::class, 'matrizPermisos'])->name('matriz-permisos');
    });

    // ==================== TANQUES ====================
    Route::prefix('tanques')->name('tanques.')->group(function () {
        Route::get('/', [TanqueController::class, 'index'])->name('index');
        Route::get('/create', [TanqueController::class, 'create'])->name('create');
        Route::post('/', [TanqueController::class, 'store'])->name('store');
        Route::get('/{id}', [TanqueController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TanqueController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TanqueController::class, 'update'])->name('update');
        Route::delete('/{id}', [TanqueController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/calibrar', [TanqueController::class, 'registrarCalibracion'])->name('registrar-calibracion');
        Route::get('/{id}/verificar-estado', [TanqueController::class, 'verificarEstado'])->name('verificar-estado');
        Route::post('/{id}/cambiar-producto', [TanqueController::class, 'cambiarProducto'])->name('cambiar-producto');
        Route::get('/{id}/curva-calibracion', [TanqueController::class, 'curvaCalibracion'])->name('curva-calibracion');
        Route::get('/{id}/historial-calibraciones', [TanqueController::class, 'historialCalibraciones'])->name('historial-calibraciones');
    });

    // ==================== USUARIOS ====================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/bloquear', [UserController::class, 'bloquear'])->name('bloquear');
        Route::post('/{id}/desbloquear', [UserController::class, 'desbloquear'])->name('desbloquear');
        Route::post('/{id}/asignar-rol', [UserController::class, 'asignarRol'])->name('asignar-rol');
        Route::post('/{id}/quitar-rol', [UserController::class, 'quitarRol'])->name('quitar-rol');
        Route::get('/{id}/permisos', [UserController::class, 'permisos'])->name('permisos');
        Route::get('/{id}/actividad', [UserController::class, 'actividad'])->name('actividad');
    });

    // ==================== API INTERNAS (para AJAX) ====================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard/resumen', [DashboardController::class, 'resumen'])->name('dashboard.resumen');
        Route::get('/notificaciones', [DashboardController::class, 'notificaciones'])->name('notificaciones');
        Route::get('/catalogos', function () {
            return response()->json(['error' => 'Catálogo no implementado'], 501);
        })->name('catalogos');
    });
});

// Ruta raíz redirige a dashboard o login según autenticación
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});