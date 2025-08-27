@extends('layouts.nav_admin')
@section('content')
<div class="container">
    <h4 class="mb-3">Edit Ruangan</h4>

    <form action="{{ route('ruangan.update', $ruangan->Id_Ruangan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="Nama_Ruangan" class="form-label">Nama Ruangan</label>
            <input type="text" class="form-control" name="Nama_Ruangan" value="{{ old('Nama_Ruangan', $ruangan->Nama_Ruangan) }}" required>
        </div>

        <div class="mb-3">
            <label for="Jenis_Ruangan" class="form-label">Jenis Ruangan</label>
            <input type="text" class="form-control" name="Jenis_Ruangan" value="{{ old('Jenis_Ruangan', $ruangan->Jenis_Ruangan) }}" required>
        </div>

        <div class="mb-3">
            <label for="Lantai" class="form-label">Lantai</label>
            <input type="number" class="form-control" name="Lantai" value="{{ old('Lantai', $ruangan->Lantai) }}" required>
        </div>

        <div class="mb-3">
            <label for="Status" class="form-label">Status</label>
            <select class="form-select" name="Status" required>
                <option value="aktif" {{ $ruangan->Status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $ruangan->Status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="dalam perbaikan" {{ $ruangan->Status == 'dalam perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="Keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" name="Keterangan" rows="3">{{ old('Keterangan', $ruangan->Keterangan) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('ruangan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
