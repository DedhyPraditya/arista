@extends('layouts.app')
@section('title', 'Tabel Kemahasiswaan > Detail')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-0 text-gray-800">Detail Arsip</h1>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('kemahasiswaan.index') }}" class="btn btn-danger" title="Kembali"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="row">
        <!-- Informasi Kemahasiswaan Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-primary">Informasi Kemahasiswaan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Nomor Surat</th>
                                <td class="text-center">{{ $kemahasiswaan->nomor_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tanggal Surat</th>
                                <td class="text-center">{{ \Carbon\Carbon::parse($kemahasiswaan->tanggal_surat)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tahun Surat</th>
                                <td class="text-center">{{ $kemahasiswaan->tahun_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Pencipta Arsip</th>
                                <td class="text-center">{{ $kemahasiswaan->pencipta_arsip }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit Pengelola</th>
                                <td class="text-center">{{ $kemahasiswaan->unitPengelola->unit_pengelola }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Kode Klasifikasi</th>
                                <td class="text-center">{{ $kemahasiswaan->klasifikasi->nama ?? '-'}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Prihal</th>
                                <td class="text-center">{{ $kemahasiswaan->prihal }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Uraian Informasi</th>
                                <td>{{ $kemahasiswaan->uraian_informasi }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tingkat Perkembangan</th>
                                <td class="text-center">{{ $kemahasiswaan->tingkatPerkembangan->tingkat_perkembangan }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lokasi Arsip</th>
                                <td class="text-center">{{ $kemahasiswaan->lokasiArsip->ruangan}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Jumlah Item</th>
                                <td class="text-center">{{ $kemahasiswaan->jumlah_item }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lampiran</th>
                                <td class="text-center">{{ $kemahasiswaan->lampiran }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tahun Aktif</th>
                                <td class="text-center">({{ $kemahasiswaan->tahun_surat }} - {{ $kemahasiswaan->tahun_surat + $kemahasiswaan->retensi }})</td>
                            </tr>
                            <tr>
                                <th scope="row">Keterangan</th>
                                <td class="text-center">
                                    @if ($kemahasiswaan->keterangan === 'Aktif')
                                    <span class="badge badge-success">{{ $kemahasiswaan->keterangan }}</span>
                                @elseif ($kemahasiswaan->keterangan === 'Inaktif')
                                    <span class="badge badge-warning">{{ $kemahasiswaan->keterangan }}</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Nasib Akhir</th>
                                <td class="text-center">{{ $kemahasiswaan->nasibAkhir->nasib_akhir ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PDF Display Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-primary">File Digitalisasi Berkas</h5>
                </div>
                <div class="card-body">
                    @if($kemahasiswaan->file_path)
                        <!-- Menampilkan file PDF menggunakan iframe -->
                        <iframe src="{{ Storage::url($kemahasiswaan->file_path) }}" type="application/pdf" width="100%" height="741px"></iframe>
                    @else
                        <p>Tidak ada file yang ditampilkan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
