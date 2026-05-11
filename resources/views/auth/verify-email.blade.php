<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Play N Chill</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #3a1fa8;
            font-family: 'Nunito', sans-serif;
        }
        .card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            max-width: 460px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }
        .icon { font-size: 4rem; margin-bottom: 20px; }
        h2 {
            font-family: 'Fredoka One', cursive;
            color: #3a1fa8;
            font-size: 1.8rem;
            margin-bottom: 12px;
        }
        p { color: #666; font-size: 0.95rem; margin-bottom: 24px; line-height: 1.6; }
        .alert {
            background: #d4edda;
            color: #155724;
            padding: 10px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: #7c4dff;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Fredoka One', cursive;
            letter-spacing: 1px;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 12px;
            transition: 0.2s;
        }
        .btn:hover { background: #3a1fa8; color: white; }
        .btn-outline {
            background: transparent;
            border: 2px solid #7c4dff;
            color: #7c4dff;
        }
        .btn-outline:hover { background: #7c4dff; color: white; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">📧</div>
        <h2>Cek Email Kamu!</h2>
        <p>Kami sudah kirim link verifikasi ke email kamu. Klik link tersebut untuk mengaktifkan akun dan mulai booking.</p>

        @if(session('message'))
            <div class="alert">{{ session('message') }}</div>
        @endif

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('aktivasi.kirim-ulang') }}">
            @csrf
            <button type="submit" class="btn">Kirim Ulang Email Verifikasi</button>
        </form>

        <script>
    // Cek status verifikasi setiap 5 detik
            setInterval(function() {
                fetch('/check-verification')
                    .then(res => res.json())
                    .then(data => {
                        if (data.verified) {
                            window.location.href = '/home';
                        }
                    });
            }, 5000);
        </script>
        <!-- <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline">Logout</button>
        </form> -->
    </div>
</body>
</html>