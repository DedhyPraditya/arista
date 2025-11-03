@extends('layouts.app')
@section('title', 'Tabel Akademik > Create')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Akademik</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Akademik</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('akademik.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <!-- Nomor Surat -->
                                <div class="form-group">
                                    <label for="nomor_surat">Nomor Surat</label>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" placeholder="Masukkan Nomor Surat..." required>
                                </div>

                                <!-- Tanggal Surat -->
                                <div class="form-group">
                                    <label for="tanggal_surat">Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" required>
                                </div>

                                <!-- Tahun Surat -->
                                <div class="form-group">
                                    <label for="tahun_surat">Tahun Surat</label>
                                    <input type="number" name="tahun_surat" id="tahun_surat" class="form-control" required>
                                </div>

                                <!-- Pencipta Arsip -->
                                <div class="form-group">
                                    <label for="pencipta_arsip">Pencipta Arsip</label>
                                    <input type="text" name="pencipta_arsip" id="pencipta_arsip" class="form-control" required>
                                </div>

                                <!-- Unit Pengelola -->
                                <div class="form-group">
                                    <label for="unit_pengelola_id">Unit Pengelola</label>
                                    <select name="unit_pengelola_id" id="unit_pengelola_id" class="form-control" required>
                                        @foreach($unitPengelola as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit_pengelola }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Kode Klasifikasi -->
                                <div class="form-group">
                                    <label for="kode_klasifikasi_id">Kode Klasifikasi</label>
                                    <select name="kode_klasifikasi_id" id="kode_klasifikasi_id" class="form-control" required>
                                        <option value="">-- Pilih Kode Klasifikasi --</option>
                                        @foreach($klasifikasi as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->kode }}
                                                @if($k->urusan) - {{ Str::limit($k->urusan, 30) }} @endif
                                                @if($k->sub_urusan) - {{ Str::limit($k->sub_urusan, 40) }} @endif
                                                ({{ $k->retensi }} th)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Retensi akan terisi otomatis sesuai klasifikasi yang dipilih</small>
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
                                        @foreach($tingkatPerkembangan as $tp)
                                            <option value="{{ $tp->id }}">{{ $tp->tingkat_perkembangan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Lokasi Arsip -->
                                <div class="form-group">
                                    <label for="lokasi_arsip_id">Lokasi Arsip</label>
                                    <select name="lokasi_arsip_id" id="lokasi_arsip_id" class="form-control" required>
                                        @foreach($lokasiArsip as $la)
                                            <option value="{{ $la->id }}">{{ $la->ruangan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Retensi (otomatis dari klasifikasi) -->
                                <div class="form-group">
                                    <label for="retensi_display">Retensi (Tahun)</label>
                                    <input type="text" id="retensi_display" class="form-control" readonly placeholder="Otomatis diisi dari Klasifikasi">
                                    <input type="hidden" name="retensi" id="retensi" value="">
                                    <small class="form-text text-muted">Retensi akan diisi otomatis berdasarkan Klasifikasi yang dipilih.</small>
                                </div>

                                <!-- Keterangan / Catatan -->
                                <div class="form-group">
                                    <label for="keterangan">Catatan</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                                    <small class="form-text text-muted">Status aktif/inaktif dihitung otomatis berdasarkan tanggal surat & retensi.</small>
                                </div>

                                <!-- Nasib Akhir -->
                                <div class="form-group">
                                    <label for="nasib_akhir_id">Nasib Akhir</label>
                                    <select name="nasib_akhir_id" id="nasib_akhir_id" class="form-control" required>
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

                                <!-- Upload File -->
                                <div class="form-group">
                                    <label for="file_path">Upload File (Opsional, Max. 10 MB)</label>
                                    <input type="file" name="file_path" id="file_path" class="form-control">
                                    <small class="form-text text-muted" id="file_info">Belum ada file dipilih.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <a href="{{ route('akademik.index') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
                        <button type="submit" class="btn btn-primary" title="Simpan"><i class="fas fa-save"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data klasifikasi untuk auto-fill retensi
    const klasifikasiData = {!! json_encode($klasifikasi->map(fn($k) => ['id' => $k->id, 'retensi' => $k->retensi])) !!};

    const klasifikasiSelect = document.getElementById('kode_klasifikasi_id');
    const retensiInput = document.getElementById('retensi');
    const retensiDisplay = document.getElementById('retensi_display');

    // Auto-fill retensi saat klasifikasi dipilih
    klasifikasiSelect?.addEventListener('change', function() {
        const selectedId = parseInt(this.value);
        const selected = klasifikasiData.find(k => k.id === selectedId);
        if (selected) {
            retensiInput.value = selected.retensi; // hidden field (angka saja)
            retensiDisplay.value = selected.retensi + ' tahun'; // display field
        } else {
            retensiInput.value = '';
            retensiDisplay.value = '';
        }
    });

    // Preview file name saat dipilih
    const fileInput = document.getElementById('file_path');
    const fileInfo = document.getElementById('file_info');

    fileInput?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
            fileInfo.textContent = `📁 ${file.name} (${sizeMB} MB)`;
            fileInfo.classList.remove('text-muted');
            fileInfo.classList.add('text-primary', 'font-weight-bold');
        } else {
            fileInfo.textContent = 'Belum ada file dipilih.';
            fileInfo.classList.remove('text-primary', 'font-weight-bold');
            fileInfo.classList.add('text-muted');
        }
    });
});
</script>
@endpush
