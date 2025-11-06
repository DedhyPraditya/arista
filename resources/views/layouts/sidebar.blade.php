<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}" style="padding: 1rem;">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-archive"></i>
        </div>
        <div class="sidebar-brand-text mx-2">
            <strong>ARISTA</strong>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Sidebar User Info (Who is logged in) -->
    <div class="sidebar-user-info p-2 text-center">
        <img src="{{ asset('sbadmin2/img/undraw_profile.svg') }}" alt="User" class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
        <div class="text-white">
            <strong style="font-size: 0.85rem;">{{ Auth::user()->name }}</strong>
        </div>
    </div>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard Link -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Beranda</span>
        </a>
    </li>

    <!-- Daftar File Link -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('files.index') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Daftar File</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Arsip Management</div>

    <!-- Data Arsip Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDataArsip"
           aria-expanded="true" aria-controls="collapseDataArsip">
            <i class="fas fa-folder-open"></i>
            <span>Data Arsip</span>
        </a>
        <div id="collapseDataArsip" class="collapse" aria-labelledby="headingDataArsip" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Management:</h6>
                <a class="collapse-item" href="{{ route('akademik.index') }}">Akademik</a>
                <a class="collapse-item" href="{{ route('akademik.index') }}">BMN & Sarpras PT</a>
                <a class="collapse-item" href="{{ route('hkt.index') }}">Hukum & Tatakelola</a>
                <a class="collapse-item" href="{{ route('akademik.index') }}">Humas & Kerja Sama</a>
                <a class="collapse-item" href="{{ route('akademik.index') }}">Kepegawaian & Org</a>
                <a class="collapse-item" href="{{ route('kemahasiswaan.index') }}">Kemahasiswaan</a>
                <a class="collapse-item" href="{{ route('kelembagaan.index') }}">Kelembagaan</a>
                <a class="collapse-item" href="{{ route('kemahasiswaan.index') }}">Kel. Pengendalian</a>
                <a class="collapse-item" href="{{ route('kemahasiswaan.index') }}">Kel. Pengembangan</a>
                <a class="collapse-item" href="{{ route('kemahasiswaan.index') }}">Penjaminan Mutu PT</a>
                <a class="collapse-item" href="{{ route('kemahasiswaan.index') }}">Persuratan & Arsip</a>
                <a class="collapse-item" href="{{ route('keuangan.index') }}">Keuangan</a>
                <a class="collapse-item" href="{{ route('sdpt.index') }}">SDPT</a>
                <a class="collapse-item" href="{{ route('kemahasiswaan.index') }}">Sistem Informasi</a>
            </div>
        </div>
    </li>

    <!-- Data Master Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRiwayatArsip"
           aria-expanded="true" aria-controls="collapseRiwayatArsip">
           <i class="fas fa-database"></i>
            <span>Data Master</span>
        </a>
        <div id="collapseRiwayatArsip" class="collapse" aria-labelledby="headingRiwayatArsip" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Master Data:</h6>
                <a class="collapse-item" href="{{ route('klasifikasi.index') }}">Klasifikasi</a>
                <a class="collapse-item" href="{{ route('unit.index') }}">Unit Pengelola</a>
                <a class="collapse-item" href="{{ route('lokasi.index') }}">Lokasi Arsip</a>
                <a class="collapse-item" href="{{ route('tingkat.index') }}">Tingkat Perkembangan</a>
                <a class="collapse-item" href="{{ route('nasib.index') }}">Nasib Akhir</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Profile Link -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile.custom') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profile</span>
        </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-white text-left w-100">
                <i class="fas fa-sign-out-alt fa-fw"></i>
                <span>Logout</span>
            </button>
        </form>
    </li>

</ul>
