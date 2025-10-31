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
                            <th class="text-center">Nama</th>
                            <th class="text-center">Retensi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($klasifikasi as $item)
                            <tr>
                                <td class="text-center">{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td class="text-center">{{ $item->retensi }} tahun</td>
                                <td class="text-center">
                                    <!-- Edit Button to Trigger Edit Modal -->
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('klasifikasi.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="retensi">Retensi</label>
                        <input type="number" class="form-control" id="retensi" name="retensi" required>
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
                        <label for="kode">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="{{ old('kode', $item->kode) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $item->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="retensi">Retensi</label>
                        <input type="number" class="form-control" id="retensi" name="retensi" value="{{ old('retensi', $item->retensi) }}" required>
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
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection
