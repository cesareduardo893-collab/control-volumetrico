<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthTest extends TestCase
{
    /** @test */
    public function test_login_form_is_displayed()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function test_successful_login()
    {
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/login' => Http::response([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'token' => $this->testApiToken,
                    'user' => $this->testUser
                ]
            ], 200)
        ]);

        $response = $this->post('/login', $loginData);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Bienvenido Test User');
        $this->assertEquals($this->testApiToken, Session::get('api_token'));
    }

    /** @test */
    public function test_login_fails_with_invalid_credentials()
    {
        $loginData = [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/login' => Http::response([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401)
        ]);

        $response = $this->post('/login', $loginData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Credenciales incorrectas');
        $this->assertNull(Session::get('api_token'));
    }

    /** @test */
    public function test_login_validation_errors()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function test_successful_logout()
    {
        $this->authenticateUser();

        Http::fake([
            $this->baseApiUrl . '/api/logout' => Http::response([
                'success' => true,
                'message' => 'Logout exitoso'
            ], 200)
        ]);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Sesión cerrada exitosamente');
        $this->assertNull(Session::get('api_token'));
    }

    /** @test */
    public function test_successful_registration()
    {
        $registrationData = [
            'identificacion' => 'TEST123456',
            'nombres' => 'Test',
            'apellidos' => 'User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefono' => '1234567890',
            'direccion' => 'Test Address'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/register' => Http::response([
                'success' => true,
                'message' => 'Registro exitoso',
                'data' => ['id' => 2]
            ], 201)
        ]);

        $response = $this->post('/register', $registrationData);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Registro exitoso. Por favor inicia sesión.');
    }

    /** @test */
    public function test_registration_validation_errors()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors([
            'identificacion', 'nombres', 'apellidos', 'email', 'password'
        ]);
    }

    /** @test */
    public function test_password_change_successful()
    {
        $this->authenticateUser();

        $passwordData = [
            'password_actual' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/users/1/cambiar-password' => Http::response([
                'success' => true,
                'message' => 'Contraseña cambiada exitosamente'
            ], 200)
        ]);

        $response = $this->post('/auth/change-password', $passwordData);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Contraseña cambiada exitosamente');
    }

    /** @test */
    public function test_password_change_fails_with_wrong_current_password()
    {
        $this->authenticateUser();

        $passwordData = [
            'password_actual' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        Http::fake([
            $this->baseApiUrl . '/api/users/1/cambiar-password' => Http::response([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta'
            ], 422)
        ]);

        $response = $this->post('/auth/change-password', $passwordData);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'La contraseña actual es incorrecta');
    }

    /** @test */
    public function test_user_profile_is_displayed()
    {
        $this->authenticateUser();

        $response = $this->get('/auth/user');

        $response->assertStatus(200);
        $response->assertViewIs('auth.profile');
        $response->assertViewHas('user');
    }

    /** @test */
    public function test_authenticated_user_can_access_dashboard()
    {
        $this->authenticateUser();

        Http::fake([
            $this->baseApiUrl . '/api/dashboard/resumen' => Http::response([
                'success' => true,
                'data' => [
                    'contribuyentes_activos' => 10,
                    'instalaciones_activas' => 5,
                    'alarmas_activas' => 2,
                    'volumen_total' => 10000
                ]
            ], 200),
            $this->baseApiUrl . '/api/dashboard/tiempo-real' => Http::response([
                'success' => true,
                'data' => [
                    'volumen_actual' => 500,
                    'flujo' => 50,
                    'temperatura' => 25,
                    'presion' => 100
                ]
            ], 200)
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    /** @test */
    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}