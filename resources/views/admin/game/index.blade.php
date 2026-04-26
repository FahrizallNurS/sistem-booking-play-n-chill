@extends('adminlte::page')

@section('title', 'Kelola Game')

@section('content_header')
    <h1>Kelola Game</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
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
                    @forelse($permainans as $index => $permainan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($permainan->gambar)
                                    <img src="{{ asset('storage/' . $permainan->gambar) }}"
                                        alt="{{ $permainan->nama_permainan }}"
                                        style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                                @else
                                    <div style="width:60px;height:60px;background:#eee;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-gamepad text-muted"></i>
                                    </div>
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
                                    method="POST" style="display:inline"
                                    onsubmit="return confirm('Yakin hapus game ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data game</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop