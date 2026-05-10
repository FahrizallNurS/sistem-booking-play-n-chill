<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Play N Chill | Log in</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminLTE/plugins/fontawesome-free/css/all.min.css') }}">

  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #3a1fa8;
      font-family: 'Nunito', sans-serif;
      overflow: hidden;
      position: relative;
    }

    .bg-pattern {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('{{ asset("images/bg-segitiga.png") }}');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        opacity: 1.0; /* Atur transparansi agar tidak menutupi kotak login */
        z-index: -1; /* Di bawah kotak login, tapi di atas warna body */
        pointer-events: none; /* Agar tidak bisa diklik/mengganggu input */
    }

    .shape-left-top {
      position: fixed;
      width: 100px;
      height: 130px;
      background-color: #c8e600;
      top: 160px;
      left: -20px;
      clip-path: polygon(0% 20%, 100% 0%, 100% 80%, 0% 100%);
      z-index: 0;
    }

    .shape-left-bottom {
      position: fixed;
      width: 120px;
      height: 110px;
      background-color: #c8e600;
      bottom: 80px;
      left: -10px;
      clip-path: polygon(0% 20%, 100% 0%, 100% 80%, 0% 100%);
      z-index: 0;
    }

    .login-box {
      position: relative;
      z-index: 1;
      background: #ffffff;
      border-radius: 24px;
      padding: 48px 40px 40px;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
      animation: slideUp 0.5s ease forwards;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .login-logo {
      text-align: center;
      margin-bottom: 40px;
    }

    .login-logo h1 {
      font-family: 'Fredoka One', cursive;
      font-size: 2.8rem;
      color: #3a1fa8;
      letter-spacing: 1px;
      line-height: 1;
    }

    .login-logo h1 .n-icon {
      display: inline-block;
      background: linear-gradient(135deg, #ff6b35, #e63946);
      color: white;
      font-size: 2rem;
      width: 48px;
      height: 48px;
      border-radius: 12px;
      line-height: 48px;
      text-align: center;
      vertical-align: middle;
      margin: 0 4px;
      transform: rotate(-5deg);
      box-shadow: 3px 3px 0 rgba(0,0,0,0.15);
    }

    .field-label {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #3a1fa8;
      font-weight: 700;
      font-size: 1.05rem;
      margin-bottom: 8px;
    }

    .field-label i {
      font-size: 1.2rem;
      color: #3a1fa8;
    }

    .input-group {
      margin-bottom: 24px;
    }

    .input-group input {
      width: 100%;
      padding: 14px 16px;
      border: 2px solid #7c4dff;
      border-radius: 12px;
      font-size: 1rem;
      font-family: 'Nunito', sans-serif;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      color: #333;
    }

    .input-group input:focus {
      border-color: #3a1fa8;
      box-shadow: 0 0 0 3px rgba(58, 31, 168, 0.12);
    }

    .input-group input.is-invalid {
      border-color: #e63946;
    }

    .error-msg {
      color: #e63946;
      font-size: 0.85rem;
      margin-top: 6px;
    }

    .btn-login {
      width: 100%;
      padding: 16px;
      background: #7c4dff;
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.2rem;
      font-family: 'Fredoka One', cursive;
      letter-spacing: 2px;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
      margin-bottom: 20px;
    }

    .btn-login:hover { background: #3a1fa8; }
    .btn-login:active { transform: scale(0.98); }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      color: #aaa;
      font-size: 0.9rem;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e0e0e0;
    }

    .btn-google {
      width: 100%;
      padding: 14px;
      background: #ffffff;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      font-size: 1rem;
      font-family: 'Nunito', sans-serif;
      font-weight: 600;
      color: #444;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: border-color 0.2s, box-shadow 0.2s;
      margin-bottom: 24px;
      text-decoration: none;
    }

    .btn-google:hover {
      border-color: #aaa;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .google-icon { width: 22px; height: 22px; }

    .register-link {
      text-align: center;
      font-size: 0.95rem;
      color: #666;
    }

    .register-link a {
      color: #7c4dff;
      font-weight: 700;
      text-decoration: none;
    }

    .register-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="bg-pattern"></div>

<div class="login-box">

  <div class="login-logo">
    <h1>Play <span class="n-icon">N</span> Chill</h1>
  </div>


  @if ($errors->any())
    <div style="background:#fff0f0;border:1px solid #e63946;border-radius:10px;padding:12px 16px;margin-bottom:20px;color:#e63946;font-size:0.9rem;">
      {{ $errors->first() }}
    </div>
  @endif
 
  <form action="{{ route('login.post') }}" method="post">
    @csrf

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-envelope"></i>
        <span>Email</span>
      </div>
      <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
        required
      >
      @error('email')
        <div class="error-msg">{{ $message }}</div>
      @enderror
    </div>

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-lock"></i>
        <span>Password</span>
      </div>
      <input
        type="password"
        name="password"
        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
        required
      >
      @error('password')
        <div class="error-msg">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn-login">LOGIN</button>

  <div style="text-align:right; margin-top: -12px; margin-bottom: 16px;">
      <a href="{{ route('password.request') }}" style="color:#7c4dff; font-size:0.85rem; text-decoration:none;">Lupa password?</a>
  </div>

  </form>

  <div class="divider">or</div>

  {{-- Tombol Google pakai route('login.google') --}}
  <a href="{{ route('login.google') }}" class="btn-google">
    <svg class="google-icon" viewBox="0 0 48 48">
      <path fill="#EA4335" d="M24 9.5c3.1 0 5.8 1.1 8 2.9l6-6C34.5 3.1 29.6 1 24 1 14.8 1 7 6.7 3.7 14.7l7 5.4C12.4 13.6 17.7 9.5 24 9.5z"/>
      <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.4 5.7c4.3-4 6.8-9.9 6.8-16.9z"/>
      <path fill="#FBBC05" d="M10.7 28.4A14.6 14.6 0 0 1 9.5 24c0-1.5.2-3 .6-4.4l-7-5.4A23.9 23.9 0 0 0 .5 24c0 3.9.9 7.5 2.6 10.8l7.6-6.4z"/>
      <path fill="#34A853" d="M24 46.5c5.6 0 10.3-1.8 13.7-5l-7.4-5.7c-1.9 1.3-4.4 2-6.3 2-6.3 0-11.6-4.2-13.5-9.9l-7.6 6.4C7.2 41 15 46.5 24 46.5z"/>
    </svg>
    Login dengan google
  </a>

  <p class="register-link">
    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
  </p>

<script src="{{ asset('adminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
<!-- login -->