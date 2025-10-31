@extends('layouts.app')
@section('title', 'Tabel HKT')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar HKT</h1>
        <a href="{{ route('hkt.create') }}" class="btn btn-primary" title="Tambah HKT"><i class="fas fa-plus"></i></a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel Data HKT</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">No. Surat</th>
                                    <th class="text-center">Tanggal Surat</th>
                                    {{-- <th class="text-center">Tahun Surat</th> --}}
                                    <th class="text-center">Pencipta Arsip</th>
                                    {{-- <th class="text-center">Unit Pengelola</th> --}}
                                    <th class="text-center">Kode Klasifikasi</th>
                                    <th class="text-center">Prihal</th>
                                    {{-- <th class="text-center">Uraian Informasi</th> --}}
                                    <th class="text-center">Tingkat Perkembangan</th>
                                    <th class="text-center">Lokasi Arsip</th>
                                    {{-- <th class="text-center">Jumlah Item</th>
                                    <th class="text-center">Lampiran</th> --}}
                                    <th class="text-center">Tahun Aktif</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Nasib Akhir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hkts as $hkt)
                                    <tr>
                                        <td>{{ $hkt->nomor_surat }}</td>
                                        <td>{{ \Carbon\Carbon::parse($hkt->tanggal_surat)->format('d-m-Y') }}</td>
                                        {{-- <td>{{ $hkt->tahun_surat }}</td> --}}
                                        <td>{{ $hkt->pencipta_arsip ?? '-' }}</td>
                                        {{-- <td>{{ $hkt->unitPengelola->unit_pengelola ?? '-' }}</td> --}}
                                        <td>{{ $hkt->klasifikasi->nama ?? '-' }}</td>
                                        <td>{{ $hkt->prihal }}</td>
                                        {{-- <td>{{ $hkt->uraian_informasi }}</td> --}}
                                        <td class="text-center">{{ $hkt->tingkatPerkembangan->tingkat_perkembangan ?? '-' }}</td>
                                        <td>{{ $hkt->lokasiArsip->ruangan ?? '-' }}</td>
                                        {{-- <td>{{ $hkt->jumlah_item ?? '-' }}</td> --}}
                                        {{-- <td>{{ $hkt->lampiran ?? '-' }}</td> --}}
                                        <td>
                                            @if($hkt->retensi)
                                                {{ $hkt->tahun_surat }} - {{ $hkt->tahun_surat + $hkt->retensi }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($hkt->keterangan === 'Aktif')
                                                <span class="badge badge-success">{{ $hkt->keterangan }}</span>
                                            @elseif ($hkt->keterangan === 'Inaktif')
                                                <span class="badge badge-warning">{{ $hkt->keterangan }}</span>
                                            @else
                                                <span class="badge badge-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $hkt->nasibAkhir->nasib_akhir ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('hkt.show', $hkt->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('hkt.edit', $hkt) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('hkt.destroy', $hkt) }}" method="POST" style="display:inline;">
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
                                        <td colspan="15" class="text-center">Tidak ada data HKT.</td>
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
