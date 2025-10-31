<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiArsip extends Model
{
    use HasFactory;

    protected $fillable = ['ruangan', 'gedung', 'lemari', 'rak', 'book', 'folder'];

    public function hkts()
    {
        return $this->hasMany(Hkt::class, 'lokasi_arsip_id');
    }
    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'lokasi_arsip_id');
    }
    public function kelembagaans()
    {
        return $this->hasMany(Kelembagaan::class, 'lokasi_arsip_id');
    }
}
