@extends('layouts.app')
@section('title', 'Daftar Unit Pengelola')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Unit Pengelola</h1>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" title="Tambah Unit">
            <i class="fas fa-plus fa-sm"></i>
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Unit Pengelola</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Unit Pengelola</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $index => $unit)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}.</td>
                                <td>{{ $unit->unit_pengelola }}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $unit->id }}" title="Edit"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" style="display:inline;" title="Hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></button>
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
        <form method="POST" action="{{ route('unit.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Unit Pengelola</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="unit_pengelola">Unit Pengelola</label>
                        <input type="text" name="unit_pengelola" class="form-control" required>
                    </div>
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
@foreach ($units as $unit)
<div class="modal fade" id="editModal{{ $unit->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('unit.update', $unit->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Unit Pengelola</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="unit_pengelola">Unit Pengelola</label>
                        <input type="text" name="unit_pengelola" class="form-control" value="{{ $unit->unit_pengelola }}" required>
                    </div>
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
