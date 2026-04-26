<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Play N Chill | Register</title>

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

    /* KOTAK KUNING BACKGROUND (Sama persis dengan Login) */
    body::before,
    body::after {
      content: '';
      position: fixed;
      background-color: #c8e600;
      z-index: 0;
    }

    body::before {
      width: 280px;
      height: 220px;
      top: -40px;
      right: -40px;
      clip-path: polygon(30% 0%, 100% 0%, 100% 100%, 0% 100%);
    }

    body::after {
      width: 220px;
      height: 180px;
      bottom: -30px;
      right: 80px;
      clip-path: polygon(20% 0%, 100% 0%, 80% 100%, 0% 100%);
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

    /* CARD REGISTER */
    .login-box {
      position: relative;
      z-index: 1;
      background: #ffffff;
      border-radius: 24px;
      padding: 40px;
      width: 100%;
      max-width: 480px; /* Sedikit lebih lebar untuk form lebih panjang */
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
      animation: slideUp 0.5s ease forwards;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .login-logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .login-logo h1 {
      font-family: 'Fredoka One', cursive;
      font-size: 2.5rem;
      color: #3a1fa8;
      line-height: 1;
    }

    .login-logo h1 .n-icon {
      display: inline-block;
      background: linear-gradient(135deg, #ff6b35, #e63946);
      color: white;
      font-size: 1.8rem;
      width: 42px;
      height: 42px;
      border-radius: 10px;
      line-height: 42px;
      text-align: center;
      vertical-align: middle;
      margin: 0 4px;
      transform: rotate(-5deg);
    }

    .field-label {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #3a1fa8;
      font-weight: 700;
      font-size: 0.95rem;
      margin-bottom: 6px;
    }

    .input-group {
      margin-bottom: 18px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #7c4dff;
      border-radius: 12px;
      font-size: 1rem;
      font-family: 'Nunito', sans-serif;
      outline: none;
      transition: all 0.2s;
    }

    .input-group input:focus {
      border-color: #3a1fa8;
      box-shadow: 0 0 0 3px rgba(58, 31, 168, 0.12);
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: #7c4dff;
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      font-family: 'Fredoka One', cursive;
      letter-spacing: 2px;
      cursor: pointer;
      transition: 0.2s;
      margin-top: 10px;
      margin-bottom: 20px;
    }

    .btn-login:hover { background: #3a1fa8; }

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
  </style>
</head>
<body>

<div class="shape-left-top"></div>
<div class="shape-left-bottom"></div>

<div class="login-box">

  <div class="login-logo">
    <h1>Play <span class="n-icon">N</span> Chill</h1>
  </div>

  <form action="{{ route('register.post') }}" method="post">
    @csrf

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-user"></i>
        <span>Username</span>
      </div>
      <input type="text" name="nama_pengguna" value="{{ old('nama_pengguna') }}" 
             style="{{ $errors->has('nama_pengguna') ? 'border-color: #e63946;' : '' }}" required>
      @error('nama_pengguna')
        <small style="color: #e63946; font-weight: 700;">{{ $message }}</small>
      @enderror
    </div>

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-envelope"></i>
        <span>Email</span>
      </div>
      <input type="email" name="email" value="{{ old('email') }}"
             style="{{ $errors->has('email') ? 'border-color: #e63946;' : '' }}" required>
      @error('email')
        <small style="color: #e63946; font-weight: 700;">{{ $message }}</small>
      @enderror
    </div>

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-phone"></i>
        <span>Nomor Telephone</span>
      </div>
      <input type="text" name="no_hp" value="{{ old('no_hp') }}"
             style="{{ $errors->has('no_hp') ? 'border-color: #e63946;' : '' }}" required>
      @error('no_hp')
        <small style="color: #e63946; font-weight: 700;">{{ $message }}</small>
      @enderror
    </div>

    <div class="input-group">
      <div class="field-label">
        <i class="fas fa-lock"></i>
        <span>Password</span>
      </div>
      <input type="password" name="password"
             style="{{ $errors->has('password') ? 'border-color: #e63946;' : '' }}" required>
      @error('password')
        <small style="color: #e63946; font-weight: 700;">{{ $message }}</small>
      @enderror
    </div>

    <button type="submit" class="btn-login">DAFTAR</button>
</form>
    <p class="register-link">
        Sudah punya akun? <a href="{{ route('login') }}">Login</a>
     </p>
</div>
</body>
</html>