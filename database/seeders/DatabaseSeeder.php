<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat user untuk login
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@arista.id',
            'password' => Hash::make('password123'), // Password terenkripsi
        ]);
    }
}
