
@extends('layouts.app')

@section('title', 'Edit Akademik')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Edit Akademik</h1>

    <!-- Card Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Akademik</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('akademik.update', $akademik->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Bagian Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_surat">Nomor Surat</label>
                            <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" value="{{ old('nomor_surat', $akademik->nomor_surat) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_surat">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $akademik->tanggal_surat ? \Carbon\Carbon::parse($akademik->tanggal_surat)->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tahun_surat">Tahun Surat</label>
                            <input type="number" name="tahun_surat" id="tahun_surat" class="form-control" value="{{ old('tahun_surat', $akademik->tahun_surat) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="pencipta_arsip">Pencipta Arsip</label>
                            <input type="text" name="pencipta_arsip" id="pencipta_arsip" class="form-control" value="{{ old('pencipta_arsip', $akademik->pencipta_arsip) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="unit_pengelola_id">Unit Pengelola</label>
                            <select name="unit_pengelola_id" id="unit_pengelola_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Unit Pengelola</option>
                                @foreach($unitPengelola as $unit)
                                    <option value="{{ $unit->id }}" {{ $akademik->unit_pengelola_id == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit_pengelola }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kode_klasifikasi_id">Klasifikasi Arsip (Hierarki)</label>
                            @php
                                function renderKlasifikasiOptionsEdit($nodes, $depth = 0, $selectedId = null) {
                                    foreach ($nodes as $node) {
                                        $indent = str_repeat('—', $depth);
                                        if ($node->isLeaf()) {
                                            $selected = ($selectedId == $node->id) ? 'selected' : '';
                                            echo '<option value="'.$node->id.'" '.$selected.'>'.$indent.' '.$node->kode.' - '.e(Str::limit($node->nama,70)).'</option>';
                                        } else {
                                            echo '<optgroup label="'.$indent.' '.$node->kode.' - '.e(Str::limit($node->nama,70)).'">';
                                            renderKlasifikasiOptionsEdit($node->children, $depth + 1, $selectedId);
                                            echo '</optgroup>';
                                        }
                                    }
                                }
                            @endphp
                            <select name="kode_klasifikasi_id" id="kode_klasifikasi_id" class="form-control" required>
                                <option value="">-- Pilih Klasifikasi Leaf --</option>
                                @isset($klasifikasiTree)
                                    @php(renderKlasifikasiOptionsEdit($klasifikasiTree, 0, $akademik->kode_klasifikasi_id))
                                @endisset
                            </select>
                            <small class="form-text text-muted">Hanya klasifikasi tingkat akhir (leaf) dapat dipilih. Retensi dihitung otomatis.</small>
                        </div>

                        <div class="form-group">
                            <label for="prihal">Prihal</label>
                            <input type="text" name="prihal" id="prihal" class="form-control" value="{{ old('prihal', $akademik->prihal) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="uraian_informasi">Uraian Informasi</label>
                            <textarea name="uraian_informasi" id="uraian_informasi" class="form-control" rows="3" required>{{ old('uraian_informasi', $akademik->uraian_informasi) }}</textarea>
                        </div>
                    </div>

                    <!-- Bagian Kanan -->
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="tingkat_perkembangan_id">Tingkat Perkembangan</label>
                            <select name="tingkat_perkembangan_id" id="tingkat_perkembangan_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Tingkat Perkembangan</option>
                                @foreach($tingkatPerkembangan as $tp)
                                    <option value="{{ $tp->id }}" {{ $akademik->tingkat_perkembangan_id == $tp->id ? 'selected' : '' }}>
                                        {{ $tp->tingkat_perkembangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="lokasi_arsip_id">Lokasi Arsip</label>
                            <select name="lokasi_arsip_id" id="lokasi_arsip_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Lokasi Arsip</option>
                                @foreach($lokasiArsip as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ $akademik->lokasi_arsip_id == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Retensi (otomatis dari klasifikasi) -->
                        <div class="form-group">
                            <label for="retensi_display">Retensi (Tahun)</label>
                            <input type="text" id="retensi_display" class="form-control" value="{{ old('retensi', $akademik->retensi) }}" readonly>
                            <input type="hidden" name="retensi" id="retensi" value="{{ old('retensi', $akademik->retensi) }}">
                            <small class="form-text text-muted">Retensi akan diperbarui jika klasifikasi diubah.</small>
                        </div>

                        <!-- Keterangan / Catatan -->
                        <div class="form-group">
                            <label for="keterangan">Catatan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $akademik->keterangan) }}</textarea>
                            <small class="form-text text-muted">Status saat ini: <strong>{{ $akademik->status_aktif }}</strong> (dihitung otomatis).</small>
                        </div>

                        <div class="form-group">
                            <label for="nasib_akhir_id">Nasib Akhir</label>
                            <select name="nasib_akhir_id" id="nasib_akhir_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Nasib Akhir</option>
                                @foreach($nasibAkhir as $nasib)
                                    <option value="{{ $nasib->id }}" {{ $akademik->nasib_akhir_id == $nasib->id ? 'selected' : '' }}>
                                        {{ $nasib->nasib_akhir }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_item">Jumlah Item</label>
                            <input type="number" name="jumlah_item" id="jumlah_item" class="form-control" value="{{ old('jumlah_item', $akademik->jumlah_item) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="lampiran">Lampiran</label>
                            <input type="text" name="lampiran" id="lampiran" class="form-control" value="{{ old('lampiran', $akademik->lampiran) }}">
                        </div>

                        <!-- Upload File -->
                        <div class="form-group">
                            <label for="file_path">Upload File (Opsional, Max. 10 MB)</label>
                            <input type="file" name="file_path" id="file_path" class="form-control">
                            @if($akademik->file_path)
                                <small class="form-text text-info">File saat ini: <a href="{{ Storage::url($akademik->file_path) }}" target="_blank">Lihat File</a></small>
                            @endif
                            <small class="form-text text-muted" id="file_info">
                                @if($akademik->file_path)
                                    File baru belum dipilih (file lama tetap digunakan).
                                @else
                                    Belum ada file dipilih.
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary" title="Simpan Perubahan"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ route('akademik.index') }}" class="btn btn-danger"><i class="fas fa-xmark"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview file name saat dipilih
    const fileInput = document.getElementById('file_path');
    const fileInfo = document.getElementById('file_info');

    fileInput?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
            fileInfo.textContent = `📁 File baru: ${file.name} (${sizeMB} MB)`;
            fileInfo.classList.remove('text-muted');
            fileInfo.classList.add('text-primary', 'font-weight-bold');
        } else {
            fileInfo.textContent = '{{ $akademik->file_path ? "File baru belum dipilih (file lama tetap digunakan)." : "Belum ada file dipilih." }}';
            fileInfo.classList.remove('text-primary', 'font-weight-bold');
            fileInfo.classList.add('text-muted');
        }
    });
});
</script>
@endpush
