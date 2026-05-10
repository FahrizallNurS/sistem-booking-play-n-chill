<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Superadmin</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-dark" style="background-color: #734FFF;">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link text-white">Super Admin</span>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar elevation-4" style="background-color: #CAC1ED;">
        <a href="{{ url('superadmin/dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-bold" style="color:#1a1a2e">Play N Chill</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                    <li class="nav-header">MENU UTAMA</li>
                    <li class="nav-item">
                        <a href="{{ url('superadmin/dashboard') }}"
                            class="nav-link {{ request()->is('superadmin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">MANAJEMEN</li>
                    <li class="nav-item">
                        <a href="{{ url('superadmin/data-user') }}"
                            class="nav-link {{ request()->is('superadmin/data-user*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Kelola User</p>
                        </a>
                    </li>

                    <li class="nav-header">LAPORAN</li>
                    <li class="nav-item">
                        <a href="{{ url('superadmin/tinjau-laporan') }}"
                            class="nav-link {{ request()->is('superadmin/tinjau-laporan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Tinjau Laporan</p>
                        </a>
                    </li>

                    <li class="nav-header">AKUN</li>
                    <li class="nav-item">
                        <a href="{{ url('superadmin/profil') }}"
                            class="nav-link {{ request()->is('superadmin/profil*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Profil</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"
                            onclick="document.getElementById('logout-form-sa').submit()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
            @yield('content_header')
        </div>
    </div>
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <strong>Play N Chill &copy; {{ date('Y') }}</strong>
    </footer>
</div>

<form id="logout-form-sa" action="{{ route('logout') }}" method="POST" style="display:none">
    @csrf
</form>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@yield('js')
</body>
</html>