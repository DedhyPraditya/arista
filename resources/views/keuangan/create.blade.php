@extends('layouts.app')
@section('title', 'Tabel Keuangan > Create')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Keuangan</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Keuangan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('keuangan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <!-- Nomor Surat -->
                                <div class="form-group">
                                    <label for="nomor_surat">Nomor Surat</label>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" placeholder="Masukkan nomor surat..." required>
                                    @error('nomor_surat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Tanggal Surat -->
                                <div class="form-group">
                                    <label for="tanggal_surat">Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" required>
                                </div>

                                <!-- Tahun Surat -->
                                <div class="form-group">
                                    <label for="tahun_surat">Tahun Surat</label>
                                    <input type="number" name="tahun_surat" id="tahun_surat" class="form-control" placeholder="Masukkan Tahun surat..." required>
                                </div>

                                <!-- Pencipta Arsip -->
                                <div class="form-group">
                                    <label for="pencipta_arsip">Pencipta Arsip</label>
                                    <input type="text" name="pencipta_arsip" id="pencipta_arsip" class="form-control" placeholder="Masukkan Pencipta Arsip..." required>
                                </div>

                                <!-- Unit Pengelola -->
                                <div class="form-group">
                                    <label for="unit_pengelola_id">Unit Pengelola</label>
                                    <select name="unit_pengelola_id" id="unit_pengelola_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Unit Pengelola</option>
                                        @foreach($unitPengelolas as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit_pengelola }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Kode Klasifikasi -->
                                <div class="form-group">
                                    <label for="kode_klasifikasi_id">Kode Klasifikasi</label>
                                    <select name="kode_klasifikasi_id" id="kode_klasifikasi_id" class="form-control" required>
                                        @foreach($klasifikasi as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Prihal -->
                                <div class="form-group">
                                    <label for="prihal">Prihal</label>
                                    <input type="text" name="prihal" id="prihal" class="form-control" required>
                                </div>

                                <!-- Uraian Informasi -->
                                <div class="form-group">
                                    <label for="uraian_informasi">Uraian Informasi</label>
                                    <textarea name="uraian_informasi" id="uraian_informasi" class="form-control" rows="3" required></textarea>
                                </div>

                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                              <!-- Tingkat Perkembangan -->
                                <div class="form-group">
                                    <label for="tingkat_perkembangan_id">Tingkat Perkembangan</label>
                                    <select name="tingkat_perkembangan_id" id="tingkat_perkembangan_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Tingkat Perkembangan</option>
                                        @foreach($tingkatPerkembangan as $tp)
                                            <option value="{{ $tp->id }}">{{ $tp->tingkat_perkembangan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Lokasi Arsip -->
                                <div class="form-group">
                                    <label for="lokasi_arsip_id">Lokasi Arsip</label>
                                    <select name="lokasi_arsip_id" id="lokasi_arsip_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Lokasi Arsip</option>
                                        @foreach($lokasiArsip as $la)
                                            <option value="{{ $la->id }}">{{ $la->ruangan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Retensi (Display Only) -->
                                <div class="form-group">
                                    <label for="retensi_display">Retensi</label>
                                    <input type="text" id="retensi_display" class="form-control" readonly placeholder="Otomatis terisi dari klasifikasi">
                                    <input type="hidden" name="retensi" id="retensi">
                                </div>

                                <!-- Keterangan (Optional Notes) -->
                                <div class="form-group">
                                    <label for="keterangan">Keterangan (Opsional)</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan jika diperlukan"></textarea>
                                </div>

                                <!-- Nasib Akhir -->
                                <div class="form-group">
                                    <label for="nasib_akhir_id">Nasib Akhir</label>
                                    <select name="nasib_akhir_id" id="nasib_akhir_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Nasib Akhir</option>
                                        @foreach($nasibAkhir as $na)
                                            <option value="{{ $na->id }}">{{ $na->nasib_akhir }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Jumlah Item -->
                                <div class="form-group">
                                    <label for="jumlah_item">Jumlah Item</label>
                                    <input type="number" name="jumlah_item" id="jumlah_item" class="form-control" required>
                                </div>

                                <!-- Lampiran -->
                                <div class="form-group">
                                    <label for="lampiran">Lampiran</label>
                                    <input type="text" name="lampiran" id="lampiran" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="file_path">Upload File (Max. 5 Mbps)</label>
                                    <input type="file" name="file_path" id="file_path" class="form-control" required>
                                    <small id="file_info" class="form-text text-muted"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <a href="{{ route('keuangan.index') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
                        <button type="submit" class="btn btn-primary" title="Simpan"><i class="fas fa-save"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#kode_klasifikasi_id').change(function() {
        const klasifikasiId = $(this).val();
        if (klasifikasiId) {
            $.ajax({
                url: '/api/klasifikasi/' + klasifikasiId,
                method: 'GET',
                success: function(data) {
                    if (data && data.retensi !== undefined) {
                        $('#retensi_display').val(data.retensi + ' th');
                        $('#retensi').val(data.retensi);
                    }
                },
                error: function() {
                    $('#retensi_display').val('');
                    $('#retensi').val('');
                }
            });
        } else {
            $('#retensi_display').val('');
            $('#retensi').val('');
        }
    });

    $('#file_path').change(function() {
        const file = this.files[0];
        if (file) {
            const size = (file.size / (1024 * 1024)).toFixed(2);
            $('#file_info').text('File: ' + file.name + ' (' + size + ' MB)');
            if (size > 5) {
                $('#file_info').addClass('text-danger').removeClass('text-muted');
                alert('Ukuran file melebihi 5 MB!');
            } else {
                $('#file_info').removeClass('text-danger').addClass('text-muted');
            }
        }
    });
});
</script>
@endpush
@endsection
