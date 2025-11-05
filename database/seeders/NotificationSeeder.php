<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        if ($user) {
            // Sample notifications
            Notification::create([
                'user_id' => $user->id,
                'type' => 'success',
                'icon' => 'fa-check-circle',
                'title' => 'Data Berhasil Disimpan',
                'message' => 'Data akademik baru telah berhasil ditambahkan ke sistem.',
                'url' => route('akademik.index'),
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => $user->id,
                'type' => 'info',
                'icon' => 'fa-info-circle',
                'title' => 'Pengingat Arsip',
                'message' => 'Terdapat 5 arsip yang akan jatuh tempo bulan ini.',
                'url' => route('dashboard'),
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => $user->id,
                'type' => 'warning',
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Perhatian',
                'message' => 'Ruang penyimpanan hampir penuh. Segera lakukan arsip cleanup.',
                'url' => null,
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => null, // Global notification
                'type' => 'info',
                'icon' => 'fa-bullhorn',
                'title' => 'Pemeliharaan Sistem',
                'message' => 'Sistem akan dilakukan pemeliharaan pada hari Minggu jam 02:00 WIB.',
                'url' => null,
                'is_read' => false
            ]);

            $this->command->info('Sample notifications created successfully!');
        } else {
            $this->command->warn('No users found. Please create a user first.');
        }
    }
}
