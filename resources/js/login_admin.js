// ==== A. KONFIGURASI DAN INICIALISASI ==== \\

// Page initialization with medical theme
window.addEventListener('load', function () {
    // Simulasi pemeriksaan sistem di console
    setTimeout(() => {
        console.log('🏥 HealthCare Pro System - Ready');
        console.log('🔒 Security protocols - Active');
        console.log('📊 Medical database - Connected');
    }, 1000);

    // Focus input pertama
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.focus();
    }
});

// Demo alert (HAPUS di production)
function showNotification(message, type) {
    console.log(`[Notification ${type.toUpperCase()}]: ${message}`);
    // Jika Anda memiliki elemen alert di HTML, Anda bisa menampilkan di sini
}
setTimeout(() => {
    showNotification('Sistem HealthCare Pro siap digunakan!', 'success');
}, 2000);


// ==== B. FORM HANDLING & INTERAKSI ==== \\

const loginForm = document.getElementById('loginForm');
if (loginForm) {
    // 1. Form submission and medical loading state
    loginForm.addEventListener('submit', function (e) {
        const submitBtn = this.querySelector('.login-btn');
        // Saat dikirim, tampilkan loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memverifikasi...';

        // Catatan: Proses verifikasi sebenarnya ada di sisi Laravel.
        // Jika menggunakan AJAX, Anda perlu mencegah default submit (e.preventDefault())
        // Di sini kita biarkan form submit secara normal.
    });
}


// 2. Enhanced input interactions (Gerakan Vertical dan Real-time validation)
document.querySelectorAll('.form-input').forEach(input => {
    // Target yang benar adalah form-group (parent dari input-container)
    const formGroup = input.closest('.form-group');

    // Gerakan Vertikal (Focus)
    input.addEventListener('focus', function () {
        if (formGroup) {
            formGroup.style.transform = 'translateY(-2px)';
            formGroup.style.transition = 'transform 0.3s ease';
        }
    });

    // Gerakan Vertikal (Blur)
    input.addEventListener('blur', function () {
        if (formGroup) {
            formGroup.style.transform = 'translateY(0)';
        }
    });

    // Validasi Real-time (Hanya untuk efek visual)
    input.addEventListener('input', function () {
        // Karena kita tidak mendefinisikan --accent-green, kita abaikan
        // validasi border real-time ini kecuali jika Anda ingin menambahkannya.
        // Biasanya validasi visual real-time diurus oleh Bootstrap/Laravel.
    });
});


// 3. Medical keyboard shortcuts (Enter pindah ke input berikutnya)
document.addEventListener('keydown', function (e) {
    // Cek jika tombol Enter ditekan dan targetnya adalah INPUT
    if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
        e.preventDefault(); // Mencegah form disubmit secara default

        const inputs = Array.from(document.querySelectorAll('.form-input'));
        const currentIndex = inputs.indexOf(e.target);

        if (currentIndex < inputs.length - 1) {
            // Pindah ke input berikutnya
            inputs[currentIndex + 1].focus();
        } else if (loginForm) {
            // Jika di input terakhir, submit form
            loginForm.requestSubmit();
        }
    }
});


// ==== C. FITUR TOGGLE PASSWORD (Mata 👁️) ==== \\

document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('togglePassword');

    // Cari elemen input yang memiliki id 'password' atau 'inputPin'
    // Menggunakan querySelector dengan operator OR (,) di dalam CSS selector
    const activeInput = document.querySelector('#password, #inputPin');

    if (toggleButton && activeInput) {
        toggleButton.addEventListener('click', function () {
            // Dapatkan tipe input saat ini
            const currentType = activeInput.getAttribute('type');

            // Tentukan tipe baru: 'text' jika saat ini 'password', dan sebaliknya
            const newType = (currentType === 'password') ? 'text' : 'password';

            // Atur tipe input yang baru
            activeInput.setAttribute('type', newType);

            // Ganti ikon di dalam tombol
            const icon = toggleButton.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    }
});
