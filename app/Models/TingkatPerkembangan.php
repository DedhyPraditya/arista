<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatPerkembangan extends Model
{
    use HasFactory;

    protected $table = 'tingkat_perkembangans';
    protected $fillable = ['tingkat_perkembangan'];

    public function hkts()
    {
        return $this->hasMany(Hkt::class, 'kode_klasifikasi_id');
    }
    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'kode_klasifikasi_id');
    }
}
