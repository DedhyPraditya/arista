@extends('layouts.app')
@section('title', 'Tabel Keuangan')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Keuangan</h1>
        <a href="{{ route('keuangan.create') }}" class="btn btn-primary" title="Tambah Keuangan"><i class="fas fa-plus"></i></a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel Data Keuangan</h6>
                </div>
                <div class="card-body">
                    <!-- Filter/Search Form -->
                    <form method="GET" action="{{ route('keuangan.index') }}" class="mb-3">
                        <div class="form-row">
                            <div class="col-md-2 mb-2">
                                <input type="text" name="nomor_surat" value="{{ request('nomor_surat') }}" class="form-control form-control-sm" placeholder="Nomor Surat">
                            </div>
                            <div class="col-md-2 mb-2">
                                <input type="number" name="tahun_surat" value="{{ request('tahun_surat') }}" class="form-control form-control-sm" placeholder="Tahun">
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="unit_pengelola_id" class="form-control form-control-sm">
                                    <option value="">Unit Pengelola</option>
                                    @isset($unitPengelolas)
                                        @foreach($unitPengelolas as $u)
                                            <option value="{{ $u->id }}" {{ request('unit_pengelola_id') == $u->id ? 'selected' : '' }}>{{ $u->unit_pengelola }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="kode_klasifikasi_id" class="form-control form-control-sm">
                                    <option value="">Klasifikasi</option>
                                    @isset($klasifikasis)
                                        @foreach($klasifikasis as $k)
                                            <option value="{{ $k->id }}" {{ request('kode_klasifikasi_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="nasib_akhir_id" class="form-control form-control-sm">
                                    <option value="">Nasib Akhir</option>
                                    @isset($nasibAkhirs)
                                        @foreach($nasibAkhirs as $n)
                                            <option value="{{ $n->id }}" {{ request('nasib_akhir_id') == $n->id ? 'selected' : '' }}>{{ $n->nasib_akhir }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <select name="keterangan" class="form-control form-control-sm">
                                    <option value="">Keterangan</option>
                                    <option value="Aktif" {{ request('keterangan') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Inaktif" {{ request('keterangan') == 'Inaktif' ? 'selected' : '' }}>Inaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-2">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Cari</button>
                                <a href="{{ route('keuangan.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                            </div>
                        </div>
                    </form>
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
                                @forelse ($keuangans as $data)
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
                                            <a href="{{ route('keuangan.show', $data->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('keuangan.edit', $data) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('keuangan.destroy', $data) }}" method="POST" style="display:inline;" data-no-loading>
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $data->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="text-center">Tidak ada data Keuangan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $keuangans->firstItem() ?? 0 }} sampai {{ $keuangans->lastItem() ?? 0 }} dari {{ $keuangans->total() }} data
                        </div>
                        <div>
                            {{ $keuangans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(){
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin hapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(result => { if(result.isConfirmed){ form.submit(); } });
        });
    });
</script>
@endpush
