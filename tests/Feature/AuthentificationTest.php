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
            'email' => 'mane@gmail.com',
            'password' => 'passer1234',
            'adresse' => 'keur Massar',
            'genre' => 'femme',
            'numero_tel' => '778009876',
            'date_naiss' => '2002-05-12'
        ];
    
        $response = $this->json('POST', '/api/registerClient', $data);
    
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Utilisateur créer avec succes',
                 ]);
    }
}
