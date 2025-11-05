@extends('layouts.app')
@section('title', 'Daftar Klasifikasi')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Klasifikasi</h1>
        <!-- Button to trigger Create Modal -->
        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createModal">
            <i class="fas fa-plus fa-sm text-white-150" title="Tambah data Klasifikasi"></i>
        </button>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('klasifikasi.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama klasifikasi..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit">Cari</button>
            </div>
        </div>
    </form>
    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Klasifikasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Urusan</th>
                            <th class="text-center">Sub Urusan</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Retensi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($klasifikasi as $item)
                            <tr>
                                <td class="text-center"><strong>{{ $item->kode }}</strong></td>
                                <td>
                                    @if($item->urusan)
                                        <span class="badge badge-primary badge-pill">Urusan</span>
                                        <br><small>{{ $item->urusan }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->sub_urusan)
                                        <span class="badge badge-info badge-pill">Sub-urusan</span>
                                        <br><small>{{ Str::limit($item->sub_urusan, 80) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->nama }}</td>
                                <td class="text-center">
                                    @if($item->retensi !== null)
                                        <span class="badge badge-secondary">{{ $item->retensi }} th</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Edit Button to Trigger Edit Modal -->
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('klasifikasi.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item->id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data klasifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $klasifikasi->firstItem() ?? 0 }} sampai {{ $klasifikasi->lastItem() ?? 0 }} dari {{ $klasifikasi->total() }} data
                </div>
                <div>
                    {{ $klasifikasi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Klasifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('klasifikasi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode">Kode Klasifikasi</label>
                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh: HK.00.01 atau PL.00.01.A" required>
                        <small class="form-text text-muted">Gunakan format bertingkat dengan titik. Parent otomatis dihitung.</small>
                        <div id="hierarchyHint" class="mt-1 small text-info"></div>
                    </div>
                    <div class="form-group">
                        <label for="urusan">
                            <span class="badge badge-primary badge-pill">Urusan</span> Urusan Utama
                        </label>
                        <input type="text" class="form-control" id="urusan" name="urusan" placeholder="Contoh: Pokok-Pokok Kebijakan Strategis">
                    </div>
                    <div class="form-group">
                        <label for="sub_urusan">
                            <span class="badge badge-info badge-pill">Sub-urusan</span> Sub Urusan / Detail
                        </label>
                        <textarea class="form-control" id="sub_urusan" name="sub_urusan" rows="2" placeholder="Contoh: Rencana Pembangunan Jangka Panjang (RPJP)"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama/Judul</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="retensi">Retensi (Tahun) <span class="badge badge-secondary">Optional</span></label>
                        <input type="number" class="form-control" id="retensi" name="retensi" placeholder="Isi hanya untuk klasifikasi leaf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-close"></i> Tutup</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach ($klasifikasi as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Klasifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('klasifikasi.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode">Kode Klasifikasi</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="{{ old('kode', $item->kode) }}" required>
                        <small class="form-text text-muted">Format hierarki tetap: segmen dipisah titik.</small>
                    </div>
                    <div class="form-group">
                        <label for="urusan">
                            <span class="badge badge-primary badge-pill">Urusan</span> Urusan Utama
                        </label>
                        <input type="text" class="form-control" id="urusan" name="urusan" value="{{ old('urusan', $item->urusan) }}">
                        <small class="form-text text-muted">Kategori utama dari klasifikasi (A, B, C, dst)</small>
                    </div>
                    <div class="form-group">
                        <label for="sub_urusan">
                            <span class="badge badge-info badge-pill">Sub-urusan</span> Sub Urusan / Detail
                        </label>
                        <textarea class="form-control" id="sub_urusan" name="sub_urusan" rows="2">{{ old('sub_urusan', $item->sub_urusan) }}</textarea>
                        <small class="form-text text-muted">Detail atau penjabaran dari urusan utama (1, 2, 3, dst atau a, b, c, dst)</small>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama/Judul</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $item->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="retensi">Retensi (Tahun) <span class="badge badge-secondary">Optional</span></label>
                        <input type="number" class="form-control" id="retensi" name="retensi" value="{{ old('retensi', $item->retensi) }}" placeholder="Isi hanya untuk leaf">
                        <small class="form-text text-muted">Biarkan kosong jika menjadi kategori induk.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-close"></i> Tutup</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    // Live hint parent & level untuk modal create
    document.addEventListener('DOMContentLoaded', () => {
        const kodeInput = document.getElementById('kode');
        const hint = document.getElementById('hierarchyHint');
        if (kodeInput && hint) {
            const updateHint = () => {
                const raw = kodeInput.value.trim();
                if (!raw) { hint.textContent = ''; return; }
                const segments = raw.split('.');
                const level = segments.length - 1;
                const parent = segments.length > 1 ? segments.slice(0, -1).join('.') : '(root)';
                hint.innerHTML = `<span class="badge badge-info">Level ${level}</span> Parent: <strong>${parent}</strong>`;
            };
            kodeInput.addEventListener('input', updateHint);
            updateHint();
        }
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
