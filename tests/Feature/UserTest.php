<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticateUser();
    }

    /** @test */
    public function test_index_displays_users_list()
    {
        $users = [
            [
                'id' => 1,
                'identificacion' => 'TEST001',
                'nombres' => 'Juan',
                'apellidos' => 'Pérez',
                'email' => 'juan@test.com',
                'activo' => true
            ],
            [
                'id' => 2,
                'identificacion' => 'TEST002',
                'nombres' => 'María',
                'apellidos' => 'López',
                'email' => 'maria@test.com',
                'activo' => true
            ]
        ];

        $this->mockPaginatedResponse('/api/users', $users, 2);

        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
        $response->assertViewHas('users');
    }

    /** @test */
    public function test_create_form_displays_correctly_with_roles()
    {
        $roles = [
            ['id' => 1, 'nombre' => 'Administrador'],
            ['id' => 2, 'nombre' => 'Operador'],
            ['id' => 3, 'nombre' => 'Consultor']
        ];

        $this->mockSuccessfulResponse('/api/roles?activo=true', $roles);

        $response = $this->get('/users/create');

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
        $response->assertViewHas('roles', $roles);
    }

    /** @test */
    public function test_store_creates_user_successfully()
    {
        $userData = [
            'identificacion' => 'TEST001',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'juan@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefono' => '1234567890',
            'direccion' => 'Calle Principal 123',
            'roles' => [1, 2]
        ];

        $createdUser = [
            'id' => 3,
            'identificacion' => 'TEST001',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'juan@test.com'
        ];

        $this->mockSuccessfulResponse('/api/users', $createdUser, 'Usuario creado exitosamente', 201);

        $response = $this->post('/users', $userData);

        $response->assertRedirect('/users');
        $response->assertSessionHas('success', 'Usuario creado exitosamente');
    }

    /** @test */
    public function test_store_validation_errors_for_duplicate_email()
    {
        $userData = [
            'identificacion' => 'TEST001',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'existing@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => [1]
        ];

        $this->mockValidationErrorResponse('/api/users', [
            'email' => ['El email ya está registrado']
        ]);

        $response = $this->post('/users', $userData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_show_displays_user_details()
    {
        $user = [
            'id' => 1,
            'identificacion' => 'TEST001',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'juan@test.com',
            'telefono' => '1234567890',
            'direccion' => 'Calle Principal 123',
            'activo' => true,
            'roles' => [
                ['id' => 1, 'nombre' => 'Administrador']
            ]
        ];

        $this->mockSuccessfulResponse('/api/users/1', $user);

        $response = $this->get('/users/1');

        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function test_edit_form_displays_correctly()
    {
        $user = [
            'id' => 1,
            'identificacion' => 'TEST001',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'juan@test.com'
        ];

        $roles = [
            ['id' => 1, 'nombre' => 'Administrador'],
            ['id' => 2, 'nombre' => 'Operador']
        ];

        $this->mockSuccessfulResponse('/api/users/1', $user);
        $this->mockSuccessfulResponse('/api/roles?activo=true', $roles);

        $response = $this->get('/users/1/edit');

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('roles', $roles);
    }

    /** @test */
    public function test_update_modifies_user_successfully()
    {
        $updateData = [
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez Gómez',
            'telefono' => '9876543210',
            'activo' => true
        ];

        $this->mockSuccessfulResponse('/api/users/1', [], 'Usuario actualizado exitosamente');

        $response = $this->put('/users/1', $updateData);

        $response->assertRedirect('/users/1');
        $response->assertSessionHas('success', 'Usuario actualizado exitosamente');
    }

    /** @test */
    public function test_bloquear_blocks_user_successfully()
    {
        $blockData = [
            'motivo' => 'Actividad sospechosa',
            'minutos_bloqueo' => 30
        ];

        $this->mockSuccessfulResponse('/api/users/1/bloquear', [], 'Usuario bloqueado exitosamente');

        $response = $this->post('/users/1/bloquear', $blockData);

        $response->assertRedirect('/users/1');
        $response->assertSessionHas('success', 'Usuario bloqueado exitosamente');
    }

    /** @test */
    public function test_desbloquear_unblocks_user_successfully()
    {
        $unblockData = [
            'motivo' => 'Revisión completada'
        ];

        $this->mockSuccessfulResponse('/api/users/1/desbloquear', [], 'Usuario desbloqueado exitosamente');

        $response = $this->post('/users/1/desbloquear', $unblockData);

        $response->assertRedirect('/users/1');
        $response->assertSessionHas('success', 'Usuario desbloqueado exitosamente');
    }

    /** @test */
    public function test_asignar_rol_assigns_role_to_user()
    {
        $roleData = [
            'rol_id' => 2
        ];

        $this->mockSuccessfulResponse('/api/users/1/asignar-rol', [], 'Rol asignado exitosamente');

        $response = $this->post('/users/1/asignar-rol', $roleData);

        $response->assertRedirect('/users/1');
        $response->assertSessionHas('success', 'Rol asignado exitosamente');
    }

    /** @test */
    public function test_asignar_rol_fails_if_role_already_assigned()
    {
        $roleData = [
            'rol_id' => 1
        ];

        $this->mockErrorResponse('/api/users/1/asignar-rol', 'El usuario ya tiene este rol asignado', 409);

        $response = $this->post('/users/1/asignar-rol', $roleData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'El usuario ya tiene este rol asignado');
    }

    /** @test */
    public function test_quitar_rol_removes_role_from_user()
    {
        $roleData = [
            'rol_id' => 1
        ];

        $this->mockSuccessfulResponse('/api/users/1/quitar-rol', [], 'Rol revocado exitosamente');

        $response = $this->post('/users/1/quitar-rol', $roleData);

        $response->assertRedirect('/users/1');
        $response->assertSessionHas('success', 'Rol revocado exitosamente');
    }

    /** @test */
    public function test_permisos_displays_user_permissions()
    {
        $permisos = [
            'modulos' => [
                'Alarmas' => ['crear', 'editar', 'ver'],
                'Contribuyentes' => ['ver', 'exportar']
            ]
        ];

        $this->mockSuccessfulResponse('/api/users/1/permisos', $permisos);

        $response = $this->get('/users/1/permisos');

        $response->assertStatus(200);
        $response->assertViewIs('users.permisos');
        $response->assertViewHas('permisos', $permisos);
    }

    /** @test */
    public function test_actividad_displays_user_activity()
    {
        $activity = [
            'total_actividades' => 50,
            'actividades' => [
                [
                    'fecha' => '2024-01-15',
                    'tipo_evento' => 'LOGIN',
                    'descripcion' => 'Inicio de sesión',
                    'ip_address' => '192.168.1.1'
                ],
                [
                    'fecha' => '2024-01-14',
                    'tipo_evento' => 'ALARMA_ATENDIDA',
                    'descripcion' => 'Atendió alarma #123',
                    'ip_address' => '192.168.1.1'
                ]
            ]
        ];

        $this->mockSuccessfulResponse('/api/users/1/actividad', $activity);

        $response = $this->get('/users/1/actividad');

        $response->assertStatus(200);
        $response->assertViewIs('users.actividad');
        $response->assertViewHas('actividad', $activity);
    }

    /** @test */
    public function test_destroy_deletes_user_successfully()
    {
        $this->mockSuccessfulResponse('/api/users/1', [], 'Usuario eliminado exitosamente');

        $response = $this->delete('/users/1');

        $response->assertRedirect('/users');
        $response->assertSessionHas('success', 'Usuario eliminado exitosamente');
    }

    /** @test */
    public function test_search_users_by_email()
    {
        $users = [
            [
                'id' => 1,
                'email' => 'search@test.com',
                'nombres' => 'Search',
                'apellidos' => 'User'
            ]
        ];

        $this->mockSuccessfulResponse('/api/users?email=search@test.com', ['data' => $users]);

        $response = $this->get('/users?email=search@test.com');

        $response->assertStatus(200);
        $response->assertViewHas('users');
        
        $users = $response->viewData('users');
        $this->assertCount(1, $users);
        $this->assertEquals('search@test.com', $users[0]['email']);
    }
}