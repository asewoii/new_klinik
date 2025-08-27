// Sidebar Toggle Functionality
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('wrapper');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.querySelector('.toggle-btn');
    const body = document.body;

    // Create overlay for mobile if it doesn't exist
    let sidebarOverlay = document.querySelector('.sidebar-overlay');
    if (!sidebarOverlay) {
        sidebarOverlay = document.createElement('div');
        sidebarOverlay.className = 'sidebar-overlay';
        document.body.appendChild(sidebarOverlay);
    }

    // Check if we're on mobile
    function isMobile() {
        return window.innerWidth <= 768;
    }

    // Toggle sidebar function
    function toggleSidebar() {
        if (isMobile()) {
            // Mobile behavior
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            body.classList.toggle('sidebar-open');

            // Add animation classes
            if (sidebar.classList.contains('active')) {
                sidebar.style.animation = 'slideIn 0.3s ease-out forwards';
            } else {
                sidebar.style.animation = 'slideOut 0.3s ease-out forwards';
            }
        } else {
            // Desktop/Tablet behavior
            wrapper.classList.toggle('toggled');

            // Store state in localStorage
            localStorage.setItem('sidebarCollapsed', wrapper.classList.contains('toggled'));

            // Trigger resize event for any charts/components that need to adjust
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 300);
        }
    }

    // Close sidebar function
    function closeSidebar() {
        if (isMobile()) {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            body.classList.remove('sidebar-open');
            sidebar.style.animation = 'slideOut 0.3s ease-out forwards';
        } else {
            wrapper.classList.add('toggled');
            localStorage.setItem('sidebarCollapsed', true);
        }
    }

    // Event Listeners
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSidebar();
        });
    }

    // Close sidebar when overlay is clicked (mobile only)
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            closeSidebar();
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (isMobile() && sidebar.classList.contains('active')) {
            // Check if click is outside sidebar and not on toggle button
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                closeSidebar();
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        clearTimeout(window.resizeTimeout);
        window.resizeTimeout = setTimeout(function () {
            handleResize();
        }, 250);
    });

    function handleResize() {
        if (isMobile()) {
            // On mobile, remove desktop toggled state
            wrapper.classList.remove('toggled');
            if (sidebar.classList.contains('active')) {
                body.classList.add('sidebar-open');
            }
        } else {
            // On desktop/tablet, remove mobile classes and restore collapsed state
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            body.classList.remove('sidebar-open');
            sidebar.style.animation = '';

            // Restore collapsed state from localStorage
            const wasCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (wasCollapsed) {
                wrapper.classList.add('toggled');
            } else {
                wrapper.classList.remove('toggled');
            }
        }
    }

    // Initialize sidebar state
    function initializeSidebar() {
        if (!isMobile()) {
            const wasCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (wasCollapsed) {
                wrapper.classList.add('toggled');
            }
        } else {
            wrapper.classList.remove('toggled');
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            body.classList.remove('sidebar-open');
        }
    }

    // Handle submenu toggles
    const navLinks = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {

            // Cek target yang diklik
            const targetId = this.getAttribute('data-bs-target') || this.getAttribute('href');
            const target = document.querySelector(targetId);

            // Logika untuk menutup menu lain (Accordion Style)
            navLinks.forEach(otherLink => {
                if (otherLink !== this) {
                    const otherTargetId = otherLink.getAttribute('data-bs-target') || otherLink.getAttribute('href');
                    const otherTarget = document.querySelector(otherTargetId);
                    const otherChevron = otherLink.querySelector('.chevron-icon');

                    // 1. Cek apakah menu lain sedang terbuka
                    if (otherTarget && otherTarget.classList.contains('show')) {

                        // 2. Dapatkan instance Collapse Bootstrap
                        const bsCollapse = bootstrap.Collapse.getInstance(otherTarget) || new bootstrap.Collapse(otherTarget, { toggle: false });

                        // 3. Tutup menu lain
                        bsCollapse.hide();

                        // Sinkronisasi Ikon Chevron dan Atribut (Opsional, tapi direkomendasikan)
                        otherLink.setAttribute('aria-expanded', 'false');
                        if (otherChevron) otherChevron.style.transform = '';
                    }
                }
            });

            // Catatan: Anda tidak perlu menambahkan logika Toggling untuk menu yang sedang diklik (Toggle current submenu).
            // Bootstrap sudah melakukannya secara otomatis karena adanya data-bs-toggle="collapse".

            // --- Perbaikan Rotasi Chevron Menu Saat Ini ---
            // Rotasi harus dilakukan SETELAH Bootstrap mengubah status aria-expanded.
            setTimeout(() => {
                const chevron = this.querySelector('.chevron-icon');
                if (this.getAttribute('aria-expanded') === 'true') {
                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                } else {
                    if (chevron) chevron.style.transform = '';
                }
            }, 1); // Delay sangat kecil untuk sinkronisasi dengan Bootstrap
        });
    });

    // Close mobile sidebar when navigation link is clicked
    const sidebarNavLinks = sidebar.querySelectorAll('.nav-link:not([data-bs-toggle])');
    sidebarNavLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            if (isMobile()) {
                setTimeout(() => {
                    closeSidebar();
                }, 150);
            }
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Toggle sidebar with Ctrl + B or Cmd + B
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            toggleSidebar();
        }

        // Close sidebar with Escape key (mobile only)
        if (e.key === 'Escape' && isMobile() && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });

    // Initialize everything
    initializeSidebar();

    console.log('🎉 Responsive Sidebar initialized successfully!');
});
