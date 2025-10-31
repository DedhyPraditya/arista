@extends('layouts.app')
@section('title', 'Tabel Keuangan > Detail')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-0 text-gray-800">Detail Data Keuangan</h1>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('keuangan.index') }}" class="btn btn-danger" title="Kembali"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="row">
        <!-- Informasi Keuangan Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-primary">Informasi Keuangan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Nomor Surat</th>
                                <td class="text-center">{{ $keuangan->nomor_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tanggal Surat</th>
                                <td class="text-center">{{ \Carbon\Carbon::parse($keuangan->tanggal_surat)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tahun Surat</th>
                                <td class="text-center">{{ $keuangan->tahun_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Pencipta Arsip</th>
                                <td class="text-center">{{ $keuangan->pencipta_arsip }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit Pengelola</th>
                                <td class="text-center">{{ $keuangan->unitPengelola->unit_pengelola }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Kode Klasifikasi</th>
                                <td class="text-center">{{ $keuangan->klasifikasi->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Prihal</th>
                                <td class="text-center">{{ $keuangan->prihal }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Uraian Informasi</th>
                                <td>{{ $keuangan->uraian_informasi }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tingkat Perkembangan</th>
                                <td class="text-center">{{ $keuangan->tingkatPerkembangan->tingkat_perkembangan }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lokasi Arsip</th>
                                <td class="text-center">{{ $keuangan->lokasiArsip->ruangan }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Retensi</th>
                                <td class="text-center">{{ $keuangan->retensi }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Keterangan</th>
                                <td class="text-center">
                                    @if ($keuangan->keterangan === 'Aktif')
                                    <span class="badge badge-success">{{ $keuangan->keterangan }}</span>
                                    @elseif ($keuangan->keterangan === 'Inaktif')
                                    <span class="badge badge-warning">{{ $keuangan->keterangan }}</span>
                                    @else
                                    <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Nasib Akhir</th>
                                <td class="text-center">{{ $keuangan->nasibAkhir->nasib_akhir ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Jumlah Item</th>
                                <td class="text-center">{{ $keuangan->jumlah_item }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lampiran</th>
                                <td class="text-center">{{ $keuangan->lampiran }}</td>
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
                    @if($keuangan->file_path)
                    <iframe src="{{ Storage::url(($keuangan->file_path)) }}" type="application/pdf" width="100%" height="820px"></iframe>
                    @else
                        <p>Tidak ada file yang ditampilkan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
