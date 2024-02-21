<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategorieTest extends TestCase
{
    use RefreshDatabase;

    public function test_StoreCategorie()
    {
        $admin = User::factory()->create(['type' => 'admin']);
        $data = [
            'nom_categorie' => 'TestCategorie',
        ];

        $response = $this->actingAs($admin)->post('/api/categorie/create', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'status_message' => 'La categorie a ete ajouté',
            ]);
    
    }

    public function test_UpdateCategorie()
    {
        $categorie = Categorie::factory()->create();

        $admin = User::factory()->create(['type' => 'admin']);

        $data = [
            'nom_categorie' => 'TestCategorie2',
        ];
        $response = $this->actingAs($admin)->post("/api/categorie/update/{$categorie->id}", $data);
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'status_message'=>'La categorie a ete modifier',
            ]);
    }  

    public function test_DeleteCategorie()
    {
        $categorie = Categorie::factory()->create();
        //dump('Category created:', $categorie);
        $admin = User::factory()->create(['type' => 'admin']);
        $response = $this->actingAs($admin)->deleteJson("/api/categorie/{$categorie->id}");
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'status_message'=>'La categorie a ete supprimer',
            ]);
    }

    public function test_IndexCategorie()
    {
        $categories = Categorie::factory()->create();
        $response = $this->get('/api/categories');
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'status_message'=>'Liste des categories',
            ]);
    }
}
