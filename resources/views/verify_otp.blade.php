@extends('layouts.verify')
@section('title', 'Verifikasi Pasien')
@section('content')

@php
    use Carbon\Carbon;

    $nomor = old('no_tlp') ?? '';
    $expireAt = $nomor ? Cache::get("pin_fail_expire_$nomor") : null;
    $isBlocked = $expireAt && Carbon::parse($expireAt)->isFuture();
@endphp

<div class="main-card">

    <div class="header-section">
        <div class="header-icon">
            <i class="fas fa-user-check"></i>
        </div>
        <h1 class="header-title">Verifikasi Pasien</h1>
        <p class="header-subtitle">Masukkan Telepon & PIN untuk memulai</p>
    </div>

    <div class="form-section">
        <div class="input-group-custom mb-3">
            <i class="fas fa-id-card"></i>
            <input type="text" id="inputNoTlp" name="no_tlp" class="form-control form-control-custom" placeholder="No. Telepon" value="{{ old('no_tlp') ?? session('login_pasien.no_tlp') }}" data-blokir="{{ $isBlocked ? '1' : '0' }}">
        </div>

        <div id="pinInput" class="input-group-custom mb-3">
            <i class="fas fa-lock"></i>
            <input type="password" id="inputPin" class="form-control form-control-custom" placeholder="PIN" maxlength="6">
        </div>

        <button id="btnCekPin" class="btn btn-custom btn-primary-custom w-100 mb-2">
            <i class="fas fa-check-circle me-2"></i>
            Verifikasi
        </button>

        <button id="btnKirimOtp" class="btn btn-custom btn-warning-custom w-100 mb-3 d-none">
            <i class="fas fa-paper-plane me-2"></i>
            Kirim OTP
        </button>

        <div id="otpSection" class="otp-section fade-in d-none">
            <div class="text-center mb-3">
                <i class="fas fa-mobile-alt text-primary" style="font-size: 2rem;"></i>
                <h6 class="mt-2 mb-0">Kode OTP</h6>
                <small class="text-muted">Masukkan 6 digit kode yang dikirim ke WhatsApp</small>
            </div>

            <div class="input-group-custom mb-3">
                <i class="fas fa-key"></i>
                <input type="text" id="kode_otp" class="form-control form-control-custom" placeholder="6 digit OTP" maxlength="6">
            </div>

            <button id="btnVerifikasiOtp" class="btn btn-custom btn-success-custom w-100">
                <i class="fas fa-check-circle me-2"></i>
                Verifikasi OTP
            </button>
        </div>

        <div class="mb-2">
            <small>Belum memiliki akun? <a href="{{ route('daftar.pasien') }}">Daftar</a></small>
        </div>

        <div class="mb-2">
            <small><a href="{{ route('reset.pin') }}">Forget PIN</a></small>
        </div>


        <div id="cardPasien" class="patient-card fade-in d-none mt-3">
            <div class="patient-name" id="namaPasien"></div>
            <div class="patient-info" id="infoPasien"></div>

            <div class="text-center mt-3">
                <div class="loading-spinner me-2"></div>
                <small>Data anda sudah ada, mengalihkan ke halaman data diri kamu...</small>
            </div>
        </div>

    </div>
</div>
@endsection
