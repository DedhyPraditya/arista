@extends('layouts.app')
@section('title', 'Tabel Akademik > Detail')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-0 text-gray-800">Detail Arsip Akademik</h1>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('akademik.index') }}" class="btn btn-danger" title="Kembali"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="row">
        <!-- Informasi Akademik Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-primary">Informasi Akademik</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Nomor Surat</th>
                                <td class="text-center">{{ $akademik->nomor_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tanggal Surat</th>
                                <td class="text-center">{{ \Carbon\Carbon::parse($akademik->tanggal_surat)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tahun Surat</th>
                                <td class="text-center">{{ $akademik->tahun_surat }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Pencipta Arsip</th>
                                <td class="text-center">{{ $akademik->pencipta_arsip }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit Pengelola</th>
                                <td class="text-center">{{ $akademik->unitPengelola->unit_pengelola }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Kode Klasifikasi</th>
                                <td class="text-center">{{ $akademik->klasifikasi->nama ?? '-'}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Prihal</th>
                                <td class="text-center">{{ $akademik->prihal }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Uraian Informasi</th>
                                <td>{{ $akademik->uraian_informasi }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tingkat Perkembangan</th>
                                <td class="text-center">{{ $akademik->tingkatPerkembangan->tingkat_perkembangan }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lokasi Arsip</th>
                                <td class="text-center">{{ $akademik->lokasiArsip->ruangan }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Jumlah Item</th>
                                <td class="text-center">{{ $akademik->jumlah_item }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lampiran</th>
                                <td class="text-center">{{ $akademik->lampiran }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tahun Aktif</th>
                                <td class="text-center">({{ $akademik->tahun_surat }} - {{ $akademik->tahun_surat + $akademik->retensi }})</td>
                            </tr>
                            <tr>
                                <th scope="row">Keterangan</th>
                                <td class="text-center">
                                    @if ($akademik->keterangan === 'Aktif')
                                    <span class="badge badge-success">{{ $akademik->keterangan }}</span>
                                @elseif ($akademik->keterangan === 'Inaktif')
                                    <span class="badge badge-warning">{{ $akademik->keterangan }}</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Nasib Akhir</th>
                                <td class="text-center">{{ $akademik->nasibAkhir->nasib_akhir ?? '-' }}</td>
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
                    @if($akademik->file_path)
                        <!-- Menampilkan file PDF menggunakan iframe -->
                        <iframe src="{{ Storage::url($akademik->file_path) }}" type="application/pdf" width="100%" height="741px"></iframe>
                    @else
                        <p>Tidak ada file yang ditampilkan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
