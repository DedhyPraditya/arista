@extends('layouts.app')

@section('title', 'Edit Data Kelembagaan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Edit Data Kelembagaan</h1>

    <!-- Card Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Data Kelembagaan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('kelembagaan.update', $kelembagaan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Bagian Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomor_surat">Nomor Surat</label>
                            <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" value="{{ old('nomor_surat', $kelembagaan->nomor_surat) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_surat">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $kelembagaan->tanggal_surat) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tahun_surat">Tahun Surat</label>
                            <input type="number" name="tahun_surat" id="tahun_surat" class="form-control" value="{{ old('tahun_surat', $kelembagaan->tahun_surat) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="pencipta_arsip">Pencipta Arsip</label>
                            <input type="text" name="pencipta_arsip" id="pencipta_arsip" class="form-control" value="{{ old('pencipta_arsip', $kelembagaan->pencipta_arsip) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="unit_pengelola_id">Unit Pengelola</label>
                            <select name="unit_pengelola_id" id="unit_pengelola_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Unit Pengelola</option>
                                @foreach($unitPengelola as $unit)
                                    <option value="{{ $unit->id }}" {{ $kelembagaan->unit_pengelola_id == $unit->id ? 'selected' : '' }}>{{ $unit->unit_pengelola }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kode_klasifikasi_id">Kode Klasifikasi</label>
                            <select name="kode_klasifikasi_id" id="kode_klasifikasi_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Kode Klasifikasi</option>
                                @foreach($klasifikasi as $kode)
                                    <option value="{{ $kode->id }}" {{ $kelembagaan->kode_klasifikasi_id == $kode->id ? 'selected' : '' }}>{{ $kode->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="prihal">Prihal</label>
                            <input type="text" name="prihal" id="prihal" class="form-control" value="{{ old('prihal', $kelembagaan->prihal) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="uraian_informasi">Uraian Informasi</label>
                            <textarea name="uraian_informasi" id="uraian_informasi" class="form-control" rows="3" required>{{ old('uraian_informasi', $kelembagaan->uraian_informasi) }}</textarea>
                        </div>
                    </div>

                    <!-- Bagian Kanan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tingkat_perkembangan_id">Tingkat Perkembangan</label>
                            <select name="tingkat_perkembangan_id" id="tingkat_perkembangan_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Tingkat Perkembangan</option>
                                @foreach($tingkatPerkembangan as $tp)
                                    <option value="{{ $tp->id }}" {{ $kelembagaan->tingkat_perkembangan_id == $tp->id ? 'selected' : '' }}>{{ $tp->tingkat_perkembangan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="lokasi_arsip_id">Lokasi Arsip</label>
                            <select name="lokasi_arsip_id" id="lokasi_arsip_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Lokasi Arsip</option>
                                @foreach($lokasiArsip as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ $kelembagaan->lokasi_arsip_id == $lokasi->id ? 'selected' : '' }}>{{ $lokasi->ruangan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="retensi">Retensi</label>
                            <input type="number" name="retensi" id="retensi" class="form-control" value="{{ old('retensi', $kelembagaan->retensi) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <select name="keterangan" id="keterangan" class="form-control" required>
                                <option value="Aktif" {{ $kelembagaan->keterangan == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Inaktif" {{ $kelembagaan->keterangan == 'Inaktif' ? 'selected' : '' }}>Inaktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nasib_akhir_id">Nasib Akhir</label>
                            <select name="nasib_akhir_id" id="nasib_akhir_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Nasib Akhir</option>
                                @foreach($nasibAkhir as $nasib)
                                    <option value="{{ $nasib->id }}" {{ $kelembagaan->nasib_akhir_id == $nasib->id ? 'selected' : '' }}>{{ $nasib->nasib_akhir }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_item">Jumlah Item</label>
                            <input type="number" name="jumlah_item" id="jumlah_item" class="form-control" value="{{ old('jumlah_item', $kelembagaan->jumlah_item) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="lampiran">Lampiran</label>
                            <input type="text" name="lampiran" id="lampiran" class="form-control" value="{{ old('lampiran', $kelembagaan->lampiran) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="file_path">Upload File (PDF)</label>
                            <input type="file" name="file_path" id="file_path" class="form-control-file">
                            @if($kelembagaan->file_path)
                                <small>File saat ini: <a href="{{ Storage::url($kelembagaan->file_path) }}" target="_blank">Lihat File</a></small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary" title="Simpan Perubahan"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ route('kelembagaan.index') }}" class="btn btn-danger"><i class="fas fa-xmark"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
