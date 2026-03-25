<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    /** @test */
    public function test_profile_page_is_displayed()
    {
        $this->authenticateUser();

        $response = $this->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.index');
    }

    /** @test */
    public function test_profile_update_successful()
    {
        $this->authenticateUser();

        $updateData = [
            'nombres' => 'Updated',
            'apellidos' => 'User',
            'email' => 'updated@example.com',
            'telefono' => '9876543210'
        ];

        $this->mockSuccessfulResponse('/api/users/1', $updateData, 'Perfil actualizado exitosamente');

        $response = $this->put('/profile', $updateData);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success', 'Perfil actualizado exitosamente');
    }

    /** @test */
    public function test_profile_update_validation_errors()
    {
        $this->authenticateUser();

        $this->mockValidationErrorResponse('/api/users/1', [
            'email' => ['El correo electrónico ya está en uso.']
        ]);

        $response = $this->put('/profile', [
            'email' => 'invalid'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }
}