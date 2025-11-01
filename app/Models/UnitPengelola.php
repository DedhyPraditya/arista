<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPengelola extends Model
{
    use HasFactory;

    protected $fillable = ['unit_pengelola'];
    // Perbaikan foreign key: gunakan unit_pengelola_id (bukan unit_pengelolas_id)
    public function hkts()
    {
        return $this->hasMany(Hkt::class, 'unit_pengelola_id');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'unit_pengelola_id');
    }

    // Tambahkan relasi umum untuk ekstensi ke model lain bila diperlukan
    public function arsip()
    {
        return $this->hasMany(Hkt::class, 'unit_pengelola_id');
    }

}

