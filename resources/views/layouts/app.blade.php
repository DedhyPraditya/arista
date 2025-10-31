<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

    <script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
    <script src="{{asset('vendor/chart.js/Chart.min.js0') }}"></script>
    <link href="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('sbadmin2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')
    @livewireScripts
</body>
</html>
