@extends('layouts.app')
@section('title', 'Daftar File')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="nav-item">
        <i class="fas fa-fw fa-list"></i> / <span>Daftar File</span>
    </div>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar File Arsip</h1>
        <!-- <div>
            <a href="{{ route('files.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Upload File
            </a>
        </div> -->
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total File</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $paginatedData->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">HKT</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paginatedData->where('archive_type', 'HKT')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Keuangan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paginatedData->where('archive_type', 'Keuangan')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kelembagaan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paginatedData->where('archive_type', 'Kelembagaan')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kemahasiswaan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paginatedData->where('archive_type', 'Kemahasiswaan')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Akademik</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paginatedData->where('archive_type', 'Akademik')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2 untuk SDPT -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">SDPT</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paginatedData->where('archive_type', 'SDPT')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar File Arsip</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Jenis Arsip</th>
                            <th>Judul Arsip</th>
                            <th>Nomor Surat</th>
                            <th>Unit Pengelola</th>
                            <th>Ukuran</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginatedData as $index => $file)
                        <tr>
                            <td>{{ $paginatedData->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                    <span title="{{ $file->original_name }}">{{ Str::limit($file->original_name, 30) }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($file->archive_type) {
                                        'HKT' => 'badge-primary',
                                        'Keuangan' => 'badge-success',
                                        'Kelembagaan' => 'badge-info',
                                        'Kemahasiswaan' => 'badge-warning',
                                        'Akademik' => 'badge-danger',
                                        'SDPT' => 'badge-secondary',
                                        default => 'badge-light'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $file->archive_type }}</span>
                            </td>
                            <td>
                                <span title="{{ $file->archive_title }}">{{ Str::limit($file->archive_title, 40) }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $file->archive_number }}</small>
                            </td>
                            <td>
                                <small>{{ $file->unit_pengelola }}</small>
                            </td>
                            <td>
                                @php
                                    $bytes = $file->file_size;
                                    $units = ['B', 'KB', 'MB', 'GB'];
                                    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                                        $bytes /= 1024;
                                    }
                                    $formattedSize = round($bytes, 2) . ' ' . $units[$i];
                                @endphp
                                <small>{{ $formattedSize }}</small>
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-info btn-sm" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ asset('storage/' . $file->file_path) }}" download="{{ $file->original_name }}" class="btn btn-success btn-sm" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @php
                                        $editRoute = match($file->archive_type) {
                                            'HKT' => route('hkt.edit', str_replace('hkt_', '', $file->id)),
                                            'Keuangan' => route('keuangan.edit', str_replace('keuangan_', '', $file->id)),
                                            'Kelembagaan' => route('kelembagaan.edit', str_replace('kelembagaan_', '', $file->id)),
                                            'Kemahasiswaan' => route('kemahasiswaan.edit', str_replace('kemahasiswaan_', '', $file->id)),
                                            'Akademik' => route('akademik.edit', str_replace('akademik_', '', $file->id)),
                                            'SDPT' => route('sdpt.edit', str_replace('sdpt_', '', $file->id)),
                                            default => '#'
                                        };
                                    @endphp
                                    <a href="{{ $editRoute }}" class="btn btn-warning btn-sm" title="Edit Arsip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada file ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $paginatedData->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
