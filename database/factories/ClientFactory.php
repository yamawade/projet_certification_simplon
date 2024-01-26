<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'prenom' => fake()->name(),
            //'adresse' => fake()->address(),
            // 'numero_tel' => fake()->phoneNumber(),
            // 'date_naiss' => fake()->date(),
            // 'genre' => fake()->randomElement(['homme', 'femme']),
            'email' => fake()->unique()->safeEmail(),
            'password' =>Hash::make('passer1234'),
            'type' => 'client',
        ];
    }
}
