<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi'; // Atur nama tabel
    protected $fillable = ['kode', 'nama', 'retensi'];

    public function hkts()
    {
        return $this->hasMany(Hkt::class, 'kode_klasifikasi_id');
    }
    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'kode_klasifikasi_id');
    }
    public function kelembagaans()
    {
        return $this->hasMany(Kelembagaan::class, 'kode_klasifikasi_id');
    }
}
