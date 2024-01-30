<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom_produit' => fake()->name(),
            'quantite' => rand(1, 10),
            'prix' => rand(1.0, 100.0),
            'description' => fake()->text(),
            'image' => 'image.png',
            'categorie_id' =>  1,
            'commercant_id' =>1,
        ];
    }
}
