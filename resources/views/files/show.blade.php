@extends('layouts.app')
@section('title', 'Detail File')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="nav-item">
        <i class="fas fa-fw fa-file"></i> / <a href="{{ route('files.index') }}">Manajemen File</a> / <span>Detail File</span>
    </div>

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Detail File</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi File</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama File:</strong></td>
                                    <td>{{ $file->original_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ukuran:</strong></td>
                                    <td>{{ $file->formatted_size }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe File:</strong></td>
                                    <td>{{ $file->mime_type }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($file->is_public)
                                            <span class="badge badge-success">Public</span>
                                        @else
                                            <span class="badge badge-warning">Private</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Uploader:</strong></td>
                                    <td>{{ $file->uploader->name ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Upload:</strong></td>
                                    <td>{{ $file->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir Diupdate:</strong></td>
                                    <td>{{ $file->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>ID File:</strong></td>
                                    <td><code>{{ $file->id }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($file->description)
                    <div class="mt-3">
                        <h6><strong>Deskripsi:</strong></h6>
                        <p class="text-muted">{{ $file->description }}</p>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('files.download', $file) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download File
                        </a>
                        <a href="{{ route('files.edit', $file) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('files.destroy', $file) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        <a href="{{ route('files.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Preview File</h6>
                </div>
                <div class="card-body text-center">
                    @if($file->isPdf())
                        <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                        <p class="text-muted">File PDF</p>
                        <a href="{{ $file->url }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                    @elseif($file->isImage())
                        <img src="{{ $file->url }}" alt="{{ $file->original_name }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                        <p class="text-muted">File Gambar</p>
                    @else
                        <i class="fas fa-file fa-5x text-secondary mb-3"></i>
                        <p class="text-muted">File Dokumen</p>
                    @endif
                </div>
            </div>
            
            <div class="card shadow mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('files.download', $file) }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <a href="{{ route('files.edit', $file) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit Info
                        </a>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="copyToClipboard('{{ $file->url }}')">
                            <i class="fas fa-copy"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link berhasil disalin ke clipboard!');
    }, function(err) {
        console.error('Gagal menyalin link: ', err);
    });
}
</script>
@endsection
