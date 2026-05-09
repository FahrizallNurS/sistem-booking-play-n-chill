<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Play N Chill | Verifikasi Email</title>

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

    .card {
      position: relative;
      z-index: 1;
      background: #ffffff;
      border-radius: 24px;
      padding: 48px 40px 40px;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
      animation: slideUp 0.5s ease forwards;
      text-align: center;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .logo {
      font-family: 'Fredoka One', cursive;
      font-size: 2.8rem;
      color: #3a1fa8;
      letter-spacing: 1px;
      line-height: 1;
      margin-bottom: 28px;
    }

    .logo .n-icon {
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

    .email-icon {
      width: 72px; height: 72px;
      background: linear-gradient(135deg, #ede9fe, #ddd6fe);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }

    .email-icon i {
      font-size: 2rem;
      color: #7c4dff;
    }

    h2 {
      font-family: 'Fredoka One', cursive;
      color: #3a1fa8;
      font-size: 1.8rem;
      margin-bottom: 12px;
    }

    p {
      color: #666;
      font-size: 0.95rem;
      line-height: 1.6;
      margin-bottom: 24px;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 10px 16px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }

    .btn-primary {
      display: block;
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
      margin-bottom: 12px;
      transition: background 0.2s, transform 0.1s;
      text-decoration: none;
    }

    .btn-primary:hover { background: #3a1fa8; color: white; }
    .btn-primary:active { transform: scale(0.98); }

    .btn-outline {
      display: block;
      width: 100%;
      padding: 14px;
      background: transparent;
      color: #7c4dff;
      border: 2px solid #7c4dff;
      border-radius: 12px;
      font-size: 1rem;
      font-family: 'Fredoka One', cursive;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-outline:hover {
      background: #7c4dff;
      color: white;
    }

    .info-box {
      background: #f5f3ff;
      border: 1px solid #ddd6fe;
      border-radius: 12px;
      padding: 14px 16px;
      margin-bottom: 24px;
      font-size: 0.88rem;
      color: #5b21b6;
      text-align: left;
    }

    .info-box i { margin-right: 6px; }
  </style>
</head>
<body>

<div class="bg-pattern"></div>
<div class="shape-left-top"></div>
<div class="shape-left-bottom"></div>

<div class="card">

  <div class="logo">Play <span class="n-icon">N</span> Chill</div>

  <div class="email-icon">
    <i class="fas fa-envelope"></i>
  </div>

  <h2>Cek Email Kamu!</h2>
  <p>Kami sudah kirim link verifikasi ke email kamu. Klik link tersebut untuk mengaktifkan akun dan login.</p>

  @if(session('success') || session('message'))
    <div class="alert-success">
      {{ session('success') ?? session('message') }}
    </div>
  @endif

  <div class="info-box">
    <i class="fas fa-info-circle"></i>
    Tidak menemukan email? Cek folder <strong>Spam</strong> atau klik tombol di bawah untuk kirim ulang.
  </div>

  <form method="POST" action="{{ route('aktivasi.kirim-ulang') }}">
    @csrf
    <button type="submit" class="btn-primary">
        <i class="fas fa-paper-plane" style="margin-right:8px;"></i> Kirim Ulang Email
    </button>
  </form>

</div>

<script src="{{ asset('adminLTE/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>