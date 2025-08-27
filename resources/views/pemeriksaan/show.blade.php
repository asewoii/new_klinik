<!---@extends('layouts.nav_admin')
@section('title', 'Detail Pemeriksaan')

@section('content')
<div class="container">
    <h3 class="mb-4">Detail Pemeriksaan Pasien</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">📌 Informasi Kunjungan</h5>
            <p><strong>Nama Pasien:</strong> {{ $data->kunjungan->Nama_Pasien ?? '-' }}</p>
            <p><strong>NIK:</strong> {{ $data->kunjungan->Nik ?? '-' }}</p>
            <p><strong>Tanggal Registrasi:</strong> {{ \Carbon\Carbon::parse($data->kunjungan->Tanggal_Registrasi)->format('d/m/Y') }}</p>
            <p><strong>Nomor Urut:</strong> {{ $data->kunjungan->Nomor_Urut ?? '-' }}</p>

            <hr>

            <h5 class="mb-3">🩺 Pemeriksaan</h5>
            <p><strong>Nama Dokter:</strong> {{ $data->dokter->Nama ?? '-' }}</p>
            <p><strong>Tanggal Pemeriksaan:</strong> {{ \Carbon\Carbon::parse($data->Tanggal_Pemeriksaan)->format('d/m/Y') }}</p>
            <p><strong>Jam Pemeriksaan:</strong> {{ $data->Jam_Pemeriksaan }}</p>
            <p><strong>Diagnosa:</strong><br>{{ $data->Diagnosa }}</p>
            <p><strong>Tindakan:</strong><br>{{ $data->Tindakan }}</p>
            <p><strong>Resep:</strong><br>{{ $data->Resep }}</p>
            <p><strong>Catatan:</strong><br>{{ $data->Catatan }}</p>
        </div>
    </div>

    <a href="{{ route('pemeriksaan.index') }}" class="btn btn-secondary mt-4">← Kembali ke Daftar</a>
</div>
@endsection
-->