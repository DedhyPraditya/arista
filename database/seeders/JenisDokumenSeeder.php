<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisDokumen;

class JenisDokumenSeeder extends Seeder
{
    public function run()
    {
        // Data default
        $data = [
            ['jenis_dokumen' => 'Dokumen Rahasia'],
            ['jenis_dokumen' => 'Dokumen Umum'],
        ];

        foreach ($data as $item) {
            JenisDokumen::create($item);
        }
    }
}
