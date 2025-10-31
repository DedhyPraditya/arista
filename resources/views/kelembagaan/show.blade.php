@extends('layouts.app')

@section('title', 'Tabel Kelembagaan > Detail')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-0 text-gray-800">Detail Data Kelembagaan</h1>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('kelembagaan.index') }}" class="btn btn-danger" title="Kembali"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="row">
        <!-- Informasi Kelembagaan Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-primary">Informasi Kelembagaan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Nomor Surat</th>
                                <td class="text-center">{{ $kelembagaan->nomor_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tanggal Surat</th>
                                <td class="text-center">{{ \Carbon\Carbon::parse($kelembagaan->tanggal_surat)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tahun Surat</th>
                                <td class="text-center">{{ $kelembagaan->tahun_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Pencipta Arsip</th>
                                <td class="text-center">{{ $kelembagaan->pencipta_arsip }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit Pengelola</th>
                                <td class="text-center">{{ $kelembagaan->unitPengelola->unit_pengelola }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Kode Klasifikasi</th>
                                <td class="text-center">{{ $kelembagaan->klasifikasi->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Prihal</th>
                                <td class="text-center">{{ $kelembagaan->prihal }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Uraian Informasi</th>
                                <td>{{ $kelembagaan->uraian_informasi }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tingkat Perkembangan</th>
                                <td class="text-center">{{ $kelembagaan->tingkatPerkembangan->tingkat_perkembangan }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lokasi Arsip</th>
                                <td class="text-center">{{ $kelembagaan->lokasiArsip->ruangan }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Retensi</th>
                                <td class="text-center">{{ $kelembagaan->retensi }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Keterangan</th>
                                <td class="text-center">
                                    @if ($kelembagaan->keterangan === 'Aktif')
                                    <span class="badge badge-success">{{ $kelembagaan->keterangan }}</span>
                                    @elseif ($kelembagaan->keterangan === 'Inaktif')
                                    <span class="badge badge-warning">{{ $kelembagaan->keterangan }}</span>
                                    @else
                                    <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Nasib Akhir</th>
                                <td class="text-center">{{ $kelembagaan->nasibAkhir->nasib_akhir ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Jumlah Item</th>
                                <td class="text-center">{{ $kelembagaan->jumlah_item }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lampiran</th>
                                <td class="text-center">{{ $kelembagaan->lampiran }}</td>
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
                    @if($kelembagaan->file_path)
                    <iframe src="{{ Storage::url(($kelembagaan->file_path)) }}" type="application/pdf" width="100%" height="820px"></iframe>
                    @else
                        <p>Tidak ada file yang ditampilkan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
