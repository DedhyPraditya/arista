<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi';

    // Tambahkan kolom hierarki baru ke fillable
    protected $fillable = [
        'kode',
        'parent_kode',
        'level',
        'is_leaf',
        'urusan',
        'sub_urusan',
        'nama',
        'retensi',
    ];

    /*
     |--------------------------------------------------
     | Relasi ke arsip (existing)
     |--------------------------------------------------
     */
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

    /*
     |--------------------------------------------------
     | Relasi hierarki internal
     |--------------------------------------------------
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_kode', 'kode');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_kode', 'kode')->orderBy('kode');
    }

    /*
     |--------------------------------------------------
     | Scope & Helper
     |--------------------------------------------------
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_kode')->orWhere('parent_kode', '');
    }

    public function scopeLeaf($query)
    {
        return $query->where('is_leaf', true);
    }

    public function isLeaf(): bool
    {
        return (bool)$this->is_leaf;
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }
}
