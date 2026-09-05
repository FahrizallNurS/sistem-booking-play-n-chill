{{-- ═══ NAVBAR ═══ --}}
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-4">

        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('gambar/Logo-PNC01.png') }}" alt="Play N Chill" height="48">
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navMain">
            <ul class="navbar-nav align-items-center gap-1">
                
                {{-- Home --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'nav-btn-active' : '' }}" href="{{ url('/') }}">Home</a>
                </li>

                {{-- Booking --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('booking*') ? 'nav-btn-active' : '' }}" href="{{ url('/booking') }}">Booking</a>
                </li>

                {{-- Menu F&B --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('menu-fb*') ? 'nav-btn-active' : '' }}" href="{{ url('/menu-fb') }}">Menu F&B</a>
                </li>

                {{-- Galeri --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('galeri*') ? 'nav-btn-active' : '' }}" href="{{ url('/galeri') }}">Galeri</a>
                </li>

                {{-- Tentang Kami --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('tentang-kami*') ? 'nav-btn-active' : '' }}" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                </li>

                {{-- Bagian Auth (Login / Profil) yang sudah dirapikan tag <li> nya --}}
                <li class="nav-item ms-2">
                    @guest
                        {{-- Belum login: tampilkan tombol Login --}}
                        <a class="nav-link nav-btn-active" href="{{ url('/login') }}"
                        style="background-color: var(--orange) !important;">
                            Login
                        </a>
                    @endguest

                    @auth
                        {{-- Sudah login: tampilkan avatar + dropdown --}}
                        <div class="dropdown">
                            <div class="nav-avatar" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4
                                            7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6
                                            1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"
                                        fill="var(--purple-dark)"/>
                                </svg>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                aria-labelledby="userDropdown">
                                <li>
                                    <span class="dropdown-item-text fw-bold">
                                        {{ auth()->user()->name }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/profile') }}">
                                        Profil Saya
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>