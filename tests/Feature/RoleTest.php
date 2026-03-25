<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RoleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_roles_list()
    {
        $roles = [
            [
                'id' => 1,
                'nombre' => 'Administrador',
                'nivel_jerarquico' => 100,
                'activo' => true
            ],
            [
                'id' => 2,
                'nombre' => 'Operador',
                'nivel_jerarquico' => 50,
                'activo' => true
            ]
        ];

        $this->mockPaginatedResponse('/api/roles', $roles, 2);

        $response = $this->get('/roles');

        $response->assertStatus(200);
        $response->assertViewIs('roles.index');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function test_create_form_displays_correctly_with_permissions()
    {
        $permisos = [
            ['id' => 1, 'name' => 'Crear Usuario', 'modulo' => 'Usuarios'],
            ['id' => 2, 'name' => 'Editar Usuario', 'modulo' => 'Usuarios'],
            ['id' => 3, 'name' => 'Ver Alarmas', 'modulo' => 'Alarmas']
        ];

        $this->mockSuccessfulResponse('/api/permissions?per_page=500&activo=true', ['data' => $permisos]);

        $response = $this->get('/roles/create');

        $response->assertStatus(200);
        $response->assertViewIs('roles.create');
        $response->assertViewHas('permisos', $permisos);
    }

    /** @test */
    public function test_store_creates_role_successfully()
    {
        $roleData = [
            'nombre' => 'Supervisor',
            'descripcion' => 'Supervisor de operaciones',
            'nivel_jerarquico' => 75,
            'es_administrador' => false,
            'permisos' => [1, 2, 3]
        ];

        $createdRole = [
            'id' => 3,
            'nombre' => 'Supervisor',
            'nivel_jerarquico' => 75
        ];

        $this->mockSuccessfulResponse('/api/roles', $createdRole, 'Rol creado exitosamente', 201);

        $response = $this->post('/roles', $roleData);

        $response->assertRedirect('/roles');
        $response->assertSessionHas('success', 'Rol creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors_for_duplicate_name()
    {
        $roleData = [
            'nombre' => 'Administrador',
            'nivel_jerarquico' => 100
        ];

        $this->mockValidationErrorResponse('/api/roles', [
            'nombre' => ['El nombre ya está registrado']
        ]);

        $response = $this->post('/roles', $roleData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['nombre']);
    }

    /** @test */
    public function test_show_displays_role_details()
    {
        $role = [
            'id' => 1,
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso total al sistema',
            'nivel_jerarquico' => 100,
            'es_administrador' => true,
            'activo' => true,
            'permissions' => [
                ['id' => 1, 'name' => 'Crear Usuario', 'modulo' => 'Usuarios'],
                ['id' => 2, 'name' => 'Editar Usuario', 'modulo' => 'Usuarios']
            ]
        ];

        $this->mockSuccessfulResponse('/api/roles/1', $role);

        $response = $this->get('/roles/1');

        $response->assertStatus(200);
        $response->assertViewIs('roles.show');
        $response->assertViewHas('role', $role);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $role = [
            'id' => 1,
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso total',
            'nivel_jerarquico' => 100,
            'permissions' => [
                ['id' => 1, 'name' => 'Crear Usuario']
            ]
        ];

        $permisos = [
            ['id' => 1, 'name' => 'Crear Usuario', 'modulo' => 'Usuarios'],
            ['id' => 2, 'name' => 'Editar Usuario', 'modulo' => 'Usuarios']
        ];

        $this->mockSuccessfulResponse('/api/roles/1', $role);
        $this->mockSuccessfulResponse('/api/permissions?per_page=500&activo=true', ['data' => $permisos]);

        $response = $this->get('/roles/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('roles.edit');
        $response->assertViewHas('role', $role);
        $response->assertViewHas('permisos', $permisos);
        $response->assertViewHas('permisosActuales', [1]);
    }

    /** @test */
    public function test_update_modifies_role_successfully()
    {
        $updateData = [
            'descripcion' => 'Administrador con permisos limitados',
            'nivel_jerarquico' => 90,
            'permisos' => [1, 2]
        ];

        $this->mockSuccessfulResponse('/api/roles/1', [], 'Rol actualizado exitosamente');

        $response = $this->put('/roles/1', $updateData);

        $response->assertRedirect('/roles/1');
        $response->assertSessionHas('success', 'Rol actualizado exitosamente');
    }

    /** @test */
    public function test_asignar_permisos_assigns_permissions_to_role()
    {
        $permisosData = [
            'permisos' => [1, 2, 3, 4]
        ];

        $this->mockSuccessfulResponse('/api/roles/1/asignar-permisos', [], 'Permisos asignados exitosamente');

        $response = $this->post('/roles/1/asignar-permisos', $permisosData);

        $response->assertRedirect('/roles/1');
        $response->assertSessionHas('success', 'Permisos asignados exitosamente');
    }

    /** @test */
    public function test_clonar_clones_role_successfully()
    {
        $cloneData = [
            'nombre' => 'Administrador Clonado',
            'incluir_permisos' => true
        ];

        $clonedRole = [
            'id' => 5,
            'nombre' => 'Administrador Clonado'
        ];

        $this->mockSuccessfulResponse('/api/roles/1/clonar', $clonedRole, 'Rol clonado exitosamente');

        $response = $this->post('/roles/1/clonar', $cloneData);

        $response->assertRedirect('/roles/5');
        $response->assertSessionHas('success', 'Rol clonado exitosamente');
    }

    /** @test */
    public function test_matriz_permisos_displays_permission_matrix()
    {
        $matriz = [
            'roles' => [
                ['id' => 1, 'nombre' => 'Administrador'],
                ['id' => 2, 'nombre' => 'Operador']
            ],
            'permisos' => [
                ['id' => 1, 'name' => 'Crear Usuario', 'modulo' => 'Usuarios'],
                ['id' => 2, 'name' => 'Ver Alarmas', 'modulo' => 'Alarmas']
            ],
            'matriz' => [
                1 => [1 => true, 2 => true],
                2 => [1 => false, 2 => true]
            ]
        ];

        $this->mockSuccessfulResponse('/api/roles/matriz-permisos', $matriz);

        $response = $this->get('/roles/matriz/permisos');

        $response->assertStatus(200);
        $response->assertViewIs('roles.matriz-permisos');
        $response->assertViewHas('roles', $matriz['roles']);
        $response->assertViewHas('permisos', $matriz['permisos']);
        $response->assertViewHas('matriz', $matriz['matriz']);
    }

    /** @test */
    public function test_destroy_deletes_role_successfully()
    {
        $this->mockSuccessfulResponse('/api/roles/1', [], 'Rol eliminado exitosamente');

        $response = $this->delete('/roles/1');

        $response->assertRedirect('/roles');
        $response->assertSessionHas('success', 'Rol eliminado exitosamente');
    }

    /** @test */
    public function test_destroy_fails_if_role_has_users()
    {
        $this->mockErrorResponse('/api/roles/1', 'No se puede eliminar el rol', 409);

        $response = $this->delete('/roles/1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No se puede eliminar el rol');
    }

    /** @test */
    public function test_filter_roles_by_nivel()
    {
        $roles = [
            ['id' => 1, 'nombre' => 'Administrador', 'nivel_jerarquico' => 100]
        ];

        $this->mockSuccessfulResponse('/api/roles?nivel_minimo=80', ['data' => $roles]);

        $response = $this->get('/roles?nivel_minimo=80');

        $response->assertStatus(200);
        $response->assertViewHas('roles');
        
        $roles = $response->viewData('roles');
        $this->assertCount(1, $roles);
        $this->assertGreaterThanOrEqual(80, $roles[0]['nivel_jerarquico']);
    }

    /** @test */
    public function test_role_agrupacion_permisos_works_correctly()
    {
        $permisos = [
            ['id' => 1, 'name' => 'Crear Usuario', 'modulo' => 'Usuarios'],
            ['id' => 2, 'name' => 'Editar Usuario', 'modulo' => 'Usuarios'],
            ['id' => 3, 'name' => 'Ver Alarmas', 'modulo' => 'Alarmas']
        ];

        $this->mockSuccessfulResponse('/api/permissions?per_page=500&activo=true', ['data' => $permisos]);

        $response = $this->get('/roles/create');

        $response->assertStatus(200);
        
        $modulos = $response->viewData('modulos');
        $this->assertCount(2, $modulos);
        
        $modulosArray = $modulos->toArray();
        $this->assertEquals('Usuarios', $modulosArray[0]['modulo']);
        $this->assertCount(2, $modulosArray[0]['permisos']);
        $this->assertEquals('Alarmas', $modulosArray[1]['modulo']);
        $this->assertCount(1, $modulosArray[1]['permisos']);
    }
}