<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-archive"></i>
        </div>
        <div class="sidebar-brand-text mx-3 text-center">
            <strong>Arista</strong>
        </div>
    </a>

    <hr class="sidebar-divider my-0">
    <!-- Sidebar User Info (Who is logged in) -->
    <div class="sidebar-user-info p-3">
        <div class="sidebar-user-name text-white text-center">
            <div class="sidebar-user-icon text-center mb-3">
                <img src="{{ asset('sbadmin2/img/undraw_profile.svg') }}" alt="User Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                {{-- <p class="mt-2 text-white" style="font-size: 14px;">Pengelola</p> --}}
            </div>
            <strong>{{ Auth::user()->name }}</strong>
        </div>
        {{-- <div class="sidebar-user-email text-white">
            {{ Auth::user()->email }}
        </div> --}}
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
    <div class="sidebar-heading"> Arsip Management
    </div>

    {{-- <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-pen"></i>
            <span>Entri Data Arsip</span>
        </a>
    </li> --}}

    <!-- Data Arsip Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDataArsip"
           aria-expanded="true" aria-controls="collapseDataArsip">
            <i class="fas fa-folder-open"></i>
            <span>Data Arsip</span>
        </a>
        <div id="collapseDataArsip" class="collapse" aria-labelledby="headingDataArsip" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded max-w-xs mx-auto">
                <h6 class="collapse-header">Management :</h6>
                <a class="collapse-item" href="{{ route('akademik.index') }}">Akademik</a>
                <a class="collapse-item" href="{{ route('hkt.index') }}">HKT</a>
                {{-- <hr class="sidebar-divider" style="background-color: rgb(119, 117, 117); height: 0.1px;"> --}}
                <a class="collapse-item" href="{{ route('keuangan.index') }}">Keuangan</a>
                <a class="collapse-item" href="{{ route('kelembagaan.index') }}">Kelembagaan</a>
                <a class="collapse-item" href="{{ route('kemahasiswaan.index') }}">Kemahasiswaan</a>
                <a class="collapse-item" href="{{ route('sdpt.index') }}">SDPT</a>
                {{-- <hr class="sidebar-divider" style="background-color: rgb(119, 117, 117); height: 0.1px;"> --}}
            </div>
        </div>
    </li>
    <!-- Riwayat Arsip Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRiwayatArsip"
           aria-expanded="true" aria-controls="collapseRiwayatArsip">
           <i class="fas fa-database"></i>
            <span>Data Master</span>
        </a>
        <div id="collapseRiwayatArsip" class="collapse" aria-labelledby="headingRiwayatArsip" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Data Master Options:</h6>
                <a class="collapse-item" href="{{ route('klasifikasi.index') }}"> Klasifikasi</a>
                <a class="collapse-item" href="{{ route('unit.index') }}">Unit Pengelolah</a>
                <a class="collapse-item" href="{{ route('lokasi.index') }}">Lokasi Arsip</a>
                <a class="collapse-item" href="{{ route('tingkat.index') }}">Tingkat Perkembangan</a>
                <a class="collapse-item" href="{{ route('nasib.index') }}">Nasib Akhir</a>
                <a class="collapse-item" href="#">Akses Keamanan</a>
            </div>
        </div>
    </li>

    <!-- Kategori Menu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKategori"
           aria-expanded="true" aria-controls="collapseKategori">
            <i class="fas fa-list"></i>
            <span>Kategori Arsip</span>
        </a>
        <div id="collapseKategori" class="collapse" aria-labelledby="headingKategori" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Kategori Arsip Options:</h6>
                <a class="collapse-item" href="#">Active</a>
                <a class="collapse-item" href="#">Incative</a>
            </div>
        </div>
    </li> --}}
    <hr class="sidebar-divider">
         <!-- Profile Link -->
         <li class="nav-item">
         <a class="nav-link" href="{{ route('profile.custom') }}">
             <i class="fas fa-fw fa-user"></i>
             <span>Profile</span>
         </a>
     </li>

     <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-white">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </li>

</ul>
