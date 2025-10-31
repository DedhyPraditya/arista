<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sdpt extends Model
{
    //use HasFactory;
    protected $table = 'sdpts';

    protected $fillable = [
        'nomor_surat', 'tanggal_surat', 'tahun_surat', 'pencipta_arsip',
        'unit_pengelola_id', 'kode_klasifikasi_id', 'prihal', 'uraian_informasi',
        'tingkat_perkembangan_id', 'lokasi_arsip_id', 'retensi', 'keterangan',
        'nasib_akhir_id', 'jumlah_item', 'lampiran', 'file_path',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tahun_surat' => 'integer',
        'retensi' => 'integer',
        'jumlah_item' => 'integer',
    ];

    // Relasi ke model lain
    public function unitPengelola()
    {
        return $this->belongsTo(UnitPengelola::class, 'unit_pengelola_id');
    }

    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'kode_klasifikasi_id');
    }

    public function tingkatPerkembangan()
    {
        return $this->belongsTo(TingkatPerkembangan::class, 'tingkat_perkembangan_id');
    }

    public function lokasiArsip()
    {
        return $this->belongsTo(LokasiArsip::class, 'lokasi_arsip_id');
    }

    public function nasibAkhir()
    {
        return $this->belongsTo(NasibAkhir::class, 'nasib_akhir_id');
    }

    // Scope untuk keterangan 'Aktif'
    public function scopeAktif($query)
    {
        return $query->where('keterangan', 'Aktif');
    }
}
