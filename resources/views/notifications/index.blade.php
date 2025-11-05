@extends('layouts.app')
@section('title', 'Pusat Notifikasi')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell mr-2"></i>Pusat Notifikasi
        </h1>
        <button onclick="markAllAsRead()" class="btn btn-primary btn-sm">
            <i class="fas fa-check-double mr-1"></i>Tandai Semua Sudah Dibaca
        </button>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Semua Notifikasi</h6>
                </div>
                <div class="card-body">
                    @forelse($notifications as $notification)
                        <div class="alert alert-{{ $notification->type }} {{ $notification->is_read ? 'alert-secondary' : '' }} d-flex align-items-start" role="alert">
                            <div class="mr-3">
                                <i class="fas {{ $notification->icon }} fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">
                                    {{ $notification->title }}
                                    @if(!$notification->is_read)
                                        <span class="badge badge-primary ml-2">Baru</span>
                                    @endif
                                </h6>
                                <p class="mb-1">{{ $notification->message }}</p>
                                <small class="text-muted">
                                    <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </small>
                                @if($notification->url)
                                    <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-primary ml-2">
                                        <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail
                                    </a>
                                @endif
                            </div>
                            <div>
                                @if(!$notification->is_read)
                                    <button onclick="markAsRead({{ $notification->id }})" class="btn btn-sm btn-success mr-1" title="Tandai sudah dibaca">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <button onclick="deleteNotification({{ $notification->id }})" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Tidak ada notifikasi</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteNotification(id) {
    Swal.fire({
        title: 'Hapus Notifikasi?',
        text: "Notifikasi akan dihapus permanen",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/notifications/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Notifikasi berhasil dihapus',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menghapus notifikasi'
                    });
                }
            });
        }
    });
}
</script>
@endpush
