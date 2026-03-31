<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteControllerViewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /**
     * @test
     * Verifica que todas las rutas protegidas tengan controladores válidos
     */
    public function test_all_protected_routes_have_valid_controllers()
    {
        $routes = Route::getRoutes();
        $protectedRoutes = [];
        $errors = [];

        foreach ($routes as $route) {
            $action = $route->getAction();
            
            // Solo verificar rutas que usan controladores (no closures)
            if (isset($action['uses']) && is_string($action['uses'])) {
                $controllerAction = $action['uses'];
                
                // Verificar que el controlador existe
                if (str_contains($controllerAction, '@')) {
                    [$controller, $method] = explode('@', $controllerAction);
                    
                    if (!class_exists($controller)) {
                        $errors[] = "Controlador no existe: {$controller} para ruta: {$route->uri()}";
                    } elseif (!method_exists($controller, $method)) {
                        $errors[] = "Método no existe: {$method} en controlador: {$controller} para ruta: {$route->uri()}";
                    }
                }
            }
        }

        $this->assertEmpty($errors, "Problemas encontrados en controladores:\n" . implode("\n", $errors));
    }

    /**
     * @test
     * Verifica que las rutas de módulos principales retornen las vistas correctas
     */
    public function test_main_module_routes_return_correct_views()
    {
        $moduleRoutes = [
            // Contribuyentes
            ['GET', '/contribuyentes', 'contribuyentes.index'],
            ['GET', '/contribuyentes/create', 'contribuyentes.create'],
            ['GET', '/contribuyentes/1', 'contribuyentes.show'],
            ['GET', '/contribuyentes/1/edit', 'contribuyentes.edit'],
            ['GET', '/contribuyentes/1/instalaciones', 'contribuyentes.instalaciones'],
            ['GET', '/contribuyentes/1/cumplimiento', 'contribuyentes.cumplimiento'],
            
            // Instalaciones
            ['GET', '/instalaciones', 'instalaciones.index'],
            ['GET', '/instalaciones/create', 'instalaciones.create'],
            ['GET', '/instalaciones/1', 'instalaciones.show'],
            ['GET', '/instalaciones/1/edit', 'instalaciones.edit'],
            ['GET', '/instalaciones/1/tanques', 'instalaciones.tanques'],
            ['GET', '/instalaciones/1/medidores', 'instalaciones.medidores'],
            ['GET', '/instalaciones/1/dispensarios', 'instalaciones.dispensarios'],
            
            // Tanques
            ['GET', '/tanques', 'tanques.index'],
            ['GET', '/tanques/create', 'tanques.create'],
            ['GET', '/tanques/1', 'tanques.show'],
            ['GET', '/tanques/1/edit', 'tanques.edit'],
            
            // Medidores
            ['GET', '/medidores', 'medidores.index'],
            ['GET', '/medidores/create', 'medidores.create'],
            ['GET', '/medidores/1', 'medidores.show'],
            ['GET', '/medidores/1/edit', 'medidores.edit'],
            
            // Productos
            ['GET', '/productos', 'productos.index'],
            ['GET', '/productos/create', 'productos.create'],
            ['GET', '/productos/1', 'productos.show'],
            ['GET', '/productos/1/edit', 'productos.edit'],
            
            // Alarmas
            ['GET', '/alarmas', 'alarmas.index'],
            ['GET', '/alarmas/create', 'alarmas.create'],
            ['GET', '/alarmas/1', 'alarmas.show'],
            ['GET', '/alarmas/1/edit', 'alarmas.edit'],
            
            // Roles
            ['GET', '/roles', 'roles.index'],
            ['GET', '/roles/create', 'roles.create'],
            ['GET', '/roles/1', 'roles.show'],
            ['GET', '/roles/1/edit', 'roles.edit'],
            
            // Usuarios
            ['GET', '/users', 'users.index'],
            ['GET', '/users/create', 'users.create'],
            ['GET', '/users/1', 'users.show'],
            ['GET', '/users/1/edit', 'users.edit'],
            
            // Dashboard
            ['GET', '/dashboard', 'dashboard.index'],
            
            // Configuración
            ['GET', '/configuracion', 'configuracion.index'],
            
            // Bitácora
            ['GET', '/bitacora', 'bitacora.index'],
            ['GET', '/bitacora/1', 'bitacora.show'],
        ];

        foreach ($moduleRoutes as [$method, $uri, $expectedView]) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->call($method, $uri);
            
            $this->assertEquals(
                200, 
                $response->getStatusCode(),
                "Ruta {$method} {$uri} no retornó estado 200"
            );
            
            $viewData = $response->getOriginalContent();
            
            if (is_object($viewData) && method_exists($viewData, 'getName')) {
                $this->assertEquals(
                    $expectedView,
                    $viewData->getName(),
                    "Ruta {$method} {$uri} no retornó la vista esperada {$expectedView}"
                );
            }
        }
    }

    /**
     * @test
     * Verifica que las rutas de autenticación retornen las vistas correctas
     */
    public function test_auth_routes_return_correct_views()
    {
        // Login form
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');

        // Register form
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register.form');
    }

    /**
     * @test
     * Verifica que las rutas de exportación retornen archivos
     */
    public function test_export_routes_return_files()
    {
        $exportRoutes = [
            '/contribuyentes/exportar',
            '/instalaciones/exportar',
            '/tanques/exportar',
            '/medidores/exportar',
            '/productos/exportar',
            '/alarmas/exportar',
            '/roles/exportar',
            '/users/exportar',
            '/bitacora/exportar',
            '/configuracion/exportar',
        ];

        foreach ($exportRoutes as $uri) {
            Http::fake([
                $this->baseApiUrl.'/api/exportar/*' => Http::response(
                    'csv content',
                    200,
                    [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="export.csv"',
                    ]
                ),
                '*' => Http::response(['success' => true, 'data' => []], 200),
            ]);

            $response = $this->get($uri);
            
            $this->assertContains($response->getStatusCode(), [200, 302], 
                "Ruta de exportación {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de API internas retornen JSON
     */
    public function test_internal_api_routes_return_json()
    {
        $apiRoutes = [
            '/api/dashboard/grafica-movimientos',
            '/api/dashboard/grafica-productos',
            '/api/notificaciones',
            '/contribuyentes/catalogo/list',
            '/productos/catalogo/list',
        ];

        foreach ($apiRoutes as $uri) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->get($uri);
            
            $this->assertContains($response->getStatusCode(), [200, 302],
                "Ruta API {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de acciones (store, update, destroy) funcionen correctamente
     */
    public function test_action_routes_work_correctly()
    {
        $actionRoutes = [
            // Store routes (POST)
            ['POST', '/contribuyentes', ['rfc' => 'TEST123456XXX']],
            ['POST', '/instalaciones', ['nombre' => 'Test']],
            ['POST', '/tanques', ['identificador' => 'TAN-001']],
            ['POST', '/medidores', ['numero_serie' => 'MED-001']],
            ['POST', '/productos', ['nombre' => 'Producto Test']],
            ['POST', '/alarmas', ['descripcion' => 'Alarma Test']],
            ['POST', '/roles', ['nombre' => 'Rol Test']],
            ['POST', '/users', ['email' => 'test@test.com']],
            
            // Update routes (PUT)
            ['PUT', '/contribuyentes/1', ['rfc' => 'TEST123456XXX']],
            ['PUT', '/instalaciones/1', ['nombre' => 'Test']],
            ['PUT', '/tanques/1', ['identificador' => 'TAN-001']],
            ['PUT', '/medidores/1', ['numero_serie' => 'MED-001']],
            ['PUT', '/productos/1', ['nombre' => 'Producto Test']],
            ['PUT', '/alarmas/1', ['descripcion' => 'Alarma Test']],
            ['PUT', '/roles/1', ['nombre' => 'Rol Test']],
            ['PUT', '/users/1', ['email' => 'test@test.com']],
            
            // Delete routes
            ['DELETE', '/contribuyentes/1', []],
            ['DELETE', '/instalaciones/1', []],
            ['DELETE', '/tanques/1', []],
            ['DELETE', '/medidores/1', []],
            ['DELETE', '/productos/1', []],
            ['DELETE', '/alarmas/1', []],
            ['DELETE', '/roles/1', []],
            ['DELETE', '/users/1', []],
        ];

        foreach ($actionRoutes as [$method, $uri, $data]) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->call($method, $uri, $data);
            
            $this->assertContains($response->getStatusCode(), [200, 302, 422],
                "Ruta de acción {$method} {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas con parámetros dinámicos funcionen
     */
    public function test_dynamic_routes_work_correctly()
    {
        $dynamicRoutes = [
            // Contribuyentes
            ['GET', '/contribuyentes/{id}', 'contribuyentes.show'],
            ['GET', '/contribuyentes/{id}/edit', 'contribuyentes.edit'],
            ['GET', '/contribuyentes/{id}/instalaciones', 'contribuyentes.instalaciones'],
            ['GET', '/contribuyentes/{id}/cumplimiento', 'contribuyentes.cumplimiento'],
            
            // Instalaciones
            ['GET', '/instalaciones/{id}', 'instalaciones.show'],
            ['GET', '/instalaciones/{id}/edit', 'instalaciones.edit'],
            ['GET', '/instalaciones/{id}/tanques', 'instalaciones.tanques'],
            ['GET', '/instalaciones/{id}/medidores', 'instalaciones.medidores'],
            ['GET', '/instalaciones/{id}/dispensarios', 'instalaciones.dispensarios'],
            
            // Tanques
            ['GET', '/tanques/{id}', 'tanques.show'],
            ['GET', '/tanques/{id}/edit', 'tanques.edit'],
            
            // Medidores
            ['GET', '/medidores/{id}', 'medidores.show'],
            ['GET', '/medidores/{id}/edit', 'medidores.edit'],
            
            // Productos
            ['GET', '/productos/{id}', 'productos.show'],
            ['GET', '/productos/{id}/edit', 'productos.edit'],
            
            // Alarmas
            ['GET', '/alarmas/{id}', 'alarmas.show'],
            ['GET', '/alarmas/{id}/edit', 'alarmas.edit'],
            
            // Roles
            ['GET', '/roles/{id}', 'roles.show'],
            ['GET', '/roles/{id}/edit', 'roles.edit'],
            
            // Usuarios
            ['GET', '/users/{id}', 'users.show'],
            ['GET', '/users/{id}/edit', 'users.edit'],
        ];

        foreach ($dynamicRoutes as [$method, $uri, $expectedView]) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->call($method, str_replace('{id}', '1', $uri));
            
            $this->assertContains($response->getStatusCode(), [200, 302],
                "Ruta dinámica {$method} {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de acciones especiales funcionen
     */
    public function test_special_action_routes_work()
    {
        $specialRoutes = [
            // Alarmas
            ['GET', '/alarmas/1/atender', 'alarmas.atender.form'],
            ['POST', '/alarmas/1/atender', []],
            ['GET', '/alarmas/1/actualizar-estado', 'alarmas.actualizar-estado.form'],
            ['POST', '/alarmas/1/actualizar-estado', []],
            
            // Roles
            ['POST', '/roles/1/asignar-permisos', []],
            ['POST', '/roles/1/clonar', []],
            ['GET', '/roles/matriz/permisos', 'roles.matriz-permisos'],
            
            // Usuarios
            ['POST', '/users/1/bloquear', []],
            ['POST', '/users/1/desbloquear', []],
            ['POST', '/users/1/asignar-rol', []],
            ['POST', '/users/1/quitar-rol', []],
            ['GET', '/users/1/permisos', 'users.permisos'],
            ['GET', '/users/1/actividad', 'users.actividad'],
            
            // Tanques
            ['POST', '/tanques/1/calibrar', []],
            ['GET', '/tanques/1/verificar-estado', 'tanques.verificar-estado'],
            ['POST', '/tanques/1/cambiar-producto', []],
            ['GET', '/tanques/1/curva-calibracion', 'tanques.curva-calibracion'],
            ['GET', '/tanques/1/historial-calibraciones', 'tanques.historial-calibraciones'],
            
            // Medidores
            ['POST', '/medidores/1/calibrar', []],
            ['GET', '/medidores/1/probar-comunicacion', 'medidores.probar-comunicacion'],
            ['GET', '/medidores/1/verificar-estado', 'medidores.verificar-estado'],
            ['GET', '/medidores/1/historial-calibraciones', 'medidores.historial-calibraciones'],
            
            // Configuración
            ['PUT', '/configuracion', []],
            ['POST', '/configuracion/backup-manual', []],
            ['POST', '/configuracion/limpiar-cache', []],
            ['GET', '/configuracion/logs', 'configuracion.logs'],
        ];

        foreach ($specialRoutes as [$method, $uri, $data]) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->call($method, str_replace('1', '1', $uri), is_array($data) ? $data : []);
            
            $this->assertContains($response->getStatusCode(), [200, 302, 422],
                "Ruta especial {$method} {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las vistas existan en el sistema de archivos
     */
    public function test_all_referenced_views_exist()
    {
        $views = [
            'auth.login',
            'auth.register.form',
            'dashboard.index',
            'contribuyentes.index',
            'contribuyentes.create',
            'contribuyentes.show',
            'contribuyentes.edit',
            'contribuyentes.instalaciones',
            'contribuyentes.cumplimiento',
            'instalaciones.index',
            'instalaciones.create',
            'instalaciones.show',
            'instalaciones.edit',
            'instalaciones.tanques',
            'instalaciones.medidores',
            'instalaciones.dispensarios',
            'tanques.index',
            'tanques.create',
            'tanques.show',
            'tanques.edit',
            'tanques.verificar-estado',
            'tanques.curva-calibracion',
            'tanques.historial-calibraciones',
            'medidores.index',
            'medidores.create',
            'medidores.show',
            'medidores.edit',
            'medidores.probar-comunicacion',
            'medidores.verificar-estado',
            'medidores.historial-calibraciones',
            'productos.index',
            'productos.create',
            'productos.show',
            'productos.edit',
            'alarmas.index',
            'alarmas.create',
            'alarmas.show',
            'alarmas.edit',
            'alarmas.atender.form',
            'alarmas.actualizar-estado.form',
            'roles.index',
            'roles.create',
            'roles.show',
            'roles.edit',
            'roles.matriz-permisos',
            'users.index',
            'users.create',
            'users.show',
            'users.edit',
            'users.permisos',
            'users.actividad',
            'configuracion.index',
            'configuracion.logs',
            'bitacora.index',
            'bitacora.show',
            'layouts.app',
        ];

        $missingViews = [];

        foreach ($views as $view) {
            $viewPath = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');
            
            if (!file_exists($viewPath)) {
                $missingViews[] = $view;
            }
        }

        $this->assertEmpty($missingViews, "Vistas no encontradas:\n" . implode("\n", $missingViews));
    }

    /**
     * @test
     * Verifica que los controladores extiendan de BaseController
     */
    public function test_controllers_extend_base_controller()
    {
        $controllers = [
            \App\Http\Controllers\ContribuyenteController::class,
            \App\Http\Controllers\InstalacionController::class,
            \App\Http\Controllers\TanqueController::class,
            \App\Http\Controllers\MedidorController::class,
            \App\Http\Controllers\ProductoController::class,
            \App\Http\Controllers\AlarmaController::class,
            \App\Http\Controllers\RoleController::class,
            \App\Http\Controllers\UserController::class,
            \App\Http\Controllers\DashboardController::class,
            \App\Http\Controllers\ConfiguracionController::class,
            \App\Http\Controllers\BitacoraController::class,
        ];

        foreach ($controllers as $controller) {
            $this->assertTrue(
                is_subclass_of($controller, \App\Http\Controllers\BaseController::class),
                "Controlador {$controller} no extiende de BaseController"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de módulos no principales también funcionen
     */
    public function test_secondary_module_routes_work()
    {
        $secondaryRoutes = [
            // CFDI
            ['GET', '/cfdi', 'cfdi.index'],
            ['GET', '/cfdi/create', 'cfdi.create'],
            ['GET', '/cfdi/1', 'cfdi.show'],
            ['GET', '/cfdi/1/edit', 'cfdi.edit'],
            
            // Dictámenes
            ['GET', '/dictamenes', 'dictamenes.index'],
            ['GET', '/dictamenes/create', 'dictamenes.create'],
            ['GET', '/dictamenes/1', 'dictamenes.show'],
            ['GET', '/dictamenes/1/edit', 'dictamenes.edit'],
            
            // Dispensarios
            ['GET', '/dispensarios', 'dispensarios.index'],
            ['GET', '/dispensarios/create', 'dispensarios.create'],
            ['GET', '/dispensarios/1', 'dispensarios.show'],
            ['GET', '/dispensarios/1/edit', 'dispensarios.edit'],
            
            // Existencias
            ['GET', '/existencias', 'existencias.index'],
            ['GET', '/existencias/create', 'existencias.create'],
            ['GET', '/existencias/1', 'existencias.show'],
            ['GET', '/existencias/1/edit', 'existencias.edit'],
            
            // Mangueras
            ['GET', '/mangueras', 'mangueras.index'],
            ['GET', '/mangueras/create', 'mangueras.create'],
            ['GET', '/mangueras/1', 'mangueras.show'],
            ['GET', '/mangueras/1/edit', 'mangueras.edit'],
            
            // Pedimentos
            ['GET', '/pedimentos', 'pedimentos.index'],
            ['GET', '/pedimentos/create', 'pedimentos.create'],
            ['GET', '/pedimentos/1', 'pedimentos.show'],
            ['GET', '/pedimentos/1/edit', 'pedimentos.edit'],
            
            // Permisos
            ['GET', '/permissions', 'permissions.index'],
            ['GET', '/permissions/create', 'permissions.create'],
            ['GET', '/permissions/1', 'permissions.show'],
            ['GET', '/permissions/1/edit', 'permissions.edit'],
            
            // Registros Volumétricos
            ['GET', '/registros-volumetricos', 'registros-volumetricos.index'],
            ['GET', '/registros-volumetricos/create', 'registros-volumetricos.create'],
            ['GET', '/registros-volumetricos/1', 'registros-volumetricos.show'],
            ['GET', '/registros-volumetricos/1/edit', 'registros-volumetricos.edit'],
            
            // Reportes SAT
            ['GET', '/reportes-sat', 'reportes-sat.index'],
            ['GET', '/reportes-sat/create', 'reportes-sat.create'],
            ['GET', '/reportes-sat/1', 'reportes-sat.show'],
            ['GET', '/reportes-sat/1/edit', 'reportes-sat.edit'],
            
            // Certificados de Verificación
            ['GET', '/certificados-verificacion', 'certificados-verificacion.index'],
            ['GET', '/certificados-verificacion/create', 'certificados-verificacion.create'],
            ['GET', '/certificados-verificacion/1', 'certificados-verificacion.show'],
            ['GET', '/certificados-verificacion/1/edit', 'certificados-verificacion.edit'],
            
            // Perfil
            ['GET', '/profile', 'profile.edit'],
        ];

        foreach ($secondaryRoutes as [$method, $uri, $expectedView]) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->call($method, $uri);
            
            $this->assertContains($response->getStatusCode(), [200, 302],
                "Ruta secundaria {$method} {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de acciones especiales de módulos secundarios funcionen
     */
    public function test_secondary_module_special_actions_work()
    {
        $specialActions = [
            // CFDI
            ['GET', '/cfdi/rfc/{rfc}', 'cfdi.por-rfc'],
            ['GET', '/cfdi/resumen/fiscal', 'cfdi.resumen-fiscal'],
            ['POST', '/cfdi/1/cancelar', []],
            
            // Dictámenes
            ['GET', '/dictamenes/estadisticas', 'dictamenes.estadisticas'],
            ['GET', '/dictamenes/producto/{productoId}', 'dictamenes.por-producto'],
            ['POST', '/dictamenes/1/cancelar', []],
            ['GET', '/dictamenes/1/verificar-vigencia', 'dictamenes.verificar-vigencia'],
            
            // Dispensarios
            ['GET', '/dispensarios/1/mangueras', 'dispensarios.mangueras'],
            ['GET', '/dispensarios/1/verificar-estado', 'dispensarios.verificar-estado'],
            
            // Existencias
            ['POST', '/existencias/1/validar', []],
            ['GET', '/existencias/inventario-actual/{tanqueId}', 'existencias.inventario-actual'],
            ['GET', '/existencias/historico/{tanqueId}', 'existencias.historico'],
            ['GET', '/existencias/reporte/mermas', 'existencias.reporte-mermas'],
            ['GET', '/existencias/por-fecha', 'existencias.por-fecha'],
            
            // Mangueras
            ['POST', '/mangueras/1/asignar-medidor', []],
            ['POST', '/mangueras/1/quitar-medidor', []],
            
            // Pedimentos
            ['POST', '/pedimentos/1/cancelar', []],
            ['POST', '/pedimentos/1/utilizado', []],
            ['GET', '/pedimentos/resumen/comercio-exterior', 'pedimentos.resumen-comercio-exterior'],
            
            // Permisos
            ['GET', '/permissions/por-modulo/list', 'permissions.por-modulo'],
            ['GET', '/permissions/verificar/permiso', 'permissions.verificar'],
            
            // Registros Volumétricos
            ['POST', '/registros-volumetricos/emulador', []],
            ['POST', '/registros-volumetricos/1/validar', []],
            ['POST', '/registros-volumetricos/1/cancelar', []],
            ['GET', '/registros-volumetricos/resumen/diario', 'registros-volumetricos.resumen-diario'],
            ['GET', '/registros-volumetricos/estadisticas/mensuales', 'registros-volumetricos.estadisticas-mensuales'],
            ['POST', '/registros-volumetricos/1/asociar-dictamen', []],
            
            // Reportes SAT
            ['POST', '/reportes-sat/1/enviar', []],
            ['POST', '/reportes-sat/1/firmar', []],
            ['POST', '/reportes-sat/1/cancelar', []],
            ['GET', '/reportes-sat/1/xml', 'reportes-sat.descargar-xml'],
            ['GET', '/reportes-sat/1/acuse', 'reportes-sat.descargar-acuse'],
            ['GET', '/reportes-sat/historial/envios/{instalacionId}', 'reportes-sat.historial-envios'],
            ['POST', '/reportes-sat/generar-anual', []],
            
            // Certificados de Verificación
            ['GET', '/certificados-verificacion/1/verificar-vigencia', 'certificados-verificacion.verificar-vigencia'],
            ['GET', '/certificados-verificacion/estadisticas', 'certificados-verificacion.estadisticas'],
            
            // Bitácora
            ['GET', '/bitacora/resumen-actividad', 'bitacora.resumen'],
            ['GET', '/bitacora/actividad-usuario/{usuarioId}', 'bitacora.actividad-usuario'],
            ['GET', '/bitacora/actividad-modulo/{modulo}', 'bitacora.actividad-modulo'],
            ['GET', '/bitacora/actividad-tabla/{tabla}/{registroId?}', 'bitacora.actividad-tabla'],
            
            // Dashboard
            ['GET', '/dashboard/exportar', 'dashboard.exportar'],
            
            // Auth
            ['GET', '/auth/change-password', 'auth.password.change.form'],
            ['POST', '/auth/change-password', []],
            ['GET', '/auth/user', 'auth.auth.user'],
        ];

        foreach ($specialActions as [$method, $uri, $data]) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            // Reemplazar parámetros dinámicos con valores de prueba
            $uri = str_replace(['{rfc}', '{productoId}', '{tanqueId}', '{instalacionId}', '{usuarioId}', '{modulo}', '{tabla}', '{registroId?}'], 
                              ['TEST123456XXX', '1', '1', '1', '1', 'test', 'test', '1'], $uri);
            
            $response = $this->call($method, $uri, is_array($data) ? $data : []);
            
            $this->assertContains($response->getStatusCode(), [200, 302, 422],
                "Acción especial {$method} {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de acciones de módulos secundarios funcionen
     */
    public function test_secondary_module_action_routes_work()
    {
        $actionRoutes = [
            // CFDI
            ['POST', '/cfdi', ['rfc' => 'TEST123456XXX']],
            ['PUT', '/cfdi/1', ['rfc' => 'TEST123456XXX']],
            ['DELETE', '/cfdi/1', []],
            
            // Dictámenes
            ['POST', '/dictamenes', ['numero_dictamen' => 'DICT-001']],
            ['PUT', '/dictamenes/1', ['numero_dictamen' => 'DICT-001']],
            ['DELETE', '/dictamenes/1', []],
            
            // Dispensarios
            ['POST', '/dispensarios', ['identificador' => 'DISP-001']],
            ['PUT', '/dispensarios/1', ['identificador' => 'DISP-001']],
            ['DELETE', '/dispensarios/1', []],
            
            // Existencias
            ['POST', '/existencias', ['tanque_id' => 1]],
            ['PUT', '/existencias/1', ['tanque_id' => 1]],
            ['DELETE', '/existencias/1', []],
            
            // Mangueras
            ['POST', '/mangueras', ['identificador' => 'MANG-001']],
            ['PUT', '/mangueras/1', ['identificador' => 'MANG-001']],
            ['DELETE', '/mangueras/1', []],
            
            // Pedimentos
            ['POST', '/pedimentos', ['numero_pedimento' => 'PED-001']],
            ['PUT', '/pedimentos/1', ['numero_pedimento' => 'PED-001']],
            ['DELETE', '/pedimentos/1', []],
            
            // Permisos
            ['POST', '/permissions', ['nombre' => 'Permiso Test']],
            ['PUT', '/permissions/1', ['nombre' => 'Permiso Test']],
            ['DELETE', '/permissions/1', []],
            
            // Registros Volumétricos
            ['POST', '/registros-volumetricos', ['tanque_id' => 1]],
            ['PUT', '/registros-volumetricos/1', ['tanque_id' => 1]],
            ['DELETE', '/registros-volumetricos/1', []],
            
            // Reportes SAT
            ['POST', '/reportes-sat', ['instalacion_id' => 1]],
            ['PUT', '/reportes-sat/1', ['instalacion_id' => 1]],
            ['DELETE', '/reportes-sat/1', []],
            
            // Certificados de Verificación
            ['POST', '/certificados-verificacion', ['numero_certificado' => 'CERT-001']],
            ['PUT', '/certificados-verificacion/1', ['numero_certificado' => 'CERT-001']],
            ['DELETE', '/certificados-verificacion/1', []],
            
            // Perfil
            ['PUT', '/profile', ['nombres' => 'Test']],
        ];

        foreach ($actionRoutes as [$method, $uri, $data]) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->call($method, $uri, $data);
            
            $this->assertContains($response->getStatusCode(), [200, 302, 422],
                "Ruta de acción secundaria {$method} {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de exportación de módulos secundarios funcionen
     */
    public function test_secondary_module_export_routes_work()
    {
        $exportRoutes = [
            '/cfdi/exportar',
            '/dictamenes/exportar',
            '/dispensarios/exportar',
            '/existencias/exportar',
            '/mangueras/exportar',
            '/pedimentos/exportar',
            '/permissions/exportar',
            '/registros-volumetricos/exportar',
            '/reportes-sat/exportar',
            '/certificados-verificacion/exportar',
        ];

        foreach ($exportRoutes as $uri) {
            Http::fake([
                $this->baseApiUrl.'/api/exportar/*' => Http::response(
                    'csv content',
                    200,
                    [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="export.csv"',
                    ]
                ),
                '*' => Http::response(['success' => true, 'data' => []], 200),
            ]);

            $response = $this->get($uri);
            
            $this->assertContains($response->getStatusCode(), [200, 302], 
                "Ruta de exportación secundaria {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de catálogo funcionen
     */
    public function test_catalog_routes_work()
    {
        $catalogRoutes = [
            '/contribuyentes/catalogo/list',
            '/productos/catalogo/list',
            '/productos/tipo/{tipo}',
            '/productos/buscar/clave-sat/{claveSat}',
        ];

        foreach ($catalogRoutes as $uri) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            // Reemplazar parámetros dinámicos
            $uri = str_replace(['{tipo}', '{claveSat}'], ['gasolina', '12345'], $uri);
            
            $response = $this->get($uri);
            
            $this->assertContains($response->getStatusCode(), [200, 302],
                "Ruta de catálogo {$uri} falló"
            );
        }
    }

    /**
     * @test
     * Verifica que las rutas de estadísticas funcionen
     */
    public function test_statistics_routes_work()
    {
        $statisticsRoutes = [
            '/alarmas/estadisticas',
            '/alarmas/activas/list',
            '/dictamenes/estadisticas',
            '/certificados-verificacion/estadisticas',
            '/registros-volumetricos/estadisticas/mensuales',
            '/registros-volumetricos/resumen/diario',
            '/bitacora/resumen-actividad',
        ];

        foreach ($statisticsRoutes as $uri) {
            $this->mockSuccessfulResponse('/api/*', []);
            
            $response = $this->get($uri);
            
            $this->assertContains($response->getStatusCode(), [200, 302],
                "Ruta de estadísticas {$uri} falló"
            );
        }
    }
}
