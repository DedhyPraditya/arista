@extends('layouts.app')
@section('title', 'Daftar Lokasi Arsip')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Lokasi Arsip</h1>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" title="tambah Lokasi Arsip"><i class="fas fa-plus fa-sm"></i>
        </button>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 1500,
                showConfirmButton: false,
            });
        </script>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Lokasi Arsip</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Ruangan</th>
                            <th>Gedung</th>
                            <th>Lemari</th>
                            <th>Rak</th>
                            <th>Book</th>
                            <th>Folder</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lokasi as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}.</td>
                                <td>{{ $item->ruangan }}</td>
                                <td>{{ $item->gedung }}</td>
                                <td class="text-center">{{ $item->lemari }}</td>
                                <td class="text-center">{{ $item->rak }}</td>
                                <td class="text-center">{{ $item->book }}</td>
                                <td>{{ $item->folder }}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $item->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('lokasi.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus"><i class="fas fa-trash"></i></button>
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
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('lokasi.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lokasi Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Form Fields -->
                    @foreach (['ruangan', 'gedung', 'lemari', 'rak', 'book', 'folder'] as $field)
                        <div class="form-group">
                            <label for="{{ $field }}">{{ ucfirst($field) }}</label>
                            <input type="text" name="{{ $field }}" class="form-control" required>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-close"></i> Tutup</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
@foreach ($lokasi as $item)
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('lokasi.update', $item->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lokasi Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @foreach (['ruangan', 'gedung', 'lemari', 'rak', 'book', 'folder'] as $field)
                        <div class="form-group">
                            <label for="{{ $field }}">{{ ucfirst($field) }}</label>
                            <input type="text" name="{{ $field }}" class="form-control" value="{{ $item->$field }}" required>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-close"></i> Tutup</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Simpan</button>
                </div>
            </div>
        </form>
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
