<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hkt extends Model
{
    protected $fillable = [
        'nomor_surat', 'tanggal_surat', 'tahun_surat', 'pencipta_arsip',
        'unit_pengelola_id', 'kode_klasifikasi_id', 'prihal', 'uraian_informasi',
        'tingkat_perkembangan_id', 'lokasi_arsip_id', 'jumlah_item',
        'lampiran', 'retensi', 'keterangan', 'nasib_akhir_id', 'file_path',
    ];

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

    public function unitPengelola()
    {
        return $this->belongsTo(UnitPengelola::class, 'unit_pengelola_id');
    }
}

