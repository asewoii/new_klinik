@extends('layouts.nav_admin')
@section('title', 'Pengaturan Sistem')

@section('content')

<h2>Pengaturan Sistem</h2>

@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-x-circle"></i> {{ session('error') }}
    </div>
@endif

@if($settings['fonnte_active'])
    <div class="alert alert-success">
        <i class="bi bi-whatsapp"></i> Fonnte Aktif - WhatsApp Notifikasi berjalan.
    </div>
@else
    <div class="alert alert-warning">
        <i class="bi bi-whatsapp"></i> Fonnte Nonaktif - WhatsApp Notifikasi tidak berjalan.
    </div>
@endif





<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCreateAdmin">
        <i class="bi bi-person-plus"></i> Buat Admin Baru
    </button>

<form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label>Nama Klinik</label>
        <input type="text" name="nama_klinik" class="form-control" value="{{ $settings['nama_klinik'] }}" required>
    </div>

    <div class="form-group">
        <label>Batas Percobaan PIN ( Login Pasien )</label>
        <input type="number" name="batas_percobaan_pin" class="form-control" value="{{ $settings['batas_percobaan_pin'] }}" min="1" max="10" required>
    </div>

    <div class="form-group">
        <label>Limit Reset Percobaan ( Login Pasien )</label>
        <input type="number" name="limit_reset_percobaan" class="form-control" value="{{ $settings['limit_reset_percobaan'] }}" min="1" max="10" required>
    </div>

    <div class="form-group">
        <label>Blokir Menggunakan PIN/menit ( Login Pasien )</label>
        <input type="number" name="blokir_menggunakan_pin" class="form-control" value="{{ $settings['blokir_menggunakan_pin'] }}" min="1" max="10" required>
    </div>

    <div class="form-group">
        <label>Batas Pengiriman OTP ( Login Pasien )</label>
        <input type="number" name="batas_otp" class="form-control" value="{{ $settings['batas_otp'] }}" min="1" max="10" required>
    </div>

    <div class="form-group">
        <label>OTP Expire/menit ( Login Pasien )</label>
        <input type="number" name="otp_expire_sec" class="form-control" value="{{ $settings['otp_expire_sec'] }}" min="1" required>
    </div>

    <div class="form-group">
        <label>Logo Klinik ( Admin )</label>
        <input type="file" name="logo" class="form-control">
        @if($settings['logo'])
            <img src="{{ asset($settings['logo']) }}" alt="Logo Klinik" height="80" class="mt-2">
        @endif
    </div>

    <div class="form-group">
        <label>Aktifkan Fonnte (WA Notifikasi)</label>
        <select name="fonnte_active" class="form-control">
            <option value="1" {{ $settings['fonnte_active'] ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !$settings['fonnte_active'] ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Pengaturan</button>
</form>

<form action="{{ url('/tes-notif-h3') }}" method="GET" class="d-inline-block mt-2" onsubmit="return confirm('Kirim notifikasi H-3 ke semua pasien yang punya kunjungan?')">
  <button type="submit" class="btn btn-warning">
    <i class="bi bi-bell"></i> Kirim Notifikasi H-3 ke Pasien
  </button>
</form>

<!-- Modal Create Admin -->
<div class="modal fade" id="modalCreateAdmin" tabindex="-1" aria-labelledby="modalCreateAdminLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalCreateAdminLabel">Tambah Admin Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="form-group mb-2">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="form-group mb-2">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
          <input type="hidden" name="role" value="admin">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
