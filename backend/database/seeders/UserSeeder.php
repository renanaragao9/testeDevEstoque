<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'renanaragao159@gmail.com',
            'password' => Hash::make('Renan@12'),
            'email_verified_at' => now(),
        ]);
    }
}
