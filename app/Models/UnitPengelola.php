<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPengelola extends Model
{
    use HasFactory;

    protected $fillable = ['unit_pengelola'];
    public function hkts()
    {
        return $this->hasMany(Hkt::class, 'unit_pengelolas_id');
    }
    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'unit_pengelolas_id');
    }
    
}

