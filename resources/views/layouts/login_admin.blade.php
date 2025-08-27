<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Klinik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('layouts.partials.cdn.admincss')
    @vite(['resources/css/Utama_Login_Admin.css'])


</head>

<body>
    @include('layouts/partials/notif')
    <div class="container d-flex justify-content-center align-items-center vh-100 p-3 p-lg-0">
        <div class="main-container row g-0 shadow-lg rounded-3 overflow-hidden">

            <div class="medical-info col-lg-7 d-none d-lg-flex flex-column p-4 p-xl-5">
                <div class="logo mb-4">
                    <img src="{{ asset('images/logo/Medical.png') }}" alt="Logo Klinik" class="logo-image img-fluid mb-2">
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

            <div class="login-section col-lg-5 col-12 d-flex flex-column justify-content-center p-4 p-md-5">
                <div class="login-header text-center mb-4">
                    <i class="fas fa-user-shield login-icon display-4 text-primary mb-2"></i>
                    <h2 class="login-title fw-bold">Login Admin</h2>
                    <p class="login-description text-muted">Masuk ke panel administrasi klinik</p>
                </div>

                <form action="login" method="POST" class="login-form mx-auto" style="max-width: 400px;">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <div class="input-container d-flex align-items-center border rounded-3 bg-light">
                            <i class="fas fa-user input-icon p-2 text-muted"></i>
                            <input type="text" id="username" name="username"
                                class="form-input border-0 bg-transparent py-2 px-0 w-100"
                                placeholder="Masukkan username" required>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-container d-flex align-items-center border rounded-3 bg-light">
                            <i class="fas fa-lock input-icon p-2 text-muted"></i>
                            <input type="password" id="password" name="password"
                                class="form-input border-0 bg-transparent py-2 px-0 w-100"
                                placeholder="Masukkan password" required>
                            <button type="button" id="togglePassword" class="btn btn-sm text-muted p-0 me-2"
                                style="border: none; background: none;">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn w-100 btn btn-primary py-2 fw-bold">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.partials.cdn.adminjs')
    @vite(['resources/js/Utama_Login_Admin.js'])

</body>

</html>
