@extends('layouts.app')

@section('title', 'Nasib Akhir')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nasib Akhir</h1>
        <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus fa-sm text-white-150" title="Tambah Nasib Akhir"></i>
        </button>
    </div>

    <!-- SweetAlert -->
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

    <!-- Tabel Nasib Akhir -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Nasib Akhir</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nasib Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nasibAkhir as $index => $nasib)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <td>{{ $nasib->nasib_akhir }}</td>
                            <td class="text-center">
                                <!-- Edit Button -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $nasib->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('nasib.destroy', $nasib->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $nasib->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('nasib.update', $nasib->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Nasib Akhir</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nasib Akhir</label>
                                                <input type="text" name="nasib_akhir" class="form-control" value="{{ $nasib->nasib_akhir }}" required>
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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('nasib.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Nasib Akhir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nasib Akhir</label>
                        <input type="text" name="nasib_akhir" class="form-control" required>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable(); // Inisialisasi DataTables
    });
</script>
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
