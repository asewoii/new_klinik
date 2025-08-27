@extends('layouts.verify')
@section('title', 'Reset PIN Pasien')

@section('content')
<div class="main-card">
    <div class="header-section">
        <div class="header-icon">
           <i class="fa-solid fa-key"></i>
        </div>
        <h1 class="header-title">Reset PIN</h1>
        <p class="header-subtitle">Masukkan Nomor & PIN baru Anda</p>
    </div>


    <div class="form-section">
        <div id="inputnohp" class="input-group-custom mb-3">
            <i class="fas fa-id-card"></i>
            <input type="text" id="no_tlp" class="form-control form-control-custom" placeholder="Masukkan nomor telepon" maxlength="15">
        </div>

        <div id="step1" class="mb-3">
            <button class="btn btn-primary w-100" id="btnKirimOtpReset">Kirim OTP</button>
        </div>

        <div id="step2" class="d-none mb-3">
            <div class="input-group-custom mb-3">
                <i class="fas fa-lock"></i>
                <input type="text" id="kode_otp_reset" class="form-control form-control-custom mb-2" placeholder="Masukkan kode OTP" minlength="4" maxlength="6">
            </div>
            
            <button class="btn btn-success w-100" id="btnVerifikasiOtpReset">Verifikasi OTP</button>
        </div>

        <div id="step3" class="d-none mb-3">
            <div class="input-group-custom mb-3">
                <i class="fas fa-lock"></i>
                <input type="password" id="pin" class="form-control form-control-custom mb-2" placeholder="PIN Baru" maxlength="6">
            </div>
            <div class="input-group-custom mb-3">
                <i class="fas fa-lock"></i>
                <input type="password" id="konfirmasi" class="form-control form-control-custom mb-2" placeholder="Konfirmasi PIN" maxlength="6">
            </div>
            <button class="btn btn-success w-100" id="btnSimpanPin">Simpan PIN</button>
        </div>
    </div>
</div>
@endsection
