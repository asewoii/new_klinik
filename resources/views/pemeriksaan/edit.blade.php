@extends('layouts.nav_admin')
@section('title', 'Edit Pemeriksaan')

@section('content')
<div class="container mt-3">
    <h4>Edit Pemeriksaan</h4>
    <form action="{{ route('pemeriksaan.update', $pemeriksaan->Id_Pemeriksaan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pasien</label>
            <select name="Id_Kunjungan" class="form-select" required>
                @foreach($kunjungan as $k)
                    <option value="{{ $k->Id_Kunjungan }}" {{ $pemeriksaan->Id_Kunjungan == $k->Id_Kunjungan ? 'selected' : '' }}>
                        {{ $k->Nama_Pasien }} ({{ $k->Jadwal_Kedatangan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Dokter</label>
            <select name="Id_Dokter" class="form-select" required>
                @foreach($dokter as $d)
                    <option value="{{ $d->Id_Dokter }}" {{ $pemeriksaan->Id_Dokter == $d->Id_Dokter ? 'selected' : '' }}>
                        {{ $d->Nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Diagnosa</label>
            <textarea name="Diagnosa" class="form-control" rows="2">{{ $pemeriksaan->Diagnosa }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tindakan</label>
            <textarea name="Tindakan" class="form-control" rows="2">{{ $pemeriksaan->Tindakan }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Resep</label>
            <textarea name="Resep" class="form-control" rows="2">{{ $pemeriksaan->Resep }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="Catatan" class="form-control" rows="2">{{ $pemeriksaan->Catatan }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Pemeriksaan</label>
            <input type="date" name="Tanggal_Pemeriksaan" class="form-control" value="{{ $pemeriksaan->Tanggal_Pemeriksaan }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jam Pemeriksaan</label>
            <input type="time" name="Jam_Pemeriksaan" class="form-control" value="{{ $pemeriksaan->Jam_Pemeriksaan }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('pemeriksaan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
