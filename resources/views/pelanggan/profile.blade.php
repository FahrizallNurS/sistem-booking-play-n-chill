<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Profil - Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid px-4">

        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navMain">
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/booking') }}">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/gallery') }}">Gallery</a>
                </li>
                <li class="nav-item ms-2">
                    @auth
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

{{-- ═══ PROFILE PAGE ═══ --}}
<div class="profile-page">

    {{-- Dekorasi diamond kuning --}}
    <div class="diamond diamond-1"></div>
    <div class="diamond diamond-2"></div>
    <div class="diamond diamond-3"></div>
    <div class="diamond diamond-4"></div>

    {{-- Judul --}}
    <h1 class="profile-title">Profil</h1>

    <div class="profile-wrapper">

        {{-- ── KARTU PROFIL ── --}}
        <div class="profile-card">

            {{-- Avatar atas kartu --}}
            <div class="profile-avatar">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4
                             7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6
                             1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-3" style="font-size: .88rem;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Profil --}}
            <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="profile-field">
                    {{-- Icon user --}}
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                    <input type="text" name="name" id="inputName"
                        value="{{ auth()->user()->name }}"
                        placeholder="Nama lengkap" disabled>
                </div>

                {{-- Email --}}
                <div class="profile-field">
                    {{-- Icon envelope --}}
                    <svg viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="M2 7l10 7 10-7"/>
                    </svg>
                    <input type="email" name="email" id="inputEmail"
                        value="{{ auth()->user()->email }}"
                        placeholder="Email" disabled>
                </div>

                {{-- Nomor HP --}}
                <div class="profile-field">
                    {{-- Icon phone --}}
                    <svg viewBox="0 0 24 24">
                        <path d="M6.6 10.8a15.2 15.2 0 006.6 6.6l2.2-2.2a1 1 0 011-.25
                                c1.1.37 2.3.57 3.6.57a1 1 0 011 1V20a1 1 0 01-1 1
                                C8.6 21 3 15.4 3 8.5a1 1 0 011-1H8a1 1 0 011 1
                                c0 1.3.2 2.5.57 3.6a1 1 0 01-.25 1L6.6 10.8z"/>
                    </svg>
                    <input type="tel" name="phone" id="inputPhone"
                        value="{{ auth()->user()->phone ?? '' }}"
                        placeholder="Nomor HP" disabled>
                </div>


                <div class="password-section" id="passwordSection">
                    <hr style="border-color: #eee; margin: 16px 0;">

                    @if(auth()->user()->password === null)
                        {{-- Login via Google, belum punya password --}}
                        <p style="font-size:.8rem; color:#888; margin-bottom:12px;">
                            Akun Anda terhubung via Google. Buat password untuk login manual.
                        </p>

                        <div class="profile-field">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input type="password" name="new_password" placeholder="Buat password baru">
                        </div>

                        <div class="profile-field">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input type="password" name="new_password_confirmation" placeholder="Konfirmasi password baru">
                        </div>

                    @else
                        {{-- Sudah punya password, wajib isi password lama --}}
                        <p style="font-size:.8rem; color:#888; margin-bottom:12px;">
                            Kosongkan jika tidak ingin ganti password.
                        </p>

                        <div class="profile-field">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input type="password" name="current_password" placeholder="Password lama">
                        </div>

                        <div class="profile-field">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input type="password" name="new_password" placeholder="Password baru">
                        </div>

                        <div class="profile-field">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input type="password" name="new_password_confirmation" placeholder="Konfirmasi password baru">
                        </div>

                    @endif

                </div>

                {{-- Tombol Edit / Simpan --}}
                <button type="button" class="btn-edit" id="btnEdit" onclick="toggleEdit()">
                    Edit Profil
                </button>

                <button type="submit" class="btn-edit d-none" id="btnSave"
                        style="background: var(--purple-dark);">
                    Simpan
                </button>

            </form>
        </div>

        {{-- ── LIHAT STATUS BOOKING ── --}}
        <a href="{{ url('/booking/status') }}" class="btn-booking-status">
            Lihat Status Booking
            <span class="arrow">➜</span>
        </a>

    </div>{{-- end profile-wrapper --}}
</div>{{-- end profile-page --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleEdit() {
    const inputs          = document.querySelectorAll('#profileForm input');
    const btnEdit         = document.getElementById('btnEdit');
    const btnSave         = document.getElementById('btnSave');
    const passwordSection = document.getElementById('passwordSection');

    // Aktifkan semua input
    inputs.forEach(input => input.disabled = false);

    // Tampilkan section password
    passwordSection.style.display = 'block';

    // Ganti tombol
    btnEdit.classList.add('d-none');
    btnSave.classList.remove('d-none');
}
</script>

</body>
</html>