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

                                <!-- Klasifikasi Arsip (Tree) -->
                                <div class="form-group">
                                    <label for="kode_klasifikasi_id">Klasifikasi Arsip (Hierarki)</label>
                                    @php
                                        // Recursive render function
                                        function renderKlasifikasiOptions($nodes, $depth = 0) {
                                            foreach ($nodes as $node) {
                                                $indent = str_repeat('—', $depth);
                                                if ($node->isLeaf()) {
                                                    echo '<option value="'.$node->id.'">'.$indent.' '.$node->kode.' - '.e(Str::limit($node->nama,70)).'</option>';
                                                } else {
                                                    echo '<optgroup label="'.$indent.' '.$node->kode.' - '.e(Str::limit($node->nama,70)).'">';
                                                    renderKlasifikasiOptions($node->children, $depth + 1);
                                                    echo '</optgroup>';
                                                }
                                            }
                                        }
                                    @endphp
                                    <select name="kode_klasifikasi_id" id="kode_klasifikasi_id" class="form-control" required>
                                        <option value="">-- Pilih Klasifikasi Leaf --</option>
                                        @isset($klasifikasiTree)
                                            @php(renderKlasifikasiOptions($klasifikasiTree))
                                        @endisset
                                    </select>
                                    <small class="form-text text-muted">Hanya klasifikasi tingkat akhir (leaf) dapat dipilih. Retensi dihitung otomatis.</small>
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

                                <!-- Retensi dihilangkan dari form create (dihitung otomatis) -->

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
    // Preview file name saat dipilih (retensi di-backend)
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

        // Initialize Select2 for Klasifikasi dropdown
        $('#kode_klasifikasi_id').select2({
            theme: 'bootstrap4',
            placeholder: '-- Pilih Klasifikasi Leaf --',
            allowClear: true,
            width: '100%'
        });
});
</script>
@endpush
