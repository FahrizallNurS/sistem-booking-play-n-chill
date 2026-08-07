@extends('layouts.error') {{-- atau HTML manual biasa --}}
@section('content')
<div class="text-center py-5">
    <h2>Sesi Anda Telah Berakhir</h2>
    <p>Silakan login kembali untuk melanjutkan.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Login Ulang</a>
</div>
@endsection