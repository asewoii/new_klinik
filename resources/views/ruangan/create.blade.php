@extends('layouts.nav_admin')
@section('content')
<div class="container">
    <h4 class="mb-3">Tambah Ruangan</h4>

    <form action="{{ route('ruangan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="Nama_Ruangan" class="form-label">Nama Ruangan</label>
            <input type="text" class="form-control" name="Nama_Ruangan" value="{{ old('Nama_Ruangan') }}" required>
        </div>

        <div class="mb-3">
            <label for="Jenis_Ruangan" class="form-label">Jenis Ruangan</label>
            <input type="text" class="form-control" name="Jenis_Ruangan" value="{{ old('Jenis_Ruangan') }}" required>
        </div>

        <div class="mb-3">
            <label for="Lantai" class="form-label">Lantai</label>
            <input type="number" class="form-control" name="Lantai" value="{{ old('Lantai') }}" required>
        </div>

        <div class="mb-3">
            <label for="Status" class="form-label">Status</label>
            <select class="form-select" name="Status" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
                <option value="dalam perbaikan">Dalam Perbaikan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="Keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" name="Keterangan" rows="3">{{ old('Keterangan') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('ruangan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
