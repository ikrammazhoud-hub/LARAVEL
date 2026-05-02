<?php

namespace Database\Seeders;

use App\Models\Technicien;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TechnicienSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean.dupont@atelierpro.fr',
                'password' => Hash::make('password'),
                'role' => 'technicien',
            ],
            [
                'name' => 'Marie Martin',
                'email' => 'marie.martin@atelierpro.fr',
                'password' => Hash::make('password'),
                'role' => 'technicien',
            ],
            [
                'name' => 'Pierre Bernard',
                'email' => 'pierre.bernard@atelierpro.fr',
                'password' => Hash::make('password'),
                'role' => 'technicien',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            Technicien::create([
                'user_id' => $user->id,
                'specialite' => $user->name === 'Jean Dupont' ? 'Hydraulique' : ($user->name === 'Marie Martin' ? 'CNC' : 'Électricité'),
                'telephone' => '06' . rand(10000000, 99999999),
            ]);
        }
    }
}
