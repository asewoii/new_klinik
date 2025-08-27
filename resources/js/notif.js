// === Notifikasi Otomatis ===
document.addEventListener("DOMContentLoaded", function () {
    const notifications = document.querySelectorAll(".notification");

    notifications.forEach(notif => {
        // Tampilkan
        requestAnimationFrame(() => notif.classList.add("show"));

        // Sembunyikan otomatis setelah 4 detik
        setTimeout(() => {
            notif.classList.remove("show");
            setTimeout(() => notif.remove(), 300);
        }, 4000);
    });
});

// === Fungsi Notifikasi Manual (JS Dinamis) ===
function showNotification(message, type = 'info') {
    const notif = document.createElement('div');
    notif.className = `notification notification-${type}`;

    const icons = {
        success: 'fa-solid fa-circle-check',
        error: 'fa-solid fa-circle-exclamation',
        warning: 'fa-solid fa-triangle-exclamation',
        info: 'fa-solid fa-circle-info'
    };

    notif.innerHTML = `<i class="${icons[type] || icons.info}"></i> ${message}`;
    document.body.appendChild(notif);

    // Tampilkan notifikasi setelah dimasukkan ke DOM
    requestAnimationFrame(() => notif.classList.add("show"));

    setTimeout(() => {
        notif.classList.remove("show");
        setTimeout(() => notif.remove(), 300);
    }, 4000);
}
