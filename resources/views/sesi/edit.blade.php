@extends('layouts.nav_admin')
@section('title', 'Edit Sesi')
@section('content')

<div class="container mt-4">
    <h4>Edit Sesi</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sesi.update', $sesi->Id_Sesi) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="Nama_Sesi">Nama Sesi</label>
            <input type="text" name="Nama_Sesi" value="{{ $sesi->Nama_Sesi }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Mulai_Sesi">Mulai Sesi</label>
            <input type="time" name="Mulai_Sesi" value="{{ \Carbon\Carbon::parse($sesi->Mulai_Sesi)->format('H:i') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Selesai_Sesi">Selesai Sesi</label>
            <input type="time" name="Selesai_Sesi" value="{{ \Carbon\Carbon::parse($sesi->Selesai_Sesi)->format('H:i') }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('sesi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
 
@endsection
