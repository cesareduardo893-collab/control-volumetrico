<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    /** @test */
    public function test_profile_page_is_displayed()
    {
        $this->authenticateUser();

        $this->mockSuccessfulResponse('/api/user', [
            'id' => 1,
            'nombres' => 'Test',
            'apellidos' => 'User',
            'email' => 'test@example.com',
            'roles' => [],
            'permissions' => [],
        ]);

        $response = $this->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    }

    /** @test */
    public function test_profile_update_successful()
    {
        $this->authenticateUser();

        $updateData = [
            'nombres' => 'Updated',
            'apellidos' => 'User',
            'email' => 'updated@example.com',
            'telefono' => '9876543210',
        ];

        $this->mockSuccessfulResponse('/api/user', $updateData, 'Perfil actualizado exitosamente');

        $response = $this->put('/profile', $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Perfil actualizado correctamente');
    }

    /** @test */
    public function test_profile_update_validation_errors()
    {
        $this->authenticateUser();

        $this->mockSuccessfulResponse('/api/user', []);

        $response = $this->put('/profile', [
            'email' => 'invalid',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['nombres', 'apellidos', 'email']);
    }
}
