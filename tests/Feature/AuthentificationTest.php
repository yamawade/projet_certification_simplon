<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Commercant;
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
            'date_naiss' => '2002-05-12'
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
            'date_naiss' => '1990-01-31'
        ];

        $response = $this->json('POST', '/api/registerCommercant', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Utilisateur créer avec succes',
            ]);
    }

    // public function test_user_login_commercant(){
    //     $user = Commercant::factory()->create(['type' => 'commercant']);
    //     $credentials = [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ];

    //     $response = $this->json('POST', '/api/login', $credentials);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'status' => 200,
    //             'message' => 'Salut Commercant',
    //             'user' => $user->toArray(),
    //             'authorization' => [
    //                 'token' => $response['authorization']['token'],
    //                 'type' => 'bearer',
    //             ]
    //         ]);
    // }

    public function test_user_login_client(){
        $user = Client::factory()->create(['type' => 'client']);
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

    // public function test_user_can_login_as_livreur(){
    //     $user = factory(User::class)->create(['type' => 'livreur']);
    //     $credentials = [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ];

    //     $response = $this->json('POST', '/api/login', $credentials);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'status' => 200,
    //             'message' => 'Salut livreur',
    //             'user' => $user->toArray(),
    //             'authorization' => [
    //                 'token' => $response['authorization']['token'],
    //                 'type' => 'bearer',
    //             ]
    //         ]);
    // }

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
