<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_permissions_list()
    {
        $permisos = [
            [
                'id' => 1,
                'name' => 'Crear Usuario',
                'slug' => 'crear-usuario',
                'modulo' => 'Usuarios',
                'activo' => true,
            ],
            [
                'id' => 2,
                'name' => 'Editar Usuario',
                'slug' => 'editar-usuario',
                'modulo' => 'Usuarios',
                'activo' => true,
            ],
        ];

        $this->mockPaginatedResponse('/api/permissions', $permisos, 2);

        $response = $this->get('/permissions');

        $response->assertStatus(200);
        $response->assertViewIs('permissions.index');
        $response->assertViewHas('permisos');
    }

    /** @test */
    public function test_show_displays_permission_details()
    {
        $permission = [
            'id' => 1,
            'name' => 'Crear Usuario',
            'slug' => 'crear-usuario',
            'description' => 'Permite crear usuarios',
            'modulo' => 'Usuarios',
            'activo' => true,
        ];

        $this->mockSuccessfulResponse('/api/permissions/1', $permission);

        $response = $this->get('/permissions/1');

        $response->assertStatus(200);
        $response->assertViewIs('permissions.show');
        $response->assertViewHas('permiso', $permission);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $permission = [
            'id' => 1,
            'name' => 'Crear Usuario',
            'slug' => 'crear-usuario',
            'description' => 'Permite crear usuarios',
            'modulo' => 'Usuarios',
            'activo' => true,
        ];

        $this->mockSuccessfulResponse('/api/permissions/1', $permission);

        $response = $this->get('/permissions/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('permissions.edit');
        $response->assertViewHas('permiso', $permission);
    }

    /** @test */
    public function test_filter_permissions_by_modulo()
    {
        $permisos = [
            ['id' => 1, 'name' => 'Crear Usuario', 'slug' => 'crear-usuario', 'modulo' => 'Usuarios', 'activo' => true],
        ];

        $this->mockSuccessfulResponse('/api/permissions', ['data' => $permisos]);

        $response = $this->get('/permissions?modulo=Usuarios');

        $response->assertStatus(200);
        $response->assertViewHas('permisos');

        $permisos = $response->viewData('permisos');
        $this->assertCount(1, $permisos);
        $this->assertEquals('Usuarios', $permisos[0]['modulo']);
    }

    /** @test */
    public function test_exportar_downloads_permissions_file()
    {
        Http::fake([
            $this->baseApiUrl.'/api/permissions/exportar*' => Http::response(
                'name,slug,modulo\nCrear Usuario,crear-usuario,Usuarios',
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="permissions.csv"',
                ]
            ),
        ]);

        $response = $this->get('/permissions/exportar');

        $response->assertStatus(200);
        $this->assertTrue(
            str_starts_with($response->headers->get('Content-Type'), 'text/csv'),
            'Content-Type header should start with text/csv'
        );
    }

    /** @test */
    public function test_por_modulo_displays_permissions_by_module()
    {
        $permisosPorModulo = [
            'Usuarios' => [
                ['id' => 1, 'name' => 'Crear Usuario', 'slug' => 'crear-usuario'],
                ['id' => 2, 'name' => 'Editar Usuario', 'slug' => 'editar-usuario'],
            ],
            'Alarmas' => [
                ['id' => 3, 'name' => 'Ver Alarmas', 'slug' => 'ver-alarmas'],
            ],
        ];

        $this->mockSuccessfulResponse('/api/permissions/por-modulo', $permisosPorModulo);

        $response = $this->get('/permissions/por-modulo');

        $response->assertStatus(200);
        $response->assertViewIs('permissions.por-modulo');
        $response->assertViewHas('permisosPorModulo', $permisosPorModulo);
    }

    /** @test */
    public function test_verificar_permission_returns_verification_result()
    {
        $verificationData = [
            'user_id' => 1,
            'permiso_slug' => 'crear-usuario',
        ];

        $resultado = [
            'tiene_permiso' => true,
            'usuario' => 'Test User',
            'permiso' => 'Crear Usuario',
        ];

        $this->mockSuccessfulResponse('/api/permissions/verificar', $resultado);

        $response = $this->getJson('/api/permissions/verificar?'.http_build_query($verificationData));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.tiene_permiso', true);
    }

    /** @test */
    public function test_verificar_permission_returns_false_when_no_permission()
    {
        $verificationData = [
            'user_id' => 1,
            'permiso_slug' => 'permiso-inexistente',
        ];

        $resultado = [
            'tiene_permiso' => false,
            'usuario' => 'Test User',
            'permiso' => 'Permiso Inexistente',
        ];

        $this->mockSuccessfulResponse('/api/permissions/verificar', $resultado);

        $response = $this->getJson('/api/permissions/verificar?'.http_build_query($verificationData));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonPath('data.tiene_permiso', false);
    }

    /** @test */
    public function test_create_form_displays_correctly()
    {
        $response = $this->get('/permissions/create');

        $response->assertStatus(200);
        $response->assertViewIs('permissions.create');
    }

    /** @test */
    public function test_store_creates_permission_successfully()
    {
        $permissionData = [
            'name' => 'Crear Usuario',
            'slug' => 'crear-usuario',
            'description' => 'Permite crear usuarios',
            'modulo' => 'Usuarios',
            'activo' => true,
        ];

        $createdPermission = array_merge($permissionData, ['id' => 1]);

        $this->mockSuccessfulResponse('/api/permissions', $createdPermission, 'Permiso creado exitosamente', 201);

        $response = $this->post('/permissions', $permissionData);

        $response->assertRedirect('/permissions');
        $response->assertSessionHas('success', 'Permiso creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors()
    {
        $invalidData = [
            'name' => '',
            'slug' => '',
        ];

        $this->mockValidationErrorResponse('/api/permissions', [
            'name' => ['El campo name es obligatorio'],
            'slug' => ['El campo slug es obligatorio'],
        ]);

        $response = $this->post('/permissions', $invalidData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'slug']);
    }

    /** @test */
    public function test_update_modifies_permission_successfully()
    {
        $updateData = [
            'name' => 'Crear Usuario Actualizado',
            'description' => 'Permite crear usuarios con nuevos permisos',
        ];

        $this->mockSuccessfulResponse('/api/permissions/1', [], 'Permiso actualizado exitosamente');

        $response = $this->put('/permissions/1', $updateData);

        $response->assertRedirect('/permissions/1');
        $response->assertSessionHas('success', 'Permiso actualizado exitosamente');
    }

    /** @test */
    public function test_destroy_deletes_permission_successfully()
    {
        $this->mockSuccessfulResponse('/api/permissions/1', [], 'Permiso eliminado exitosamente');

        $response = $this->delete('/permissions/1');

        $response->assertRedirect('/permissions');
        $response->assertSessionHas('success', 'Permiso eliminado exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_permission_in_use()
    {
        $this->mockErrorResponse('/api/permissions/1', 'No se puede eliminar el permiso', 409);

        $response = $this->delete('/permissions/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar el permiso');
    }
}