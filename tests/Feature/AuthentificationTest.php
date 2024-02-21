<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'date_naiss' => '2002-05-12',
            'etat_compte' => 'actif'
        ];
    
        $response = $this->json('POST', '/api/registerClient', $data);
    
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Utilisateur créer avec succes',
                 ]);
    }

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
            'date_naiss' => '1990-01-31',
            'etat_compte' => 'actif'
        ];

        $response = $this->json('POST', '/api/registerCommercant', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Utilisateur créer avec succes',
            ]);
    }

    public function test_registerLivreur(){

        $admin = User::factory()->create(['type' => 'admin']);

        $data = [
            'nom' => 'LivreurTest',
            'prenom' => 'LivreurPrenom',
            'email' => 'livreur@gmail.com',
            'password' => 'passer1234',
            'genre' => 'homme',
            'matricule'=> '123456789',
            'statut'=> 'disponible',
            'adresse' => 'keur Massar',
            'etat_compte' => 'actif',
            'numero_tel' => '778009876'
        ];

        $response = $this->actingAs($admin)->json('POST', '/api/registerLivreur', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Utilisateur créer avec succes',
            ]);
    }

    public function test_user_login_commercant(){
        $user = User::factory()->create(['type' => 'commercant']);
        $credentials = [
            'email' => $user->email,
            'password' => 'passer1234',
        ];

        $response = $this->json('POST', '/api/login', $credentials);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Salut Commercant',
                'user' => $user->toArray(),
                'authorization' => [
                    'token' => $response['authorization']['token'],
                    'type' => 'bearer',
                ]
            ]);
    }

    public function test_user_login_client(){
        $user = User::factory()->create(['type' => 'client']);
        $credentials = [
            'email' => $user->email,
            'password' => 'passer1234',
        ];

        $response = $this->json('POST', '/api/login', $credentials);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Salut Client',
                'user' => $user->toArray(),
                'authorization' => [
                    'token' => $response['authorization']['token'],
                    'type' => 'bearer',
                ]
            ]);
    }

    public function test_user_login_livreur(){
        $user = User::factory()->create(['type' => 'livreur']);
        $credentials = [
            'email' => $user->email,
            'password' => 'passer1234',
        ];

        $response = $this->json('POST', '/api/login', $credentials);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Salut livreur',
                'user' => $user->toArray(),
                'authorization' => [
                    'token' => $response['authorization']['token'],
                    'type' => 'bearer',
                ]
            ]);
    }

    public function test_user_login_admin(){
        $user = User::factory()->create(['type' => 'admin']);
        $credentials = [
            'email' => $user->email,
            'password' => 'passer1234',
        ];

        $response = $this->json('POST', '/api/login', $credentials);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Salut Admin',
                'user' => $user->toArray(),
                'authorization' => [
                    'token' => $response['authorization']['token'],
                    'type' => 'bearer',
                ]
            ]);
    }
}
