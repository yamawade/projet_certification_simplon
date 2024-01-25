<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthentificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_client()
    {
        $data = [
            'nom' => 'Wade',
            'prenom' => 'Mariam',
            'email' => 'client@gmail.com',
            'password' => 'passer1234',
            'adresse' => 'km',
            'type' => 'client'
        ];

        $response = $this->json('POST', '/api/registerClient', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Utilisateur créé avec succès',
            ]);
    }
}
