<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pasien</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<style>
    :root {
            /* Warna Utama - Biru Medis */
            --medical-blue: #2563eb;           /* Biru medis yang tenang dan profesional */
            --medical-teal: #0891b2;           /* Teal untuk aksen sekunder */
            --medical-mint: #10b981;           /* Hijau mint untuk kesehatan */
            
            /* Warna Background */
            --medical-light: #f0f9ff;          /* Biru sangat muda untuk background */
            --medical-white: #ffffff;          /* Putih bersih */
            --medical-gray: #f8fafc;           /* Abu-abu sangat terang */
            
            /* Warna Aksen */
            --accent-green: #059669;           /* Hijau untuk status positif */
            --accent-orange: #ea580c;          /* Orange untuk peringatan */
            --accent-red: #dc2626;             /* Merah untuk urgent/emergency */
            
            /* Warna Teks */
            --text-dark: #1e293b;              /* Teks gelap yang mudah dibaca */
            --text-light: #64748b;             /* Teks abu-abu untuk subtitle */
            --text-muted: #94a3b8;             /* Teks yang lebih pudar */
            
            /* Shadow & Effects */
            --shadow-medical: 0 4px 6px -1px rgba(37, 99, 235, 0.1), 0 2px 4px -1px rgba(37, 99, 235, 0.06);
            --shadow-card: 0 10px 15px -3px rgba(37, 99, 235, 0.1), 0 4px 6px -2px rgba(37, 99, 235, 0.05);
            --glow-medical: 0 0 20px rgba(37, 99, 235, 0.15);
            
            /* Warna Status Medis */
            --status-normal: #10b981;          /* Hijau untuk normal */
            --status-warning: #f59e0b;         /* Kuning untuk perhatian */
            --status-critical: #ef4444;        /* Merah untuk kritis */
            --status-info: #3b82f6;            /* Biru untuk informasi */
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #2563eb 0%, #0891b2 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
        }
        
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
        
    .main-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        transition: all 0.3s ease;
    }
        
    .main-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
        
    .header-section {
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        padding: 2rem 1.5rem;
        border-radius: 20px 20px 0 0;
        text-align: center;
        color: white;
    }
    
    .header-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    
    .header-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .header-subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-top: 0.5rem;
    }
    
    .form-section {
        padding: 2rem;
    }
    
    .input-group-custom {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .input-group-custom i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 3;
    }
    
    .form-control-custom {
        padding: 15px 15px 15px 45px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .form-control-custom:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        background: white;
    }

    .btn-custom {
        padding: 15px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary-custom {
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
}
    
    .btn-success-custom {
        background: linear-gradient(45deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
    }
    
    .btn-success-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(86, 171, 47, 0.3);
    }
    
    .btn-warning-custom {
        background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .btn-warning-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(245, 87, 108, 0.3);
    }
    
    .otp-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
        border: 2px dashed #dee2e6;
    }

    .patient-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    .patient-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .patient-info {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 1rem;
        backdrop-filter: blur(5px);
    }
    
    .patient-info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        padding: 0.3rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .patient-info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .alert-custom {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        font-weight: 500;
        margin-top: 1rem;
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
}
    
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
    border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        padding: 12px 18px;
        border-radius: 8px;
        font-weight: 500;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease, fadeOut 0.4s ease 4.5s forwards;
        transition: opacity 0.3s ease;
    }

    .notification-success { background-color: #22c55e; }
    .notification-danger { background-color: #ef4444; }
    .notification-warning { background-color: #f59e0b; }
    .notification-info { background-color: #3b82f6; }

    .notif-close {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
        margin-left: auto;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeOut {
        to { opacity: 0; transform: translateX(100%); }
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
    

<body>
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 py-4">
        @if(session('success') || session('error') || session('message'))
            @php
                $type = session('success') ? 'success' : (session('error') ? 'error' : 'info');
                $message = session('success') ?? session('error') ?? session('message');
                $icon = $type === 'success' ? 'bi-clipboard-check-fill'
                    : ($type === 'error' ? 'bi-exclamation-octagon-fill' : 'bi-info-circle-fill');
            @endphp

            <div class="notification notification-{{ $type }}" id="notif-global">
                <i class="bi {{ $icon }}"></i>
                <span>{{ $message }}</span>
                <button type="button" class="notif-close" onclick="closeNotif()">×</button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!---------- Mengalihkan otomatis halaman jika session 'pasien' sudah ada ---------->
    @if(session('pasien'))
    <script>
        window.location.href = "{{ route('pasien.datadiri', ['id' => session('pasien.Id_Pasien')]) }}";
    </script>
    @endif
            
    <!---------- Mengecek PIN Apa Sedang Di Blokir ---------->
    <script>
        $(document).ready(function () {
            const blokir = $('#inputNoTlp').data('blokir');
            $('#inputNoTlp').on('blur', function () {
                const noTlp = $(this).val()?.trim();
                const nomorTersimpan = $('#inputNoTlp').val();
                if (!noTlp) return;
                if (nomorTersimpan) {

                $.post('/cek_status_pin', { no_tlp: noTlp }, function (res) {
                    if (res.status === 'blocked') {
                        $('#btnCekPin').addClass('d-none');
                        $('#btnKirimOtp').removeClass('d-none');
                        $('#pinInput').addClass('d-none');
                        $('#otpSection').removeClass('d-none');

                        showNotif(`PIN Anda diblokir hingga jam <b>${res.expire_at}</b>. Silakan gunakan OTP.`, 'warning');
                    }
                });
            });


            if (blokir == '1') {
                nomor = $('#inputNoTlp').val()?.trim();
                $('#btnCekPin').addClass('d-none');
                $('#btnKirimOtp').removeClass('d-none');
                $('#pinInput').addClass('d-none');
                $('#otpSection').removeClass('d-none');
                showNotif('PIN Anda diblokir. Silakan gunakan OTP.', 'warning');
            }
        });
    </script>

    
    <script>
        // ---------- Baris ini digunakan untuk mengatur token CSRF secara otomatis pada semua permintaan AJAX ($.post, $.ajax, dll.) agar tidak ditolak oleh Laravel. ----------
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ---------- VARIABEL GLOBAL ----------
            let nomor = '';
            let nomorGlobal = '';

             // ---------- VERIFIKASI PIN ----------
            $('#btnCekPin').on('click', function () {
                nomor = $('#inputNoTlp').val()?.trim();
                const pin = $('#inputPin').val()?.trim();

                if (!nomor) return showNotif('Nomor telepon wajib diisi', 'danger');

                if (pin) {
                    $.post('/verify_pin', { no_tlp: nomor, pin: pin }, function (res) {
                        if (res.status === 'not_found') {
                            showNotif('Nomor tidak terdaftar. Mengalihkan ke pendaftaran...', 'warning');
                            setTimeout(() => window.location.href = '/daftar-pasien', 1500);
                        } else if (res.status === 'success') {
                            showNotif(res.message, 'success');
                            setTimeout(() => window.location.href = res.redirect_url, 1000);
                        } else if (res.status === 'wrong_pin') {
                            showNotif(res.message, 'danger');
                        } else if (res.status === 'to_otp') {
                            showNotif(res.message, 'warning');
                            $('#btnCekPin').addClass('d-none');
                            $('#btnKirimOtp').removeClass('d-none');
                            $('#pinInput').addClass('d-none');
                            $('#pinInfo').removeClass('d-none').html(
                                res.expire_at
                                    ? `PIN Anda diblokir hingga jam <b>${res.expire_at}</b>. Silakan gunakan OTP.`
                                    : 'PIN Anda diblokir. Silakan gunakan OTP.'
                            );
                        } else {
                            showNotif('Terjadi kesalahan. Coba lagi.', 'danger');
                        }
                    });
                } else {
                    $.post('/cek_no', { no_tlp: nomor }, function (res) {
                        if (res.status === 'not_found') {
                            showNotif('Nomor tidak terdaftar. Mengalihkan ke pendaftaran...', 'warning');
                            setTimeout(() => window.location.href = '/daftar-pasien', 1500);
                        } else {
                            showNotif('PIN wajib diisi untuk melanjutkan.', 'danger');
                        }
                    });
                }
            });

            // ---------- KIRIM OTP HALAMAN VERIFIKASI ----------
            $('#btnKirimOtp').on('click', function () {
                if (!nomor) return showNotif('Isi nomor telepon dulu', 'danger');

                $.post('/send_otp', { no_tlp: nomor }, function (res) {
                    if (res.status === 'otp_sent') {
                        $('#otpSection').removeClass('d-none');
                        $('#kode_otp').val('');
                        showNotif('OTP dikirim ke WhatsApp Anda.', 'info');
                    } else if (res.status === 'not_found') {
                        showNotif('Nomor tidak terdaftar. Mengalihkan ke pendaftaran...', 'warning');
                        setTimeout(() => window.location.href = "{{ route('daftar.pasien') }}", 1500);
                    } else {
                        showNotif('Gagal mengirim OTP. Coba lagi.', 'danger');
                    }
                });
            });

            // ---------- VERIFIKASI OTP HALAMAN VERIFIKASI ----------
            $('#btnVerifikasiOtp').on('click', function () {
                const kode = $('#kode_otp').val()?.trim();
                if (!kode) return showNotif('Masukkan kode OTP', 'danger');

                $.post('/kode_otp', { no_tlp: nomor, kode_otp: kode }, function (res) {
                    if (res.status === 'verified') {
                        showNotif('OTP valid. Menampilkan data pasien...', 'success');
                        $('#cardPasien').removeClass('d-none');
                        $('#namaPasien').text(res.data.Nama_Pasien);
                        $('#infoPasien').html(`
                            ID: ${res.data.Id_Pasien}<br>
                            NIK: ${res.data.Nik}<br>
                            Umur: ${res.data.umur}<br>
                            No. Telp: ${res.data.No_Tlp}<br>
                            Alamat: ${res.data.Alamat}
                        `);
                        setTimeout(() => window.location.href = `/user/datadiri/${res.data.Id_Pasien}`, 2000);
                    } else {
                        showNotif('Kode OTP salah atau kadaluarsa.', 'danger');
                    }
                });
            });

            // ---------- RESET PIN ----------
            $('#btnKirimOtpReset').on('click', function () {
                nomorGlobal = $('#no_tlp').val()?.trim();
                if (!nomorGlobal) return showNotif('Isi nomor telepon dulu', 'danger');

                $.post('{{ route("reset.pin.requestOtp") }}', { no_tlp: nomorGlobal }, function (res) {
                    if (res.status === 'otp_sent' || res.status === 'waiting') {
                        $('#no_tlp').prop({ readonly: true, disabled: false });
                        $('#step1').addClass('d-none');
                        $('#step2').removeClass('d-none');
                        $('#kode_otp_reset').val('');
                        showNotif(res.message || 'Kode OTP sudah dikirim ke WhatsApp Anda.', 'info');
                    } else {
                        showNotif(res.message || 'Gagal mengirim OTP. Coba lagi.', 'danger');
                    }
                });
            });

            $('#btnVerifikasiOtpReset').on('click', function () {
                const kode = $('#kode_otp_reset').val()?.trim();
                if (!kode) return showNotif('Masukkan kode OTP', 'danger');

                $.post('{{ route("reset.pin.verifikasiOtp") }}', { no_tlp: nomorGlobal, kode_otp: kode }, function (res) {
                    if (res.status === 'verified') {
                        $('#no_tlp').prop({ readonly: false, disabled: true });
                        $('#step1').removeClass('d-none');
                        $('#step2').addClass('d-none');
                        $('#step3').removeClass('d-none');
                        showNotif('OTP berhasil diverifikasi. Silakan buat PIN baru.', 'success');
                    } else if (res.status === 'expired') {
                        $('#step1').removeClass('d-none');
                        $('#btnKirimOtpReset').removeClass('d-none');
                        showNotif(res.message || 'Kode OTP sudah kadaluarsa.', 'warning');
                    } else if (res.status === 'invalid') {
                        showNotif(res.message || 'Kode OTP salah.', 'danger');
                        // Opsional: kalau ingin munculkan tombol kirim ulang juga saat invalid
                        $('#btnKirimOtpReset').removeClass('d-none').prop('disabled', false);
                    } else {
                        showNotif('Terjadi kesalahan. Silakan coba lagi.', 'danger');
                    }
                });
            });

            $('#btnSimpanPin').on('click', function () {
                const pin = $('#pin').val()?.trim();
                const konfirmasi = $('#konfirmasi').val()?.trim();

                if (!pin || !konfirmasi) return showNotif('PIN dan Konfirmasi wajib diisi', 'danger');
                if (pin !== konfirmasi) return showNotif('Konfirmasi PIN tidak cocok', 'danger');

                $.post('{{ route("reset.pin.simpan") }}', {
                    no_tlp: nomorGlobal,
                    pin: pin,
                    pin_confirmation: konfirmasi
                }, function (res) {
                    if (res.status === 'success') {
                        showNotif(res.message || 'PIN berhasil disimpan', 'success');
                        setTimeout(() => window.location.href = '{{ route("verify") }}', 2000);
                    } else {
                        showNotif(res.message || 'Gagal menyimpan PIN.', 'danger');
                    }
                });
            });

            // ---------- NOTIFIKASI ----------
            function showNotif(msg, type = 'info') {
                const notifEl = document.createElement('div');
                notifEl.className = `notification notification-${type}`;
                notifEl.innerHTML = `
                    <i class="bi ${
                        type === 'success' ? 'bi-clipboard-check-fill' :
                        type === 'danger' ? 'bi-exclamation-octagon-fill' :
                        type === 'warning' ? 'bi-exclamation-triangle-fill' :
                        'bi-info-circle-fill'
                    }"></i>
                    <span>${msg}</span>
                    <button type="button" class="notif-close" onclick="this.parentElement.remove()">×</button>
                `;

                notifEl.id = 'notif-global';

                // Hapus notif sebelumnya jika ada
                const oldNotif = document.getElementById('notif-global');
                if (oldNotif) oldNotif.remove();

                document.body.appendChild(notifEl);

                // Auto-hide after 5s
                setTimeout(() => {
                    notifEl.style.opacity = '0';
                    setTimeout(() => notifEl.remove(), 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>