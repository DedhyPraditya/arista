@extends('layouts.app')
@section('title', 'Edit File')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="nav-item">
        <i class="fas fa-fw fa-file"></i> / <a href="{{ route('files.index') }}">Manajemen File</a> / 
        <a href="{{ route('files.show', $file) }}">Detail File</a> / <span>Edit</span>
    </div>

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Edit File</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Informasi File</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('files.update', $file) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Deskripsi file (opsional)">{{ old('description', $file->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" 
                                       {{ old('is_public', $file->is_public) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    File dapat diakses publik
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Jika dicentang, file dapat diakses oleh semua pengguna.
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('files.show', $file) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi File Saat Ini</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Nama File:</strong></td>
                            <td>{{ $file->original_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ukuran:</strong></td>
                            <td>{{ $file->formatted_size }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tipe:</strong></td>
                            <td>{{ $file->mime_type }}</td>
                        </tr>
                        <tr>
                            <td><strong>Uploader:</strong></td>
                            <td>{{ $file->uploader->name ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Upload:</strong></td>
                            <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('files.download', $file) }}" class="btn btn-success btn-sm btn-block">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Perhatian</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        <i class="fas fa-info-circle"></i> 
                        Anda hanya dapat mengedit deskripsi dan status publik/private file. 
                        Untuk mengubah file itu sendiri, silakan hapus file ini dan upload file baru.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
