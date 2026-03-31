# Corrección de Tests - control-volumetrico (Frontend)

## Problemas Identificados

### 1. Vistas no encontradas
Las vistas no existen o no están siendo mockeadas correctamente.

**Errores comunes:**
- `View [test-view] not found`
- `Undefined array key "estado_atencion"`
- `Undefined array key "id"`

**Solución:** Crear vistas de prueba o mockear las respuestas de la API correctamente.

### 2. Arrays con claves faltantes
Los datos de la API no tienen las claves esperadas por las vistas.

**Errores comunes:**
- `Undefined array key "contribuyentes_activos"`
- `Undefined array key "tiempoReal"`
- `Undefined array key "estado_atencion"`

**Solución:** Asegurar que los mocks de la API devuelvan todas las claves necesarias.

### 3. Redirecciones incorrectas
Los tests esperan códigos de estado específicos pero reciben redirecciones.

**Errores comunes:**
- Expected 200 but received 302
- Expected 200 but received 500

**Solución:** Autenticar al usuario antes de hacer las peticiones.

### 4. Problemas de autenticación
Los tests no están autenticando correctamente al usuario.

**Solución:** Usar `authenticateUser()` en el método `setUp()` de cada test.

## Archivos a Corregir

### 1. AlarmaTest.php
**Problema:** Vista no encontrada y claves faltantes en array
**Solución:** Mockear la API correctamente y crear vista de prueba

### 2. DashboardTest.php
**Problema:** Claves faltantes en array de respuesta
**Solución:** Asegurar que el mock devuelva todas las claves

### 3. UserTest.php
**Problema:** Redirecciones y autenticación
**Solución:** Autenticar usuario y verificar rutas

### 4. BaseControllerTest.php
**Problema:** Vista no encontrada
**Solución:** Crear vista de prueba o mockear

## Ejemplo de Corrección

### Antes (con error):
```php
public function test_show_displays_alarm_details()
{
    $response = $this->get('/alarmas/1');
    $response->assertStatus(200);
}
```

### Después (corregido):
```php
public function test_show_displays_alarm_details()
{
    // Autenticar usuario
    $this->authenticateUser();
    
    // Mockear respuesta de la API
    Http::fake([
        $this->baseApiUrl . '/api/alarmas/1' => Http::response([
            'success' => true,
            'data' => [
                'id' => 1,
                'numero_registro' => 'ALM-001',
                'estado_atencion' => 'PENDIENTE',
                // ... más campos
            ]
        ], 200)
    ]);
    
    $response = $this->get('/alarmas/1');
    $response->assertStatus(200);
}
```

## Claves Necesarias en Respuestas de API

### Alarma
```php
[
    'id' => 1,
    'numero_registro' => 'ALM-001',
    'fecha_hora' => '2024-01-01 10:00:00',
    'componente_tipo' => 'Tanque',
    'componente_id' => 1,
    'componente_identificador' => 'TAN-001',
    'tipo_alarma_id' => 1,
    'gravedad' => 'ALTA',
    'descripcion' => 'Descripción de la alarma',
    'estado_atencion' => 'PENDIENTE',
    'requiere_atencion_inmediata' => true,
]
```

### Dashboard
```php
[
    'contribuyentes_activos' => 10,
    'instalaciones_activas' => 5,
    'alarmas_activas' => 2,
    'volumen_total' => 10000,
    'tiempoReal' => [
        'volumen_actual' => 500,
        'flujo' => 50,
        'temperatura' => 25,
        'presion' => 100,
    ]
]
```

### Usuario
```php
[
    'id' => 1,
    'identificacion' => 'TEST123456',
    'nombres' => 'Juan',
    'apellidos' => 'Pérez',
    'email' => 'juan@example.com',
    'full_name' => 'Juan Pérez',
    'roles' => ['Operador'],
    'permisos' => ['ver', 'crear'],
]
```

## Vistas de Prueba Necesarias

### resources/views/test-view.blade.php
```html
<!DOCTYPE html>
<html>
<head>
    <title>Test View</title>
</head>
<body>
    <h1>Test View</h1>
    <p>{{ $message ?? 'Default message' }}</p>
</body>
</html>
```

## Configuración de Tests

### TestCase.php
Asegurar que el TestCase base tenga los métodos necesarios:

```php
protected function authenticateUser(): void
{
    Session::put('api_token', $this->testApiToken);
    Session::put('user_id', $this->testUser['id']);
    Session::put('user_name', $this->testUser['full_name']);
    Session::put('user_email', $this->testUser['email']);
    Session::put('user_roles', $this->testUser['roles']);
}
```

## Comandos para Ejecutar Tests

```bash
# Ejecutar todos los tests
php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml

# Ejecutar tests específicos
php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml --filter AlarmaTest

# Ejecutar con verbose
php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml --verbose
```

## Pasos para Corregir

1. **Revisar cada test** y identificar los errores
2. **Crear vistas de prueba** si es necesario
3. **Mockear respuestas de API** con todas las claves
4. **Autenticar usuario** en cada test
5. **Verificar rutas** y códigos de estado
6. **Ejecutar tests** para verificar correcciones