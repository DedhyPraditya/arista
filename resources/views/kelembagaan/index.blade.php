@extends('layouts.app')
@section('title', 'Tabel Kelembagaan')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Kelembagaan</h1>
        <a href="{{ route('kelembagaan.create') }}" class="btn btn-primary" title="Tambah Kelembagaan"><i class="fas fa-plus"></i></a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel Data Kelembagaan</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">Nomor Surat</th>
                                    <th class="text-center">Tanggal Surat</th>
                                    <th class="text-center">Tahun Surat</th>
                                    <th class="text-center">Pencipta Arsip</th>
                                    <th class="text-center">Unit Pengelola</th>
                                    <th class="text-center">Kode Klasifikasi</th>
                                    <th class="text-center">Prihal</th>
                                    {{-- <th class="text-center">Uraian Informasi</th> --}}
                                    <th class="text-center">Tingkat Perkembangan</th>
                                    <th class="text-center">Lokasi Arsip</th>
                                    {{-- <th class="text-center">Jumlah Item</th> --}}
                                    {{-- <th class="text-center">Lampiran</th> --}}
                                    <th class="text-center">Tahun Aktif</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Nasib Akhir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelembagaans as $data)
                                    <tr>
                                        <td>{{ $data->nomor_surat }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_surat)->format('d-m-Y') }}</td>
                                        <td>{{ $data->tahun_surat }}</td>
                                        <td>{{ $data->pencipta_arsip ?? '-' }}</td>
                                        <td>{{ $data->unitPengelola->unit_pengelola ?? '-' }}</td>
                                        <td>{{ $data->klasifikasi->nama ?? '-' }}</td>
                                        <td>{{ $data->prihal }}</td>
                                        {{-- <td>{{ $data->uraian_informasi }}</td> --}}
                                        <td>{{ $data->tingkatPerkembangan->tingkat_perkembangan ?? '-' }}</td>
                                        <td>{{ $data->lokasiArsip->ruangan ?? '-' }}</td>
                                        {{-- <td>{{ $data->jumlah_item ?? '-' }}</td> --}}
                                        {{-- <td>{{ $data->lampiran ?? '-' }}</td> --}}
                                        <td>
                                            @if($data->retensi)
                                                {{ $data->tahun_surat }} - {{ $data->tahun_surat + $data->retensi }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($data->keterangan === 'Aktif')
                                                <span class="badge badge-success">{{ $data->keterangan }}</span>
                                            @elseif ($data->keterangan === 'Inaktif')
                                                <span class="badge badge-warning">{{ $data->keterangan }}</span>
                                            @else
                                                <span class="badge badge-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $data->nasibAkhir->nasib_akhir ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('kelembagaan.show', $data->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('kelembagaan.edit', $data->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('kelembagaan.destroy', $data->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="16" class="text-center">Tidak ada data kelembagaan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
