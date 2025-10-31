@extends('layouts.app')
@section('title', 'Daftar Pencipta Arsip')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Pencipta Arsip</h1>
            <!-- Button to trigger Create Modal -->
            <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus fa-sm text-white-150" title="Tambah Pencipta Arsip"></i>
            </button>
        </div>

        <!-- Alert SweetAlert -->
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        @endif

        <!-- Tabel Arsip -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pencipta Arsip</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Unit Kerja</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penciptaArsip as $index => $item)
                                <tr>
                                    <th class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_departemen }}</td>
                                    <th class="text-center">
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $item->id }}" title="Edit"><i class="fas fa-edit"></i></button>

                                        <form action="{{ route('pencipta_arsip.destroy', $item->id) }}" method="POST" style="display: inline-block;">
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
                    <h5 class="modal-title" id="createModalLabel">Tambah Pencipta Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('pencipta_arsip.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_departemen">Unit Kerja</label>
                            <input type="text" class="form-control" name="nama_departemen" required>
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
    @foreach($penciptaArsip as $item)
        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Pencipta Arsip</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('pencipta_arsip.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_departemen">Unit Kerja</label>
                                <input type="text" class="form-control" name="nama_departemen" value="{{ $item->nama_departemen }}" required>
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
@endsection

@section('scripts')
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
