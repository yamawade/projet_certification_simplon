<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commercant>
 */
class CommercantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ninea'=> fake()->optional()->randomNumber(8),
            'adresse'=> fake()->address(),
            'nin'=> fake()->unique()->randomNumber(9),
            'genre'=> fake()->randomElement(['homme', 'femme']),
            'date_naiss'=> fake()->date(),
            'numero_tel' => fake()->phoneNumber(),
            'user_id' => 1
        ];
    }
}
