<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        :root {
            /* Warna Utama */
            --medical-blue: #2563eb;
            --medical-teal: #0891b2;
            --medical-mint: #10b981;

            /* Warna Background */
            --bg-medical-light: #f0f9ff;
            --bg-medical-white: #ffffff;
            --bg-medical-gray: #f8fafc;

            /* Warna Notif */
            --notif-accent-green: #059669;
            --notif-accent-orange: #ea580c;
            --notif-accent-red: #dc2626;

            /* Warna Teks */
            --text-dark: #1e293b;
            --text-light: #64748b;
            --text-muted: #94a3b8;

            /* Shadow & Effects */
            --shadow-medical: 0 4px 6px -1px rgba(37, 99, 235, 0.1), 0 2px 4px -1px rgba(37, 99, 235, 0.06);
            --shadow-card: 0 10px 15px -3px rgba(37, 99, 235, 0.1), 0 4px 6px -2px rgba(37, 99, 235, 0.05);
            --glow-medical: 0 0 20px rgba(37, 99, 235, 0.15);

            /* Warna Status Medis */
            --status-normal: #10b981;
            --status-warning: #f59e0b;
            --status-critical: #ef4444;
            --status-info: #3b82f6;

            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--medical-blue) 0%, var(--medical-teal) 100%);
            --gradient-success: linear-gradient(135deg, var(--medical-mint) 0%, var(--notif-accent-green) 100%);
            --gradient-background: linear-gradient(135deg, var(--bg-medical-light) 0%, var(--bg-medical-white) 100%);

            /* Warna tambahan hasil konversi rgba */
            --overlay-light: rgba(255, 255, 255, 0.15);
            --overlay-hover: rgba(255, 255, 255, 0.25);
            --overlay-border: rgba(255, 255, 255, 0.2);
            --overlay-border-light: var(--overlay-border-light);
            --blue-light-transparent: rgba(37, 99, 235, 0.05);
            --blue-border-transparent: rgba(37, 99, 235, 0.1);
            --blue-focus-shadow: rgba(37, 99, 235, 0.15);

            /* Transisi */
            --transition-default: all .3s ease-in-out;

            /* Warna Fokus */
            --focus-ring: #93c5fd;

            /* Warna Kategori Layanan */
            --category-obgyn: #f472b6;
            --category-pediatric: #facc15;
            --category-dental: #a78bfa;

            /* Warna Netral Tambahan */
            --gray-light: #e2e8f0;
            --gray-dark: #cbd5e1;

            /* Tombol Sekunder & Cancel */
            --button-secondary: #e0f2fe;
            --button-cancel: #fef2f2;

            /* Background Gelap */
            --bg-dark: #1e293b;
            --bg-dark-soft: #334155;
        }

        body {
            background: var(--gradient-background);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }

        .registration-container {
            background: var(--bg-medical-white);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--overlay-border);
        }

        .header-section {
            background: var(--gradient-primary);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.1"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="50" cy="50" r="30" fill="url(%23a)"/><circle cx="950" cy="100" r="20" fill="url(%23a)"/><circle cx="150" cy="950" r="25" fill="url(%23a)"/><circle cx="850" cy="850" r="35" fill="url(%23a)"/></svg>') no-repeat;
            background-size: cover;
            opacity: 0.1;
        }

        .header-section h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
        }

        .header-section .lead {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-control:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
            background-color: var(--bg-medical-white);
        }

        .form-floating>label {
            opacity: 0.65;
            color: var(--text-light);
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            opacity: 0.65;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: var(--medical-blue);
        }

        .form-control {
            background-color: var(--bg-medical-gray);
            border: 2px solid var(--gray-light);
            transition: var(--transition-default);
            color: var(--text-dark);
        }

        .form-control:focus {
            background-color: var(--bg-medical-white);
            border-color: var(--medical-blue);
            box-shadow: 0 0 0 0.2rem var(--blue-focus-shadow);
        }

        .form-select {
            background-color: var(--bg-medical-gray);
            border: 2px solid var(--gray-light);
            transition: var(--transition-default);
            color: var(--text-dark);
        }

        .form-select:focus {
            background-color: var(--bg-medical-white);
            border-color: var(--medical-blue);
            box-shadow: 0 0 0 0.2rem var(--blue-focus-shadow);
        }

        .btn-register {
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: var(--transition-default);
            box-shadow: var(--shadow-medical);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--overlay-light);
            transition: left 0.5s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow-medical);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .alert-custom {
            border-radius: 12px;
            border: none;
            background-color: var(--button-cancel);
            color: var(--notif-accent-red);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        }

        .status-message {
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: -0.75rem;
            margin-bottom: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            transition: var(--transition-default);
        }

        .text-success {
            color: var(--status-normal) !important;
            background-color: rgba(16, 185, 129, 0.1);
        }

        .text-danger {
            color: var(--notif-accent-red) !important;
            background-color: rgba(220, 38, 38, 0.1);
        }

        .text-info {
            color: var(--status-info) !important;
            background-color: var(--blue-light-transparent);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 10;
            transition: var(--transition-default);
        }

        .input-icon .form-control:focus+label+i {
            color: var(--medical-blue);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-body {
            padding: 2.5rem;
            background-color: var(--bg-medical-white);
        }

        .toast {
            backdrop-filter: blur(10px);
            border: 1px solid var(--overlay-border);
        }

        .bg-danger {
            background: var(--gradient-success) !important;
        }

        @media (max-width: 576px) {
            .registration-container {
                margin: 1rem;
                border-radius: 15px;
            }

            .header-section {
                border-radius: 15px 15px 0 0;
                padding: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }
        }

        /* Medical Icons Animation */
        .header-section i {
            position: relative;
            z-index: 1;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Loading Animation */
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#togglePin').on('click', function() {
                const pinInput = $('#Pin');
                const icon = $('#icon-eye');

                if (pinInput.attr('type') === 'password') {
                    pinInput.attr('type', 'text');
                    icon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
                } else {
                    pinInput.attr('type', 'password');
                    icon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // NIK Validation
            $('#Nik').on('blur', function() {
                const nik = $(this).val();
                const statusDiv = $('#nik-status');

                if (nik.length === 16) {
                    // Show loading
                    statusDiv.html('<i class="bi bi-arrow-repeat spin"></i> Memverifikasi NIK...')
                        .removeClass('text-danger text-success')
                        .addClass('text-info');

                    $.post("{{ route('cek.nik') }}", {
                        _token: "{{ csrf_token() }}",
                        nik: nik
                    }, function(data) {
                        if (data.available) {
                            statusDiv.html(
                                    '<i class="bi bi-check-circle-fill me-1"></i>NIK tersedia')
                                .removeClass('text-danger text-info')
                                .addClass('text-success');
                        } else {
                            statusDiv.html('<i class="bi bi-x-circle-fill me-1"></i>' + data
                                    .message)
                                .removeClass('text-success text-info')
                                .addClass('text-danger');
                        }
                    }).fail(function() {
                        statusDiv.html(
                                '<i class="bi bi-exclamation-triangle-fill me-1"></i>Gagal memverifikasi NIK'
                                )
                            .removeClass('text-success text-info')
                            .addClass('text-danger');
                    });
                } else if (nik.length > 0) {
                    statusDiv.html('<i class="bi bi-x-circle-fill me-1"></i>NIK harus 16 digit')
                        .removeClass('text-success text-info')
                        .addClass('text-danger');
                } else {
                    statusDiv.empty();
                }
            });

            // Phone Number Validation
            $('#No_Tlp').on('blur', function() {
                const no_tlp = $(this).val();
                const statusDiv = $('#notlp-status');

                if (no_tlp.length > 0) {
                    // Show loading
                    statusDiv.html('<i class="bi bi-arrow-repeat spin"></i> Memverifikasi nomor telepon...')
                        .removeClass('text-danger text-success')
                        .addClass('text-info');

                    $.post("{{ route('cek.notlp') }}", {
                        _token: "{{ csrf_token() }}",
                        no_tlp: no_tlp
                    }, function(data) {
                        if (data.available) {
                            statusDiv.html(
                                    '<i class="bi bi-check-circle-fill me-1"></i>Nomor telepon tersedia'
                                    )
                                .removeClass('text-danger text-info')
                                .addClass('text-success');
                        } else {
                            statusDiv.html('<i class="bi bi-x-circle-fill me-1"></i>' + data
                                    .message)
                                .removeClass('text-success text-info')
                                .addClass('text-danger');
                        }
                    }).fail(function() {
                        statusDiv.html(
                                '<i class="bi bi-exclamation-triangle-fill me-1"></i>Gagal memverifikasi nomor telepon'
                                )
                            .removeClass('text-success text-info')
                            .addClass('text-danger');
                    });
                } else {
                    statusDiv.empty();
                }
            });

            // Form Submission Validation
            $('#form-pasien').on('submit', function(e) {
                const nikStatus = $('#nik-status').text();
                const notlpStatus = $('#notlp-status').text();

                if (nikStatus.includes('harus') || nikStatus.includes('sudah') ||
                    notlpStatus.includes('sudah') || notlpStatus.includes('Gagal')) {
                    e.preventDefault();

                    // Show toast notification
                    const toastHtml = `
                        <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Periksa kembali NIK atau Nomor Telepon Anda.
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        </div>
                    `;

                    // Remove existing toasts
                    $('.toast').remove();

                    // Add toast to body
                    $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3">' +
                        toastHtml + '</div>');

                    // Show toast
                    const toast = new bootstrap.Toast($('.toast'));
                    toast.show();
                }
            });


        });
    </script>
</body>

</html>
