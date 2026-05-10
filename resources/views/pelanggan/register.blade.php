<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Play N Chill | Daftar</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700&display=swap">
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
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-image: url('{{ asset("images/bg-segitiga.png") }}');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      opacity: 1.0;
      z-index: -1;
      pointer-events: none;
    }

    .shape-left-top {
      position: fixed;
      width: 100px; height: 130px;
      background-color: #c8e600;
      top: 160px; left: -20px;
      clip-path: polygon(0% 20%, 100% 0%, 100% 80%, 0% 100%);
      z-index: 0;
    }

    .shape-left-bottom {
      position: fixed;
      width: 120px; height: 110px;
      background-color: #c8e600;
      bottom: 80px; left: -10px;
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
      margin-bottom: 32px;
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
      width: 48px; height: 48px;
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
      margin-bottom: 20px;
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
      font-weight: 700;
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
      margin-top: 8px;
      margin-bottom: 20px;
    }

    .btn-login:hover { background: #3a1fa8; }
    .btn-login:active { transform: scale(0.98); }

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
<div class="shape-left-top"></div>
<div class="shape-left-bottom"></div>

<div class="login-box">

  <div class="login-logo">
    <h1>Play <span class="n-icon">N</span> Chill</h1>
  </div>

  @if ($errors->any())
    <div style="background:#fff0f0;border:1px solid #e63946;border-radius:10px;
                padding:12px 16px;margin-bottom:20px;color:#e63946;font-size:0.9rem;">
      {{ $errors->first() }}
    </div>
  @endif

  <form action="{{ route('register.post') }}" method="POST">
    @csrf

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-user"></i>
        <span>Username</span>
      </div>
      <input type="text" name="nama_pengguna"
        value="{{ old('nama_pengguna') }}"
        class="{{ $errors->has('nama_pengguna') ? 'is-invalid' : '' }}"
        required>
      @error('nama_pengguna')
        <div class="error-msg">{{ $message }}</div>
      @enderror
    </div>

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-envelope"></i>
        <span>Email</span>
      </div>
      <input type="email" name="email"
        value="{{ old('email') }}"
        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
        required>
      @error('email')
        <div class="error-msg">{{ $message }}</div>
      @enderror
    </div>

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-phone"></i>
        <span>Nomor Telepon</span>
      </div>
      <input type="text" name="no_hp"
        value="{{ old('no_hp') }}"
        class="{{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
        required>
      @error('no_hp')
        <div class="error-msg">{{ $message }}</div>
      @enderror
    </div>

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-lock"></i>
        <span>Password</span>
      </div>
      <input type="password" name="password"
        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
        required>
      @error('password')
        <div class="error-msg">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn-login">DAFTAR</button>
  </form>

  <p class="register-link">
    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
  </p>

</div>

<script src="{{ asset('adminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>