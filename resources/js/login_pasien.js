// ---------- Mengecek PIN Apa Sedang Di Blokir ----------
$(document).ready(function () {
    const blokir = $('#inputNoTlp').data('blokir');
    $('#inputNoTlp').on('blur', function () {
        const noTlp = $(this).val()?.trim();
        if (!noTlp) return;

        $.post(checkStatusPinUrl, {
            no_tlp: noTlp
        })
            .done(function (res) {
                if (res.status === 'blocked') {
                    $('#btnCekPin').addClass('d-none');
                    $('#btnKirimOtp').removeClass('d-none');
                    $('#pinInput').addClass('d-none');
                    $('#otpSection').removeClass('d-none');

                    showNotif(
                        `PIN Anda diblokir hingga jam <b>${res.expire_at}</b>. Silakan gunakan OTP.`,
                        'warning',
                        'errorNoTlp' // biar tampil di bawah input telepon
                    );
                }
            })
            .fail(function (xhr) {
                showNotif('Gagal cek status PIN. Coba lagi.', 'danger', 'errorNoTlp');
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

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ---------- VARIABEL GLOBAL ----------
    let nomor = '';
    let nomorGlobal = '';

    // ---------- FUNCTION NOTIFIKASI (INLINE DI BAWAH INPUT) ----------
    function showNotif(msg, type = 'danger', target = null) {
        if (target) {
            const errorEl = document.getElementById(target);
            if (errorEl) {
                errorEl.innerHTML = msg; // biar support <b>
                errorEl.classList.remove('d-none');
                errorEl.classList.remove('text-danger', 'text-success', 'text-warning', 'text-info');
                errorEl.classList.add('text-' + type);

                setTimeout(() => {
                    errorEl.innerHTML = '';
                    errorEl.classList.add('d-none');
                }, 5000);
            }
        } else {
            console.log(msg);
        }
    }

    // ---------- VERIFIKASI PIN ----------
    $('#btnCekPin').on('click', function () {
        nomor = $('#inputNoTlp').val()?.trim();
        const pin = $('#inputPin').val()?.trim();

        if (!nomor) return showNotif('Nomor telepon wajib diisi', 'danger', 'errorNoTlp');

        if (pin) {
            $.post(checkLoginPinUrl, {
                no_tlp: nomor,
                pin: pin
            }, function (res) {
                if (res.status === 'not_found') {
                    showNotif(res.message, 'warning', 'errorNoTlp');
                    setTimeout(() => window.location.href = checkDaftarPasienUrl,
                        1500);
                } else if (res.status === 'success') {
                    showNotif(res.message, 'success', 'errorPin');
                    setTimeout(() => window.location.href = res.redirect_url, 1000);
                } else if (res.status === 'wrong_pin') {
                    showNotif(res.message, 'danger', 'errorPin');
                } else if (res.status === 'to_otp') {
                    showNotif(res.message, 'warning', 'errorPin');
                    $('#btnCekPin').addClass('d-none');
                    $('#btnKirimOtp').removeClass('d-none');
                    $('#pinInput').addClass('d-none');
                    $('#otpSection').removeClass('d-none');
                } else {
                    showNotif('Terjadi kesalahan. Coba lagi.', 'danger', 'errorPin');
                }
            });
        } else {
            $.post(checkNoTlpUrl, {
                no_tlp: nomor
            }, function (res) {
                if (res.status === 'not_found') {
                    showNotif('Nomor tidak terdaftar. Mengalihkan ke pendaftaran...',
                        'warning', 'errorNoTlp');
                    setTimeout(() => window.location.href = checkDaftarPasienUrl,
                        1500);
                } else {
                    showNotif('PIN wajib diisi untuk melanjutkan.', 'danger', 'errorPin');
                }
            });
        }
    });

    // ---------- KIRIM OTP ----------
    $('#btnKirimOtp').on('click', function (e) {
        e.preventDefault(); // Mencegah perilaku default jika tombol ada di dalam form

        // Ambil nomor dari input. Pastikan variabel 'nomor' global sudah diinisialisasi.
        const nomorTlp = $('#inputNoTlp').val()?.trim();

        // Reset notifikasi inline sebelum menampilkan yang baru
        $('.error-message').addClass('d-none').text('');

        if (!nomorTlp) {
            return showNotif('Nomor telepon wajib diisi', 'danger', 'errorNoTlp');
        }

        // Tampilkan loading state pada tombol untuk umpan balik
        const btn = $(this);
        const originalHtml = btn.html();
        btn.html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...'
        ).prop('disabled', true);

        $.post(checkSendOtpUrl, {
            no_tlp: nomorTlp
        }, function (res) {
            // Kembalikan tombol ke keadaan semula
            btn.html(originalHtml).prop('disabled', false);

            if (res.status === 'otp_sent') {
                // Tampilkan bagian OTP untuk verifikasi
                $('#otpSection').removeClass('d-none');
                $('#kode_otp').val('');
                // Tampilkan notifikasi info di bawah input OTP
                showNotif('OTP dikirim ke WhatsApp Anda.', 'info', 'errorOtp');
            } else if (res.status === 'not_found') {
                showNotif('Nomor tidak terdaftar. Mengalihkan ke pendaftaran...',
                    'warning', 'errorNoTlp');
                setTimeout(() => window.location.href = checkDaftarPasienUrl,
                    1500);
            } else if (res.status === 'limit_reached') {
                showNotif(res.message, 'warning', 'errorNoTlp');
            } else {
                // Kasus lain, seperti gagal mengirim OTP
                showNotif(res.message || 'Gagal mengirim OTP. Coba lagi.',
                    'danger', 'errorOtp');
            }
        }).fail(function () {
            // Tangani kegagalan server
            btn.html(originalHtml).prop('disabled', false);
            showNotif('Terjadi kesalahan pada server. Coba lagi.', 'danger', 'errorOtp');
        });
    });

    // ---------- VERIFIKASI OTP ----------
    $('#btnVerifikasiOtp').on('click', function () {
        const kode = $('#kode_otp').val()?.trim();
        const nomorTlp = $('#inputNoTlp').val()?.trim(); // ambil langsung dari input

        if (!kode) return showNotif('Masukkan kode OTP', 'danger', 'errorOtp');
        if (!nomorTlp) return showNotif('Nomor telepon kosong', 'danger', 'errorNoTlp');

        $.post(checkVerifyOtpUrl, {
            no_tlp: nomorTlp,
            kode_otp: kode
        }, function (res) {
            if (res.status === 'verified') {
                showNotif('OTP valid. Menampilkan data pasien...', 'success', 'errorOtp');
                setTimeout(() => window.location.href = res.redirect_url, 1000);
            } else {
                showNotif('Kode OTP salah atau kadaluarsa.', 'danger', 'errorOtp');
            }
        });
    });

    // ---------- RESET PIN (KIRIM OTP) ----------
    $('#btnKirimOtpReset').on('click', function () {
        nomorGlobal = $('#no_tlp').val()?.trim();
        if (!nomorGlobal) return showNotif('Isi nomor telepon dulu', 'danger', 'errorNoTlpReset');

        $.post(checkResetPinReqUrl, {
            no_tlp: nomorGlobal
        }, function (res) {
            if (res.status === 'otp_sent' || res.status === 'waiting') {
                $('#no_tlp').prop({
                    readonly: true,
                    disabled: false
                });
                $('#step1').addClass('d-none');
                $('#step2').removeClass('d-none');
                $('#kode_otp_reset').val('');
                showNotif(res.message || 'Kode OTP sudah dikirim ke WhatsApp Anda.', 'info',
                    'errorOtpReset');
            } else {
                showNotif(res.message || 'Gagal mengirim OTP. Coba lagi.', 'danger',
                    'errorOtpReset');
            }
        });
    });

    // ---------- RESET PIN (VERIFIKASI OTP) ----------
    $('#btnVerifikasiOtpReset').on('click', function () {
        const kode = $('#kode_otp_reset').val()?.trim();
        if (!kode) return showNotif('Masukkan kode OTP', 'danger', 'errorOtpReset');

        $.post(checkResetPinVerUrl, {
            no_tlp: nomorGlobal,
            kode_otp: kode
        }, function (res) {
            if (res.status === 'verified') {
                $('#no_tlp').prop({
                    readonly: false,
                    disabled: true
                });
                $('#step1').removeClass('d-none');
                $('#step2').addClass('d-none');
                $('#step3').removeClass('d-none');
                showNotif('OTP berhasil diverifikasi. Silakan buat PIN baru.', 'success',
                    'errorOtpReset');
            } else if (res.status === 'expired') {
                $('#step1').removeClass('d-none');
                $('#btnKirimOtpReset').removeClass('d-none');
                showNotif(res.message || 'Kode OTP sudah kadaluarsa.', 'warning',
                    'errorOtpReset');
            } else if (res.status === 'invalid') {
                showNotif(res.message || 'Kode OTP salah.', 'danger', 'errorOtpReset');
                $('#btnKirimOtpReset').removeClass('d-none').prop('disabled', false);
            } else {
                showNotif('Terjadi kesalahan. Silakan coba lagi.', 'danger',
                    'errorOtpReset');
            }
        });
    });

    // ---------- RESET PIN (SIMPAN PIN BARU) ----------
    $('#btnSimpanPin').on('click', function () {
        const pin = $('#pin').val()?.trim();
        const konfirmasi = $('#konfirmasi').val()?.trim();

        if (!pin || !konfirmasi) return showNotif('PIN dan Konfirmasi wajib diisi', 'danger',
            'errorPinReset');
        if (pin !== konfirmasi) return showNotif('Konfirmasi PIN tidak cocok', 'danger',
            'errorPinReset');

        $.post(checkResetPinSimpanUrl, {
            no_tlp: nomorGlobal,
            pin: pin,
            pin_confirmation: konfirmasi
        }, function (res) {
            if (res.status === 'success') {
                showNotif(res.message || 'PIN berhasil disimpan', 'success',
                    'errorPinReset');
                setTimeout(() => window.location.href = checkVerifyUrl, 2000);
            } else {
                showNotif(res.message || 'Gagal menyimpan PIN.', 'danger', 'errorPinReset');
            }
        });
    });
});
