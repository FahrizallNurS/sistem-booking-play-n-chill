@extends('adminlte::page')

@section('title', 'Kelola Game')

@section('content_header')
    <h1>Kelola Game</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Game</h3>
            <div class="card-tools">
                <a href="{{ route('admin.game.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Game
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Game</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permainans as $i => $permainan)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if($permainan->gambar)
                                <img src="{{ asset('storage/' . $permainan->gambar) }}"
                                    alt="{{ $permainan->nama_permainan }}"
                                    class="img-thumbnail" width="60" height="60"
                                    style="object-fit:cover;">
                            @else
                                <img src="https://via.placeholder.com/60x60"
                                    alt="no image" class="img-thumbnail" width="60">
                            @endif
                        </td>
                        <td>{{ $permainan->nama_permainan }}</td>
                        <td>
                            @forelse($permainan->ruangans as $ruangan)
                                <span class="badge badge-info">{{ $ruangan->nama_ruangan }}</span>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('admin.game.edit', $permainan->id_permainan) }}"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.game.destroy', $permainan->id_permainan) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus game {{ $permainan->nama_permainan }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data game.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop