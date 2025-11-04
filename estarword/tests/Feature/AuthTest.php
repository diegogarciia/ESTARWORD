<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_un_usuario_se_puede_registrar_correctamente(): void
    {
        $userData = [
            'name' => 'Usuario de Prueba',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'rol' => 'gestor', 
        ];

        $response = $this->postJson('/api/registro', $userData);

        $response
            ->assertStatus(201) 
            ->assertJson(['message' => 'Usuario registrado con éxito.']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'rol' => 'gestor'
        ]);
    }

    public function test_el_registro_falla_sin_confirmacion_de_password(): void
    {
        $userData = [
            'name' => 'Usuario de Prueba',
            'email' => 'test@example.com',
            'password' => 'password123',
            'rol' => 'gestor',
        ];

        $response = $this->postJson('/api/registro', $userData);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    public function test_un_usuario_se_puede_loguear_y_recibe_un_token_con_su_rol(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@starwars.com',
            'password' => Hash::make('123456'),
            'rol' => 'administrador',
        ]);

        $loginData = [
            'email' => 'admin@starwars.com',
            'password' => '123456',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response
            ->assertStatus(200) 
            ->assertJsonStructure([ 
                'success',
                'data' => [ 'id', 'name', 'rol', 'token' ],
                'message'
            ])
        
            ->assertJsonPath('data.rol', 'administrador');
    }

    public function test_el_login_falla_con_password_incorrecta(): void
    {
        User::factory()->create([
            'email' => 'admin@starwars.com',
            'password' => Hash::make('123456'),
        ]);

        $loginData = [
            'email' => 'admin@starwars.com',
            'password' => 'password-incorrecto', 
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401);
    }

    public function test_un_usuario_autenticado_puede_hacer_logout(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user); 

        $response = $this->postJson('/api/logout');

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Sesión cerrada correctamente.']);
    }

}
