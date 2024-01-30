<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Commercant;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProduitTest extends TestCase
{
    use RefreshDatabase;

    public function test_StoreProduit(){
        $user = User::factory()->create(['type' => 'commercant']);
        $categorie = Categorie::factory()->create();
        $commercant = Commercant::factory()->create(['user_id' => $user->id]);
       // dd($categorie->id);
        $data = [
            'nom_produit' => 'ProduitTest',
            'quantite' => 10,
            'prix' => 20.99,
            'description' => 'Ceci est un produit',
            'categorie_id' => $categorie->id,
            'commercant_id' => $commercant->id,
            'image' => UploadedFile::fake()->image('produit.jpg'),
        ];

        $response = $this->actingAs($user)->json('POST', '/api/produit/create', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status'=>200,
                'status_message'=>'Le produit a ete ajouté',
            ]);
    }


    public function test_UpdateProduit(){
        $user = User::factory()->create(['type' => 'commercant']);
        $categorie = Categorie::factory()->create();
        $commercant = Commercant::factory()->create(['user_id' => $user->id]);
        $produit = Produit::factory()->create(['commercant_id' => $commercant->id]);
        $data = [
            'nom_produit' => 'ProduitTest',
            'quantite' => 10,
            'prix' => 20.99,
            'description' => 'Ceci est un produit',
            'categorie_id' => $categorie->id,
            'commercant_id' => $commercant->id,
            'image' => UploadedFile::fake()->image('produit.jpg'),
        ];
        $response = $this->actingAs($user)->json('POST', "/api/produit/update/{$produit->id}", $data);
        $response->assertStatus(200)
            ->assertJson([
                'status'=>200,
                'status_message'=>'Le produit a ete modifié',
            ]);

    }

    public function test_DeleteProduit(){
        $user = User::factory()->create(['type' => 'commercant']);
        $categorie = Categorie::factory()->create();
        $commercant = Commercant::factory()->create(['user_id' => $user->id]);
        $produit = Produit::factory()->create(['commercant_id' => $commercant->id]);

        $response = $this->actingAs($user)->json('POST', "/api/produit/{$produit->id}");
        $response->assertStatus(200)
            ->assertJson([
                'status'=>200,
                'status_message'=>'Le produit a ete supprimer',
            ]);
    }

    public function test_IndexProduit(){
        $user = User::factory()->create(['type' => 'commercant']);
        $categorie = Categorie::factory()->create();
        $commercant = Commercant::factory()->create(['user_id' => $user->id]);
        $produit = Produit::factory()->create(['commercant_id' => $commercant->id]);
        $response = $this->get('/api/produits');
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'status_message'=>'Liste des produits',
            ]);
    }
}
