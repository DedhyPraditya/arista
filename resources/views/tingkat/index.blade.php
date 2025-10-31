@extends('layouts.app')
@section('title', 'Tingkat Perkembangan')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tingkat Perkembangan</h1>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" title="Tambah Tingkat Perkembangan">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <!-- SweetAlert -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 1500,
                showConfirmButton: false,
            });
        </script>
    @endif

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Tingkat Perkembangan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Tingkat Perkembangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tingkat as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <td>{{ $item->tingkat_perkembangan }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $item->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('tingkat.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('tingkat.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Tingkat Perkembangan</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Tingkat Perkembangan</label>
                                                <input type="text" name="tingkat_perkembangan" class="form-control" value="{{ $item->tingkat_perkembangan }}" required>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('tingkat.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tingkat Perkembangan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tingkat Perkembangan</label>
                        <input type="text" name="tingkat_perkembangan" class="form-control" required>
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
@endsection
