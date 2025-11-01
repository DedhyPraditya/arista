<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat / memastikan user admin tersedia (idempoten)
        User::updateOrCreate(
            ['email' => 'admin@arista.id'],
            [
                'name' => 'Admin',
                // Jika sudah ada user, jangan timpa password tanpa perlu; gunakan kondisi
                'password' => User::where('email', 'admin@arista.id')->exists()
                    ? User::where('email', 'admin@arista.id')->value('password')
                    : Hash::make('password123'),
            ]
        );

        // Seed data dasar Tingkat Perkembangan jika tabel tersedia dan masih kosong
        if (Schema::hasTable('tingkat_perkembangans')) {
            $defaultTingkat = ['Aktif', 'Inaktif', 'Retensi'];
            foreach ($defaultTingkat as $tp) {
                \App\Models\TingkatPerkembangan::firstOrCreate(['tingkat_perkembangan' => $tp]);
            }
        }
    }
}
