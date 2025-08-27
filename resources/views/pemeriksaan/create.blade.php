@extends('layouts.nav_admin')
@section('title', 'Tambah Pemeriksaan')

@section('content')
<div class="container mt-3">
    <h4 class="mb-4">Tambah Pemeriksaan</h4>

    <form action="{{ route('pemeriksaan.store') }}" method="POST">
        @csrf

        {{-- Info Pasien jika hanya ada 1 kunjungan --}}
        @if ($kunjungan->count() === 1)
            @php $pasien = $kunjungan[0]; @endphp

            <input type="hidden" name="Id_Kunjungan" value="{{ $pasien->Id_Kunjungan }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Pasien</label>
                <input type="text" class="form-control" value="{{ $pasien->Nama_Pasien }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">NIK</label>
                <input type="text" class="form-control" value="{{ $pasien->Nik }}" disabled>
            </div>
        @endif

        {{-- Pilih Dokter --}}
        <div class="mb-3">
            <label for="Id_Dokter" class="form-label fw-semibold">Pilih Dokter</label>
            <select name="Id_Dokter" id="Id_Dokter" class="form-select" required>
                <option value="">-- Pilih Dokter --</option>
                @foreach($dokter as $d)
                    <option value="{{ $d->Id_Dokter }}">{{ $d->Nama_Dokter }}</option>
                @endforeach
            </select>
        </div>

        {{-- Diagnosa --}}
        <div class="mb-3">
            <label for="Diagnosa" class="form-label fw-semibold">Diagnosa</label>
            <textarea name="Diagnosa" id="Diagnosa" class="form-control" rows="2"></textarea>
        </div>

        {{-- Tindakan --}}
        <div class="mb-3">
            <label for="Tindakan" class="form-label fw-semibold">Tindakan</label>
            <textarea name="Tindakan" id="Tindakan" class="form-control" rows="2"></textarea>
        </div>

        {{-- Resep --}}
        <div class="mb-3">
            <label for="Resep" class="form-label fw-semibold">Resep</label>
            <textarea name="Resep" id="Resep" class="form-control" rows="2"></textarea>
        </div>

        {{-- Catatan --}}
        <div class="mb-3">
            <label for="Catatan" class="form-label fw-semibold">Catatan</label>
            <textarea name="Catatan" id="Catatan" class="form-control" rows="2"></textarea>
        </div>

        {{-- Tanggal Pemeriksaan --}}
        <div class="mb-3">
            <label for="Tanggal_Pemeriksaan" class="form-label fw-semibold">Tanggal Pemeriksaan</label>
            <input type="date" name="Tanggal_Pemeriksaan" id="Tanggal_Pemeriksaan" class="form-control" value="{{ now()->toDateString() }}" required>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('pemeriksaan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
