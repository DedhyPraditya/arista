<?php

namespace App\Traits;

trait ValidationMessagesTrait
{
    /**
     * Get custom validation messages in Indonesian
     *
     * @return array
     */
    protected function getValidationMessages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'min' => ':attribute minimal :min karakter.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'integer' => ':attribute harus berupa angka.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'file' => ':attribute harus berupa file.',
            'mimes' => ':attribute harus berupa file dengan format: :values.',
            'max.file' => ':attribute maksimal :max KB.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'in' => ':attribute yang dipilih tidak valid.',
        ];
    }

    /**
     * Get custom attribute names in Indonesian
     *
     * @return array
     */
    protected function getAttributeNames(): array
    {
        return [
            'nomor_surat' => 'Nomor Surat',
            'tanggal_surat' => 'Tanggal Surat',
            'tahun_surat' => 'Tahun Surat',
            'pencipta_arsip' => 'Pencipta Arsip',
            'unit_pengelola_id' => 'Unit Pengelola',
            'kode_klasifikasi_id' => 'Kode Klasifikasi',
            'prihal' => 'Perihal',
            'uraian_informasi' => 'Uraian Informasi',
            'tingkat_perkembangan_id' => 'Tingkat Perkembangan',
            'lokasi_arsip_id' => 'Lokasi Arsip',
            'jumlah_item' => 'Jumlah Item',
            'lampiran' => 'Lampiran',
            'retensi' => 'Retensi',
            'keterangan' => 'Keterangan',
            'nasib_akhir_id' => 'Nasib Akhir',
            'file_path' => 'File Dokumen',
            'kode' => 'Kode',
            'nama' => 'Nama',
            'nama_departemen' => 'Nama Departemen',
            'nasib_akhir' => 'Nasib Akhir',
            'tingkat_perkembangan' => 'Tingkat Perkembangan',
            'unit_pengelola' => 'Unit Pengelola',
            'ruangan' => 'Ruangan',
            'gedung' => 'Gedung',
            'lemari' => 'Lemari',
            'rak' => 'Rak',
            'book' => 'Book',
            'folder' => 'Folder',
        ];
    }

    /**
     * Get standardized validation rules for common fields
     *
     * @return array
     */
    protected function getCommonValidationRules(): array
    {
        return [
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tahun_surat' => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'pencipta_arsip' => 'required|string|max:255',
            'unit_pengelola_id' => 'required|exists:unit_pengelolas,id',
            'kode_klasifikasi_id' => 'required|exists:klasifikasi,id',
            'prihal' => 'required|string|max:500',
            'uraian_informasi' => 'required|string',
            'tingkat_perkembangan_id' => 'required|exists:tingkat_perkembangans,id',
            'lokasi_arsip_id' => 'required|exists:lokasi_arsips,id',
            'jumlah_item' => 'nullable|integer|min:0',
            'lampiran' => 'nullable|string|max:255',
            'retensi' => 'nullable|integer|min:0|max:100',
            'keterangan' => 'nullable|string',
            'nasib_akhir_id' => 'required|exists:nasib_akhir,id',
        ];
    }
}
