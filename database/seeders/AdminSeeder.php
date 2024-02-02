<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            'nom' => 'WADE',
            'prenom' => 'Mariam',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('passer1234'),
            'type' => 'admin',
            'numero_tel' => '778909876',
            'genre' => 'femme'
        ];
        $user = User::create($userData);

        $admin = $user->admin()->create();
    }
}
