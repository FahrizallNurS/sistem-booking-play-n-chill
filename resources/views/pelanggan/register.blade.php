<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: rgb(93, 10, 172);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: rgb(237, 237, 238);
            padding: 30px;
            border-radius: 15px;
            width: 320px;
            box-shadow: 0 5px 25px rgb(93, 10, 172);
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: rgb(93, 10, 172);
            margin-bottom: 20px;
        }
        label {
            font-size: 12px;
            color: rgb(93, 10, 172);
        }
        .label-group {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }
        .label-group img {
            width: 16px;
        }
        input {
            width: 100%;
            padding: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: rgb(93, 10, 172);
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .google-btn {
            margin-top: 10px;
            background: white;
            color: rgb(93, 10, 172);
            border: 1px solid #ccc;
        }
        .bottom-text {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            font-size: 11px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="title">Play N Chill</div>

    {{-- pesan sukses --}}
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        
        <!-- Username -->
        <div class="label-group">
            <img src="{{ asset('gambar/profil.png') }}">
            <label>Username</label>
        </div>
        <input type="text" name="nama" value="{{ old('nama') }}">
        @error('nama') <div class="error">{{ $message }}</div> @enderror

        <!-- Email -->
        <div class="label-group">
            <img src="{{ asset('gambar/email.png') }}">
            <label>Email</label>
        </div>
        <input type="email" name="email" value="{{ old('email') }}">
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <!-- Telephone -->
        <div class="label-group">
            <img src="{{ asset('gambar/telephone.png') }}">
            <label>Nomor Telephone</label>
        </div>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}">
        @error('no_hp') <div class="error">{{ $message }}</div> @enderror

        <!-- Password -->
        <div class="label-group">
            <img src="{{ asset('gambar/key.png') }}">
            <label>Password</label>
        </div>
        <input type="password" name="password">
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <!-- tombol daftar -->
        <button type="submit">Daftar</button>
    </form>
    <div class="bottom-text">
    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
    </div>
</div>

</body>
</html>