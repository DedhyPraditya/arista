@extends('layouts.app')
@section('title', 'Upload File')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="nav-item">
        <i class="fas fa-fw fa-file"></i> / <a href="{{ route('files.index') }}">Manajemen File</a> / <span>Upload File</span>
    </div>

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Upload File Baru</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Upload File</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="file">File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Format yang diperbolehkan: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 10MB.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Deskripsi file (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" 
                                       {{ old('is_public') ? 'checked' : '' }}>
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
                                <i class="fas fa-upload"></i> Upload File
                            </button>
                            <a href="{{ route('files.index') }}" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
                </div>
                <div class="card-body">
                    <h6>Format File yang Didukung:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-file-pdf text-danger"></i> PDF Documents</li>
                        <li><i class="fas fa-file-word text-primary"></i> Word Documents</li>
                        <li><i class="fas fa-file-image text-info"></i> Image Files</li>
                    </ul>
                    
                    <h6 class="mt-3">Ukuran Maksimal:</h6>
                    <p class="text-muted">10 MB per file</p>
                    
                    <h6 class="mt-3">Tips:</h6>
                    <ul class="list-unstyled text-muted">
                        <li>• Gunakan nama file yang deskriptif</li>
                        <li>• Tambahkan deskripsi untuk memudahkan pencarian</li>
                        <li>• Pilih status publik/private sesuai kebutuhan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
