<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthentificationTest extends TestCase
{
    use RefreshDatabase;

    // public function test_register_client()
    // {
    //     $data = [
    //         'nom' => 'Wade',
    //         'prenom' => 'Mariam',
    //         'email' => 'mane@gmail.com',
    //         'password' => 'passer1234',
    //         'adresse' => 'keur Massar',
    //         'genre' => 'femme',
    //         'numero_tel' => '778009876',
    //         'date_naiss' => '2002-05-12'
    //     ];
    
    //     $response = $this->json('POST', '/api/registerClient', $data);
    
    //     $response->assertStatus(200)
    //              ->assertJson([
    //                  'status' => 200,
    //                  'message' => 'Utilisateur créer avec succes',
    //              ]);
    // }

    public function test_register_commercant(){
        $data = [
            'nom' => 'test',
            'prenom' => 'test',
            'email' => 'commercant@gmail.com',
            'password' => 'passer1234',
            'ninea' => '123456789',
            'adresse' => 'keur Massar',
            'nin' => '14567896789',
            'numero_tel' => '778009876',
            'genre' => 'homme',
            'date_naiss' => '1990-01-31'
        ];

        $response = $this->json('POST', '/api/registerCommercant', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Utilisateur créer avec succes',
            ]);
    }

}
