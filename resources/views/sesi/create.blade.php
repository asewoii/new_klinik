@extends('layouts.nav_admin')
@section('title', 'Tambah Sesi')
@section('content')

@php
    use Carbon\Carbon;
    $today = Carbon::now()->format('Y-m-d\TH:i');
    $maxDatetime = Carbon::now()->addDays(14)->format('Y-m-d\T23:59');
@endphp

<div class="container mt-4">
    <h4>Tambah Sesi Baru</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sesi.store') }}">
        @csrf

        <div class="mb-3">
            <label for="Nama_Sesi">Nama Sesi</label>
            <input type="text" name="Nama_Sesi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Mulai_Sesi">Mulai Sesi</label>
            <input type="time" name="Mulai_Sesi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Selesai_Sesi">Selesai Sesi</label>
            <input type="time" name="Selesai_Sesi" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('sesi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

@endsection
