<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Styles -->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.css">
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <style>
        .chart-area {
            position: relative;
            height: 320px;
            width: 100%;
        }
        .chart-bar {
            position: relative;
            height: 350px;
            width: 100%;
        }
        .chart-pie {
            position: relative;
            height: 280px;
            width: 100%;
        }
        .card-hover {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: pointer;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2) !important;
        }
        a.text-decoration-none:hover {
            text-decoration: none !important;
        }
        /* Loading Overlay */
        #loading-overlay {
            position: fixed;
            top: 0; left: 0; right:0; bottom:0;
            background: rgba(255,255,255,0.7);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .spinner-border { width: 3rem; height: 3rem; }

        /* Notification Styles */
        .notification-item.unread {
            background-color: #f8f9fc;
            border-left: 3px solid #4e73df;
        }
        .notification-item:hover {
            background-color: #eaecf4;
        }
        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .badge-counter {
            position: absolute;
            transform: scale(0.7);
            transform-origin: top right;
            right: 0.25rem;
            margin-top: -0.25rem;
        }
        .dropdown-list {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
    @livewireStyles

</head>
<body id="page-top">
    <div id="wrapper">
        @include('layouts.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.header')
                <main>
                    @yield('content')
                </main>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3 font-weight-bold text-primary">Memproses...</p>
        </div>
    </div>

    <script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link href="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('sbadmin2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')
    @livewireScripts
    @stack('scripts')
    <script>
        // Tampilkan overlay saat submit form
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('loading-overlay');
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    // Jangan tampilkan jika form punya attribute data-no-loading
                    if (form.hasAttribute('data-no-loading')) return;
                    overlay.style.display = 'flex';
                });
            });
            // Sembunyikan overlay ketika halaman sudah dimuat ulang penuh (optional fallback)
            window.addEventListener('pageshow', function() {
                overlay.style.display = 'none';
            });

            // Notification System
            loadNotifications();

            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);

            // Handle notification dropdown toggle
            $('#alertsDropdown').on('click', function() {
                loadNotifications();
            });
        });

        function loadNotifications() {
            $.ajax({
                url: '/notifications/unread',
                method: 'GET',
                success: function(response) {
                    updateNotificationBadge(response.unread_count);
                    renderNotifications(response.notifications);
                },
                error: function() {
                    console.error('Failed to load notifications');
                }
            });
        }

        function updateNotificationBadge(count) {
            const badge = $('#notification-badge');
            if (count > 0) {
                badge.text(count > 99 ? '99+' : count).show();
            } else {
                badge.hide();
            }
        }

        function renderNotifications(notifications) {
            const container = $('#notifications-container');

            if (notifications.length === 0) {
                container.html(`
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-gray-300 mb-2"></i>
                        <p class="text-gray-500 mb-0">Tidak ada notifikasi</p>
                    </div>
                `);
                return;
            }

            let html = '';
            notifications.forEach(function(notification) {
                const iconClass = getIconClass(notification.type);
                const timeAgo = formatTimeAgo(notification.created_at);

                html += `
                    <a class="dropdown-item d-flex align-items-center notification-item ${notification.is_read ? 'read' : 'unread'}"
                       href="${notification.url || '#'}"
                       data-id="${notification.id}"
                       onclick="markAsRead(${notification.id})">
                        <div class="mr-3">
                            <div class="icon-circle bg-${iconClass}">
                                <i class="fas ${notification.icon} text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">${timeAgo}</div>
                            <span class="font-weight-bold">${notification.title}</span>
                            <div class="small">${notification.message}</div>
                        </div>
                    </a>
                `;
            });

            container.html(html);
        }

        function getIconClass(type) {
            const classes = {
                'info': 'primary',
                'success': 'success',
                'warning': 'warning',
                'danger': 'danger'
            };
            return classes[type] || 'primary';
        }

        function formatTimeAgo(datetime) {
            const date = new Date(datetime);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return 'Baru saja';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
            if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';

            return date.toLocaleDateString('id-ID');
        }

        function markAsRead(id) {
            $.ajax({
                url: `/notifications/${id}/read`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadNotifications();
                }
            });
        }

        function markAllAsRead() {
            $.ajax({
                url: '/notifications/mark-all-read',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadNotifications();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Semua notifikasi telah ditandai sebagai sudah dibaca',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>
</body>
</html>
