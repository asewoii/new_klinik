<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pasien</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('layouts.partials.cdn.admincss')
    @vite(['resources/css/Utama_Login_Admin.css'])
</head>

<body>
    @php
        use Carbon\Carbon;

        $nomor = old('no_tlp') ?? '';
        $expireAt = $nomor ? Cache::get("pin_fail_expire_$nomor") : null;
        $isBlocked = $expireAt && Carbon::parse($expireAt)->isFuture();
    @endphp

    <div class="container d-flex justify-content-center align-items-center vh-100 p-3 p-lg-0">
        <div class="main-container row g-0 shadow-lg rounded-3 overflow-hidden">

            <div class="medical-info col-lg-7 d-none d-lg-flex flex-column p-4 p-xl-5">
                <div class="logo mb-4">
                    <img src="{{ asset('images/logo/Medical.png') }}" alt="Logo Klinik"
                        class="logo-image img-fluid mb-2">
                    <h1 class="clinic-title mb-0">Medical Sehat</h1>
                </div>

                <div class="deskripsi mb-4">
                    <p class="clinic-subtitle lead">Pelayanan Kesehatan Terpercaya</p>
                    <p class="clinic-description small">
                        Memberikan pelayanan kesehatan terbaik dengan teknologi modern dan tenaga medis
                        berpengalaman untuk
                        kesehatan keluarga Anda.
                    </p>
                </div>

                <ul class="features-list list-unstyled mb-auto">
                    <li class="mb-2"><i class="fas fa-user-md me-2"></i> Dokter Berpengalaman</li>
                    <li class="mb-2"><i class="fas fa-calendar-check me-2"></i> Pendaftaran Online Mudah & Antrian
                        Real-Time</li>
                    <li class="mb-2"><i class="fas fa-shield-alt me-2"></i> Fasilitas Lengkap</li>
                    <li class="mb-2"><i class="fas fa-heart me-2"></i> Pelayanan Terpercaya</li>
                    <li class="mb-2"><i class="fas fa-chart-line me-2"></i> Rekam Medis Digital</li>
                </ul>

                <div class="stats-container d-flex justify-content-between pt-3 border-top border-light opacity-75">
                    <div class="stat-item text-center">
                        <span class="stat-number h4 fw-bold d-block">500+</span>
                        <span class="stat-label small">Pasien</span>
                    </div>
                    <div class="stat-item text-center">
                        <span class="stat-number h4 fw-bold d-block">15+</span>
                        <span class="stat-label small">Dokter</span>
                    </div>
                    <div class="stat-item text-center">
                        <span class="stat-number h4 fw-bold d-block">5+</span>
                        <span class="stat-label small">Tahun</span>
                    </div>
                </div>
            </div>

            <div class="main-card login-section col-lg-5 col-12 d-flex flex-column justify-content-center p-4 p-md-5">
                <div class="login-header text-center mb-4">
                    <i class="fas fa-user-shield login-icon display-4 text-primary mb-2"></i>
                    <h2 class="login-title fw-bold">Login Pasien</h2>
                    <p class="login-description text-muted">Masuk ke halaman pasien</p>
                </div>

                <div class="login-form" style="max-width: 400px;">
                    <div class="form-group mb-1">
                        <label for="NoTlp" class="form-label fw-semibold">No Telepon</label>
                        <div class="input-container d-flex align-items-center border rounded-3 bg-light mb-1">
                            <i class="fas fa-user input-icon p-2 text-muted"></i>
                            <input type="text" id="inputNoTlp" name="no_tlp"
                                class="form-input border-0 bg-transparent py-2 px-0 w-100" placeholder="08"
                                minlength="10" maxlength="15">
                        </div>
                        <div id="errorNoTlp" class="text-danger error-message d-none"></div>
                        <!-- tempat notif -->
                    </div>

                    <div id="pinInput" class="form-group mb-4">
                        <label for="password" class="form-label fw-semibold">PIN</label>
                        <div class="input-container d-flex align-items-center border rounded-3 bg-light">
                            <i class="fas fa-lock input-icon p-2 text-muted"></i>
                            <input type="password" id="inputPin" name="pin"
                                class="form-input border-0 bg-transparent py-2 px-0 w-100" placeholder="Masukkan pin"
                                maxlength="6">
                            <button type="button" id="togglePassword" class="btn btn-sm text-muted p-0 me-2"
                                style="border: none; background: none;">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        <small id="errorPin" class="text-danger error-message d-none"></small> <!-- tempat notif -->
                    </div>

                    <div id="otpSection" class="form-group fade-in mb-4 d-none">
                        <label for="otp" class="form-label fw-semibold">Kode OTP</label>
                        <div class="input-container d-flex align-items-center border rounded-3 bg-light">
                            <i class="fas fa-mobile-alt input-icon p-2 text-muted"></i>
                            <input type="text" id="kode_otp"
                                class="form-input border-0 bg-transparent py-2 px-0 w-100" placeholder="6 digit OTP"
                                maxlength="6">
                            <small id="errorOtp" class="text-danger error-message d-none"></small>

                            <button type="button" id="togglePassword" class="btn btn-sm text-muted p-0 me-2"
                                style="border: none; background: none;">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>

                        <button id="btnVerifikasiOtp" class="btn btn-custom btn-success-custom mt-2 w-100">
                            <i class="fas fa-check-circle me-2"></i>
                            Verifikasi OTP
                        </button>
                    </div>

                    <button id="btnCekPin" class="login-btn w-100 btn btn-primary py-2 fw-bold">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Masuk ke Dashboard
                    </button>

                    <button id="btnKirimOtp" class="login-btn w-100 btn btn-primary py-2 fw-bold d-none">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Kirim OTP via WhatsApp
                    </button>

                    <div class="info_sudah_punya_akun">
                        <small>Belum memiliki akun? <a href="{{ route('daftar.pasien') }}">Daftar</a></small>
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
        </div>
    </div>

    @include('layouts.partials.cdn.adminjs')
    @vite(['resources/js/Utama_Login_Pasien.js'])

    <!---------- Mengalihkan otomatis halaman jika session 'pasien' sudah ada ---------->
    @if (session('pasien'))
        <script>
            window.location.href = "{{ route('pasien.datadiri', ['id' => session('pasien.Id_Pasien')]) }}";
        </script>
    @endif

    <script>
        const checkStatusPinUrl = "{{ route('check_status_pin') }}";
        const checkLoginPinUrl = "{{ route('login_pin') }}";
        const checkDaftarPasienUrl = "{{ route('daftar.pasien') }}";
        const checkNoTlpUrl = "{{ route('cek_notlp') }}";
        const checkSendOtpUrl = "{{ route('send_otp') }}";
        const checkVerifyOtpUrl = "{{ route('verify_otp') }}";
        const checkResetPinReqUrl = "{{ route('reset.pin.requestOtp') }}";
        const checkResetPinVerUrl = "{{ route('reset.pin.verifikasiOtp') }}";
        const checkResetPinSimpanUrl = "{{ route('reset.pin.simpan') }}";
        const checkVerifyUrl = "{{ route('verify') }}";
    </script>
</body>

</html>
