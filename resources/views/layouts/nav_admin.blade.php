<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Klinik')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS Library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">




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

            /* Datatables.com */
            --dt-row-selected: 13, 110, 253;
            --dt-row-selected-text: 255, 255, 255;
            --dt-row-selected-link: 9, 10, 11;
            --dt-row-stripe: 0, 0, 0;
            --dt-row-hover: 0, 0, 0;
            --dt-column-ordering: 0, 0, 0;
            --dt-header-align-items: center;
            --dt-html-background: white;
        }

        .scroll_create_layanan::-webkit-scrollbar {
            width: 6px;
        }

        .scroll_create_layanan::-webkit-scrollbar-thumb {
            background-color: #bbb;
            border-radius: 4px;
        }

        .scroll_create_layanan::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .qr-code-box {
            width: 90px;
            /* kotak sedikit lebih besar dari QR */
            height: 90px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
        }

        .qr-code-box svg {
            width: 80px !important;
            height: 80px !important;
        }

        .info-box {
            border-radius: 10px;
            padding: 1rem;
            background-color: #f8f9fa;
            flex: 1;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        /* Base badge styling */
        .badge-saya {
            font-size: 0.55rem;
            padding: 0.5em 0.75em;
            border-radius: 0.5rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
            min-width: 80px;
            text-align: center;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid transparent;
        }

        /* Status variants */
        .badge-saya.badge-tersedia {
            background: var(--gradient-success);
            color: white;
            border-color: #047857;
        }

        .badge-saya.badge-full {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-color: #b91c1c;
        }

        .badge-saya.badge-close {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            border-color: #374151;
        }

        .badge-saya.badge-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-color: #b45309;
        }

        .badge-saya.badge-default {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #6c757d;
            border-color: #dee2e6;
        }

        .badge-saya.badge-menunggu {
            background: linear-gradient(135deg, #ffc107, #ffb300);
            color: #212529;
            border-color: #ffa000;
        }

        .badge-saya.badge-diperiksa {
            background: linear-gradient(135deg, #0dcaf0, #0aa5c2);
            color: #212529;
            border-color: #0891b2;
        }

        .badge-saya.badge-selesai {
            background: linear-gradient(135deg, #198754, #157347);
            color: white;
            border-color: #146c43;
        }

        /* Hover effects */
        .badge-saya:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: var(--transition-default);
        }

        .badge-saya.badge-tersedia:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }

        .badge-saya.badge-full:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .badge-saya.badge-close:hover {
            background: linear-gradient(135deg, #4b5563, #374151);
        }

        .badge-saya.badge-pending:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        .badge-saya.badge-default:hover {
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
        }

        .badge-saya.badge-menunggu:hover {
            background: linear-gradient(135deg, #ffb300, #ffa000);
        }

        .badge-saya.badge-diperiksa:hover {
            background: linear-gradient(135deg, #0aa5c2, #0891b2);
        }

        .badge-saya.badge-selesai:hover {
            background: linear-gradient(135deg, #157347, #146c43);
        }

        /* Size variants */
        .badge-saya.badge-outline {
            background: transparent;
            border-width: 2px;
            box-shadow: none;
        }

        .badge-saya.badge-outline.badge-tersedia {
            color: #059669;
            border-color: #059669;
        }

        /* Outline variant */
        .badge-saya.badge-outline {
            background: transparent;
            border-width: 2px;
            box-shadow: none;
        }

        .badge-saya.badge-outline.badge-tersedia {
            color: #059669;
            border-color: #059669;
        }

        .badge-saya.badge-outline.badge-full {
            color: #dc2626;
            border-color: #dc2626;
        }

        .badge-saya.badge-outline.badge-close {
            color: #4b5563;
            border-color: #4b5563;
        }

        .badge-saya.badge-outline.badge-pending {
            color: #d97706;
            border-color: #d97706;
        }

        .badge-saya.badge-outline.badge-default {
            color: #6c757d;
            border-color: #6c757d;
        }

        .badge-saya.badge-outline.badge-menunggu {
            color: #ffc107;
            border-color: #ffc107;
        }

        .badge-saya.badge-outline.badge-diperiksa {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .badge-saya.badge-outline.badge-selesai {
            color: #198754;
            border-color: #198754;
        }

        /* Animated pulse for important statuses */
        .badge-saya.badge-pill {
            border-radius: 50px;
        }

        .badge-saya.badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .badge-saya {
                font-size: 0.7rem;
                padding: 0.4em 0.6em;
                min-width: 70px;
            }
        }



        .modal-title i {
            margin-right: 0.5rem;
        }

        .section-title {
            border-left: 4px solid #0d6efd;
            padding-left: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #0d6efd;
        }

        div.dataTables_wrapper div.dataTables_length select {
            min-width: 4.5rem;
            font-weight: 700;
        }

        .dataTables_filter {
            margin-bottom: 1rem;
        }

        /* Validasi Input */
        .is-valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            margin-left: 2px;
            display: block;
        }

        .dataTables_filter input {
            border: 1px solid #ced4da;
            border-radius: 10px;
            padding: 0.55rem 1rem;
            font-size: 0.9rem;
            width: 200px !important;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dataTables_filter input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
        }

        /* Kontainer pagination */
        .dataTables_paginate {
            margin-top: 1rem;
            display: flex;
            justify-content: right;
        }

        .page-item:not(:first-child) .page-link {
            border-radius: 5px;
        }

        /* Tombol pagination */
        .dataTables_paginate .paginate_button {
            border: 1px solid #dee2e6;
            margin: 0 3px;
            background-color: white;
            color: #0d6efd;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        /* Hover effect */
        .dataTables_paginate .paginate_button:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        /* Tombol aktif */
        .dataTables_paginate .paginate_button.current {
            background-color: #0d6efd;
            color: white !important;
            border-color: #0d6efd;
        }

        /* Tombol disabled (First, Previous di halaman awal) */
        .dataTables_paginate .paginate_button.disabled {
            color: #adb5bd !important;
            cursor: not-allowed;
            background-color: #f8f9fa;
        }


        body {
            background: var(--gradient-background);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            transition: var(--transition-default);
            color: var(--text-dark);
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--bg-medical-white);
            border-radius: 12px;
            box-shadow: var(--shadow-card);
            display: flex;
            flex-direction: column;
            padding: 20px;
            min-width: 14rem;
            max-width: 14rem;
            transition: var(--transition-default);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: var(--transition-default);
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--overlay-border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            backdrop-filter: blur(10px);
            border: 1px solid var(--overlay-border);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            margin-top: -15px;
        }

        .brand-main {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .brand-sub {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .garisss {
            border-top: 1px solid var(--bg-medical-gray);
            margin-bottom: 1rem;
        }

        .nav {
            text-align: left;
        }

        .nav-item {
            margin-bottom: 10px;
            transition: var(--transition-default);
        }

        .nav-item:hover,
        .navbar-nav .nav-link:hover {
            background: var(--bg-medical-light);
            transform: translateY(-1px);
            color: var(--medical-blue);
            backdrop-filter: blur(10px);
        }

        .navbar-nav .nav-link {
            color: var(--text-muted);
            font-weight: 500;
            margin: 0 8px;
            padding: 8px 16px;
            border-radius: 8px;
            display: block;
            text-decoration: none;
            transition: var(--transition-default);
        }

        .navbar-nav .nav-link.active {
            background: var(--bg-medical-light);
            color: var(--medical-blue);
            font-weight: bold;
            box-shadow: var(--shadow-medical);
            backdrop-filter: blur(15px);
        }

        .medical-navbar {
            background: var(--gradient-primary);
            box-shadow: var(--shadow-card);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--overlay-border-light);
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .dropdown-menu {
            background-color: var(--bg-medical-white);
            border: none;
            padding: 0.4rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-card);
            transition: var(--transition-default);
        }

        .dropdown-item {
            margin: 5px 0;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            color: var(--text-dark);
            transition: var(--transition-default);
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: var(--bg-medical-light);
            color: var(--medical-blue);
            font-weight: bold;
        }

        .notification-btn {
            background: var(--overlay-light);
            border: 1px solid var(--overlay-border);
            color: var(--bg-medical-white);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-default);
            position: relative;
        }

        .notification-btn:hover {
            background: var(--overlay-hover);
            transform: translateY(-1px);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--notif-accent-red);
            color: var(--bg-medical-white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .notification-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--notif-accent-green);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-medical);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 3;
        }

        .notification-error {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--notif-accent-red);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-medical);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 3;
        }

        .notification.show {
            transform: translateX(0);
        }

        #sidebar {
            width: 250px;
            min-height: 100vh;
            transition: var(--transition-default);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 2;
            background-color: var(--bg-medical-white);
        }

        #page-content {
            margin-left: 250px;
            transition: var(--transition-default);
            padding: 1.5rem;
            max-width: 170vh;
        }

        #wrapper.sidebar-toggled #sidebar {
            margin-left: -250px;
        }

        #wrapper.sidebar-toggled #page-content {
            margin-left: 0;
            min-width: 100%;
        }

        /* Dropdown Loqout Admin */
        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--medical-light) 0%, rgba(37, 99, 235, 0.05) 100%);
            color: var(--medical-blue);
        }

        .breadcrumb {
            background: linear-gradient(135deg, var(--medical-light) 0%, rgba(37, 99, 235, 0.05) 100%);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            border: 1px solid var(--blue-border-transparent);
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item a {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            color: var(--medical-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--medical-teal);
            transform: translateX(2px);
        }

        .breadcrumb-item.active {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            color: var(--text-light);
            font-weight: 600;
        }

        /* Search Form + Tambah + Delete */
        .cardstd {
            background: var(--medical-white);
            border-radius: 16px;
            border: 1px solid var(--blue-border-transparent);
            min-height: 10rem;
            max-height: 12rem;
            overflow: hidden;
        }

        .form-control,
        .form-select {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border-radius: 10px;
            border: 2px solid var(--blue-border-transparent);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--medical-blue);
            box-shadow: 0 0 0 0.2rem var(--overlay-light);
        }

        .input-group .btn {
            border-radius: 0 10px 10px 0;
            padding: 0.75rem 1.25rem;
        }

        /* Statistics Card */
        .stats-card {
            background: linear-gradient(135deg, var(--medical-light) 0%, var(-blue-light-transparent) 100%);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--medical-teal);
        }

        .stats-text {
            color: var(--text-light);
            font-weight: 500;
        }

        .stats-number {
            color: var(--medical-blue);
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            width: auto;
        }

        /* Table */
        .custom-table {
            background: var(--bg-medical-white);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            border: none;
            transition: var(--transition-default);
        }

        .custom-table:hover {
            box-shadow: var(--glow-medical);
        }

        /* Header Styling */
        .custom-table th {
            background: var(--bg-medical-white);
            color: black;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 1rem 0.75rem;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
            position: relative;
            user-select: none;
            border: none;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Rounded corners for header */
        .custom-table th:first-child {
            border-radius: 12px 0 0 0;
        }

        .custom-table th:last-child {
            border-radius: 0 12px 0 0;
        }

        /* Body Styling */
        .custom-table td {
            vertical-align: middle;
            text-align: left;
            white-space: nowrap;
            font-size: 0.875rem;
            padding: 0.75rem;
            border: none;
            border-bottom: 1px solid var(--gray-light);
            color: var(--text-dark);
            transition: var(--transition-default);
        }

        /* Row hover effects */
        .custom-table tbody tr {
            transition: var(--transition-default);
            border: none;
        }

        .custom-table tbody tr:hover {
            background: var(--blue-light-transparent);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.08);
        }

        /* Rounded corners for last row */
        .custom-table tbody tr:last-child td:first-child {
            border-radius: 0 0 0 12px;
        }

        .custom-table tbody tr:last-child td:last-child {
            border-radius: 0 0 12px 0;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Column specific styling */
        .custom-table td:first-child,
        .custom-table th:first-child {
            min-width: 50px;
            padding-left: 1rem;
        }

        .custom-table td:nth-child(-n+3),
        .custom-table th:nth-child(-n+3) {
            min-width: 50px;
        }

        .custom-table td:nth-child(4),
        .custom-table th:nth-child(4) {
            min-width: 50px;
        }

        .custom-table td:nth-child(5),
        .custom-table th:nth-child(5) {
            min-width: 100px;
        }

        .custom-table td:nth-child(6),
        .custom-table th:nth-child(6) {
            min-width: 50px;
        }

        /* Button styling within table */
        .custom-table td .btn {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: var(--transition-default);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .custom-table td .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Checkbox styling */
        .custom-table input[type="checkbox"] {
            width: 1.2rem;
            height: 1.2rem;
            accent-color: var(--medical-blue);
            cursor: pointer;
            transition: var(--transition-default);
        }

        .custom-table input[type="checkbox"]:hover {
            transform: scale(1.1);
        }

        /* Medical status indicators */
        .custom-table .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .custom-table .status-normal {
            background-color: var(--status-normal);
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.4);
        }

        .custom-table .status-warning {
            background-color: var(--status-warning);
            box-shadow: 0 0 6px rgba(245, 158, 11, 0.4);
        }

        .custom-table .status-critical {
            background-color: var(--status-critical);
            box-shadow: 0 0 6px rgba(239, 68, 68, 0.4);
        }

        .custom-table .status-info {
            background-color: var(--status-info);
            box-shadow: 0 0 6px rgba(59, 130, 246, 0.4);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .custom-table {
                border-radius: 8px;
            }

            .custom-table th:first-child {
                border-radius: 8px 0 0 0;
            }

            .custom-table th:last-child {
                border-radius: 0 8px 0 0;
            }

            .custom-table tbody tr:last-child td:first-child {
                border-radius: 0 0 0 8px;
            }

            .custom-table tbody tr:last-child td:last-child {
                border-radius: 0 0 8px 0;
            }

            .custom-table th,
            .custom-table td {
                padding: 0.5rem 0.4rem;
                font-size: 0.8rem;
            }

            .custom-table td:nth-child(2),
            .custom-table th:nth-child(2) {
                min-width: 100px;
            }
        }

        /* Loading state */
        .custom-table.loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .custom-table.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--medical-blue);
            border-top: 2px solid transparent;
            border-radius: 50%;
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

        /* DataTables integration */
        .custom-table.dataTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table.dataTable thead th {
            border-bottom: 2px solid var(--medical-blue);
        }

        .custom-table.dataTable tbody td {
            border-top: none;
        }

        /* Pagination for DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: var(--bg-medical-white);
            border: 1px solid var(--blue-border-transparent);
            color: var(--medical-blue) !important;
            transition: var(--transition-default);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--medical-blue);
            color: white !important;
            border-color: var(--medical-blue);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--gradient-primary);
            color: white !important;
            border-color: var(--medical-blue);
        }

        .daterangepicker {
            right: 29px !important;
        }

        .live_waktu {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: var(--bg-medical-light);
            color: var(--text-muted);
            border-radius: 5px;
        }

        .anti_salin {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .card_table {
            max-width: 170vh;
        }

        .notification-container {
            background-color: var(--bg-medical-light);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .notification-list {
            list-style: none;
        }

        .notification-item {
            border: 1px solid var(--shadow-medical);
            border-radius: 6px;
            padding: 5px;
            margin-bottom: 15px;
            background-color: var(--bg-medical-white);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .notification-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px var(--shadow-medical);
        }

        .notification-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .notification-category {
            font-weight: 500;
            color: #3498db;
            display: flex;
            align-items: center;
        }

        .notification-category i {
            margin-right: 5px;
        }

        .notification-content {
            margin-bottom: 10px;
        }

        .notification-text {
            display: flex;
            align-items: flex-start;
        }

        .notification-text i {
            margin-right: 5px;
            color: #e74c3c;
            margin-top: 3px;
        }

        .notification-text strong {
            font-weight: 500;
        }

        .notification-footer {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        .notification-footer i {
            margin-right: 5px;
        }

        .notification-footer b {
            color: #7f8c8d;
        }

        .timetable {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .timetable th,
        .timetable td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
        }

        .timetable th {
            background-color: #0d6efd;
            color: #fff;
        }

        .keterangan-col {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: left;
        }

        /* Biar input filter rapi */
        .filter-input {
            width: 100%;
            padding: 4px;
            font-size: 0.85rem;
            text-align: center;
        }



        /* Global Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @media (max-width: 575.98px) {
            .sidebar {
                display: none;
                /* Sembunyikan sidebar di layar kecil */
            }

            #page-content {
                padding: 0.75rem;
                margin-left: 0;
            }

            .navbar-brand {
                font-size: 1.1rem;
                gap: 8px;
            }

            .brand-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }

            .brand-main {
                font-size: 1rem;
            }

            .brand-sub {
                font-size: 0.65rem;
            }

            .form-control,
            .form-select {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            .stats-card {
                padding: 0.75rem 1rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .custom-table th,
            .custom-table td {
                font-size: 0.75rem;
                padding: 0.4rem;
            }

            .notification-success,
            .notification-error {
                top: 10px;
                right: 10px;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 2;
                transform: translateX(-100%);
            }

            #wrapper.sidebar-toggled #sidebar {
                transform: translateX(0);
            }

            #page-content {
                padding: 1rem;
                margin-left: 0;

            }

            .medical-navbar {
                padding: 0.5rem;
            }

            .navbar-collapse {
                background: var(--medical-blue);
                backdrop-filter: blur(20px);
                border-radius: 12px;
                margin-top: 15px;
                padding: 20px;
                border: 1px solid var(--overlay-border-light);
            }

            .navbar-nav .nav-link {
                margin: 5px 0;
                padding: 12px 16px;
            }

            .user-menu {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid var(--overlay-border-light);
                justify-content: center;
            }
        }

        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }

        .select2-container .select2-selection--single {
            height: 50px;
            display: flex;
            align-items: center;
            /* Vertikal center */
            padding: 0 0.75rem;
            /* Horizontal padding */
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            /* Hilangkan default */
            flex: 1;
            text-align: left;
            /* atau center jika mau horizontal center */
            font-size: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
            right: 10px;
        }




        /* Area konten utama */
        .flex-grow-1 {
            background-color: var(--white);
            /* Latar putih */
            border-radius: 12px;
            /* Sudut membulat */
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.06);
            /* Bayangan lembut */
            padding: 30px;
            /* Spasi dalam */
            margin: 0px 5px;
            /* Jarak antar elemen */
            transition: box-shadow 0.3s ease-in-out;
            /* Transisi bayangan */
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        @if (Auth::check() && Auth::user()->role == 'admin')
            <div class="anti_salin sidebar shadow" id="sidebar">
                <a class="navbar-brand text-decoration-none" href="/admin">
                    <div class="brand-icon">
                        <i class="bi bi-hospital-fill"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-main">MediCare</span>
                        <span class="brand-sub">Admin Dashboard</span>
                    </div>
                </a>

                <div class="garisss"></div>

                <ul class="nav navbar-nav mx-auto">
                    <li class="nav-item">
                        <a href="{{ route('admin') }}"
                            class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('indikasi.index') }}"
                            class="nav-link {{ request()->routeIs('indikasi.*') ? 'active' : '' }}">
                            <i class="bi bi-heart-pulse"></i>
                            <span>Layanan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sesi.index') }}"
                            class="nav-link {{ request()->routeIs('sesi.*') ? 'active' : '' }}">
                            <i class="bi bi-clock"></i>
                            <span>Sesi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ruangan.index') }}"
                            class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>
                            <span>Ruangan</span>
                        </a>
                    </li>

                    @php
                        $terapisActive =
                            request()->routeIs('dokter.*') ||
                            request()->routeIs('admin.pemeriksaan.periksa') ||
                            request()->routeIs('pemeriksaan.*');
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ $terapisActive ? '' : 'collapsed' }}"
                            href="#terapisMenu" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $terapisActive ? 'true' : 'false' }}" aria-controls="terapisMenu">
                            <span><i class="bi bi-prescription2"></i>Dokter</span>
                            <i class="bi bi-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ $terapisActive ? 'show' : '' }}" id="terapisMenu">
                            <ul class="nav flex-column ms-3">
                                <li class="nav-item">
                                    <a href="{{ route('dokter.index') }}"
                                        class="nav-link {{ request()->routeIs('dokter.index') ? 'active' : '' }}">
                                        Data Dokter
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dokter.jadwal_harian') }}"
                                        class="nav-link {{ request()->routeIs('dokter.jadwal_harian') ? 'active' : '' }}">
                                        Table Jadwal
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.pemeriksaan.periksa') }}"
                                        class="nav-link {{ request()->routeIs('admin.pemeriksaan.periksa') ? 'active' : '' }}">
                                        Periksa
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pemeriksaan.index') }}"
                                        class="nav-link {{ request()->routeIs('pemeriksaan.index') ? 'active' : '' }}">
                                        Hasil Pemeriksaan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pasien.index') }}"
                            class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                            <i class="bi bi-person-vcard-fill"></i>
                            <span>Pasien</span>
                        </a>
                    </li>
                    <!-- Kunjungan -->
                    @php
                        $kunjunganActive =
                            request()->routeIs('kunjungan.*') || request()->routeIs('admin.kunjungan.hari_ini');
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ $kunjunganActive ? '' : 'collapsed' }}"
                            href="#kunjunganMenu" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $kunjunganActive ? 'true' : 'false' }}" aria-controls="kunjunganMenu">
                            <span><i class="bi bi-calendar-check me-2"></i>Kunjungan</span>
                            <i class="bi bi-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ $kunjunganActive ? 'show' : '' }}" id="kunjunganMenu">
                            <ul class="nav flex-column ms-3">
                                <li class="nav-item">
                                    <a href="{{ route('kunjungan.index') }}"
                                        class="nav-link {{ request()->routeIs('kunjungan.index') ? 'active' : '' }}">
                                        Semua Kunjungan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.kunjungan.hari_ini') }}"
                                        class="nav-link {{ request()->routeIs('admin.kunjungan.hari_ini') ? 'active' : '' }}">
                                        Kunjungan Hari Ini
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('kunjungan.laporan') }}"
                                        class="nav-link {{ request()->routeIs('kunjungan.laporan') ? 'active' : '' }}">
                                        Laporan Kunjungan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}"
                            class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-medical"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <!-- Main Content -->
        <div class="d-flex flex-column min-vh-100 w-100" id="page-content">
            @yield('content')
        </div>

        {{-- Modal Konfirmasi Global --}}
        <div class="modal fade" id="modal-konfirmasi-delete" tabindex="-1" aria-labelledby="modalDeleteLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0" id="modalDeleteLabel">
                            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                            Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteModalMessage" class="mb-0">
                            Yakin ingin menghapus data yang dipilih?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Script CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment-with-locales.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>




    <!-- Select2 Init -->
    <script>
        $(document).ready(function() {

            // Inisialisasi Select2
            $('#kategori_filter').select2({
                placeholder: 'Pilih Data'
            });

            // Fungsi untuk tampilkan filter tambahan sesuai kategori
            function tampilkanFilter() {
                let kategori = $('#kategori_filter').val();
                let html = '';

                if (kategori === 'pasien') {
                    html += `
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label>Nama Pasien</label>
                                <input type="text" name="nama_pasien" class="form-control" value="{{ request('nama_pasien') }}">
                            </div>
                            <div class="col-md-4">
                                <label>Jenis Kelamin</label>
                                <select name="jk" class="form-select">
                                    <option value="">-- Semua --</option>
                                    <option value="L" {{ request('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ request('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Rentang Umur</label>
                                <input type="text" name="umur_range" id="umur_range" class="form-control" placeholder="Contoh: 20-40" value="{{ request('umur_range') }}">
                            </div>
                        </div>
                    `;
                } else if (kategori === 'dokter') {
                    html += `
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label>Nama Dokter</label>
                                <input type="text" name="nama_dokter" class="form-control" value="{{ request('nama_dokter') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Spesialisasi</label>
                                <input type="text" name="spesialis" class="form-control" value="{{ request('spesialis') }}">
                            </div>
                        </div>
                    `;
                } else if (kategori === 'keluhan' || kategori === 'sesi' || kategori === 'kunjungan') {
                    html += `
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label>Rentang Tanggal</label>
                                <input type="text" name="tanggal_range" id="tanggal_range" class="form-control" value="{{ request('tanggal_range') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Kata Kunci</label>
                                <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}">
                            </div>
                        </div>
                    `;
                } else if (kategori === 'jadwal') {
                    html += `
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label>Nama Dokter</label>
                                <input type="text" name="nama_dokter" class="form-control" value="{{ request('nama_dokter') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Tanggal</label>
                                <input type="text" name="tanggal_jadwal" id="tanggal_jadwal" class="form-control" value="{{ request('tanggal_jadwal') }}">
                            </div>
                        </div>
                    `;
                }

                $('#filter_dinamis').html(html);

                // Inisialisasi DateRangePicker jika ada tanggal
                $('#tanggal_range').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    autoUpdateInput: false
                });

                $('#tanggal_range').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#tanggal_range').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

                $('#tanggal_jadwal').daterangepicker({
                    singleDatePicker: true,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });
            }

            tampilkanFilter(); // Load awal
            $('#kategori_filter').change(tampilkanFilter); // Saat kategori diganti
        });
    </script>

    <!-- Live Clock -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Atur locale sesuai bahasa aktif dari Laravel
            moment.locale('{{ app()->getLocale() }}');

            function updateLiveTime() {
                const now = moment();
                const waktu = now.format('dddd, D MMMM YYYY [|] HH:mm:ss');

                const liveTimeElement = document.getElementById('liveTime'); // didefinisikan DI SINI
                if (liveTimeElement) {
                    liveTimeElement.textContent = waktu;
                }

                // Pilih semua elemen dengan class live_Time
                const elements = document.querySelectorAll('.live_Time');
                elements.forEach(el => {
                    el.textContent = waktu;
                });
            }

            updateLiveTime();
            setInterval(updateLiveTime, 1000);
        });
    </script>

    <!-- Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnToggle = document.getElementById('toggleSidebarBtn');
            if (btnToggle) {
                btnToggle.addEventListener('click', function() {
                    document.getElementById('wrapper').classList.toggle('sidebar-toggled');
                });
            }
        });
    </script>


    <!-- Active Sidebar Highlight -->
    <script>
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove(
                    'active'));
                this.classList.add('active');
            });
        });
    </script>

    <!-- Notification Auto Close -->
    <script>
        // Notif Untuk Error
        function showError(message) {
            const existing = document.getElementById('notification-error');
            if (existing) existing.remove();

            const notif = document.createElement('div');
            notif.className = 'notification notification-error show';
            notif.innerHTML = `<i class="bi bi-exclamation-octagon-fill"></i> ${message}`;
            document.body.appendChild(notif);

            setTimeout(() => {
                notif.classList.remove('show');
                notif.remove();
            }, 4000);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const notifications = document.querySelectorAll(".notification");

            notifications.forEach(notif => {
                setTimeout(() => notif.classList.add("show"), 100);
                setTimeout(() => notif.classList.remove("show"), 4000);
            });
        });
    </script>

    <!-- Bulk Delete Validation + Select All -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('modal-konfirmasi-delete');
            if (!modalElement) return;

            const modalDelete = new bootstrap.Modal(modalElement);
            const btnConfirm = document.getElementById('btn-confirm-delete');
            const modalMsg = document.getElementById('deleteModalMessage');

            // Handler: Trigger tombol hapus
            document.querySelectorAll('.btn-trigger-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const formSelector = this.getAttribute('data-form');
                    const type = this.getAttribute('data-type') || 'data';
                    const form = document.querySelector(formSelector);
                    const checkboxes = form ? form.querySelectorAll(
                        `input[name="selected_${type}[]"]:checked`) : [];

                    if (checkboxes.length === 0) {
                        modalMsg.textContent = `⚠️ Pilih minimal satu ${type} untuk dihapus!`;
                        btnConfirm.classList.add('d-none');
                    } else {
                        modalMsg.textContent =
                            `Yakin ingin menghapus ${checkboxes.length} ${type}?`;
                        btnConfirm.classList.remove('d-none');
                    }

                    btnConfirm.onclick = () => form && form.submit();
                    modalDelete.show();
                });
            });

            // Handler: Select All Checkbox
            document.querySelectorAll('input[type="checkbox"][id^="select-all"]').forEach(masterCheckbox => {
                const type = masterCheckbox.getAttribute('data-type') || 'data';
                const itemSelector = `input[name="selected_${type}[]"]`;

                masterCheckbox.addEventListener('change', function() {
                    document.querySelectorAll(itemSelector).forEach(cb => cb.checked = this
                        .checked);
                });

                // Sync master checkbox if individual checkboxes changed
                document.querySelectorAll(itemSelector).forEach(cb => {
                    cb.addEventListener('change', function() {
                        const allChecked = [...document.querySelectorAll(itemSelector)]
                            .every(i => i.checked);
                        masterCheckbox.checked = allChecked;
                    });
                });
            });
        });
    </script>




    <!-- Date Range Picker Filter -->
    <script>
        $(function() {
            const drp = $('#date_range');
            drp.daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });
            drp.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                $(this).closest('form').submit();
            });
            drp.on('cancel.daterangepicker', function() {
                $(this).val('');
                $(this).closest('form').submit();
            });
        });
    </script>

    <!-- User Avatar (Placeholder) -->
    <script>
        document.querySelector('.user-avatar')?.addEventListener('click', function() {
            alert('Menu Profile:\n\n• Pengaturan Akun\n• Ganti Password\n• Logout');
        });
    </script>

    <!-- Mobile Menu Auto-Close -->
    <script>
        document.addEventListener('click', function(e) {
            const navbar = document.querySelector('.navbar-collapse');
            const toggleBtn = document.querySelector('.navbar-toggler');

            if (navbar && toggleBtn && navbar.classList.contains('show') && !navbar.contains(e.target) && !toggleBtn
                .contains(e.target)) {
                navbar.classList.remove('show');
            }
        });
    </script>

    <!-- Table -->
    <script>
        $(document).ready(function() {
            var languageOptions = {
                en: {
                    search: '',
                    searchPlaceholder: 'Search...',
                    lengthMenu: 'Show _MENU_ entries per page',
                    zeroRecords: 'No matching records found',
                    info: '<strong>Total:</strong> _TOTAL_ | <strong>Displayed:</strong> _START_ - _END_ | <strong>Page:</strong> _PAGE_ / _PAGES_ | <strong>Rows:</strong> _START_ - _END_',
                    infoEmpty: '<strong>Total Complaints:</strong> 0 | <strong>Displayed:</strong> 0 | <strong>Page:</strong> 0 / 0 | <strong>Rows:</strong> 0 - 0',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    }
                },
                id: {
                    search: '',
                    searchPlaceholder: 'Cari...',
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    zeroRecords: 'Tidak ada data ditemukan',
                    info: '<strong>Total:</strong> _TOTAL_ | <strong>Ditampilkan:</strong> _START_ - _END_ | <strong>Halaman:</strong> _PAGE_ / _PAGES_ | <strong>Baris:</strong> _START_ - _END_',
                    infoEmpty: '<strong>Total Keluhan:</strong> 0 | <strong>Ditampilkan:</strong> 0 | <strong>Halaman:</strong> 0 / 0 | <strong>Baris:</strong> 0 - 0',
                    infoFiltered: '(disaring dari _MAX_ total data)',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            };

            var currentLocale = '{{ app()->getLocale() }}';

            // Inisialisasi DataTables saat modal dibuka
            $(document).on('shown.bs.modal', function(e) {
                setTimeout(() => {
                    $(e.target).find('table.datatable-dokter').each(function() {
                        if (!$.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable({
                                scrollY: 200, // scroll vertikal, atur tinggi sesuai kebutuhan
                                scrollX: true, // aktifkan scroll horizontal
                                scrollCollapse: true,
                                pageLength: 5,
                                paging: false,
                                pagingType: 'simple_numbers',
                                searching: true,
                                ordering: true,
                                info: false,
                                searchBox: false,
                                lengthChange: true,
                                lengthMenu: [
                                    [5, 10, 25, 50, 100],
                                    [5, 10, 25, 50, 100]
                                ],
                                language: languageOptions[currentLocale] ||
                                    languageOptions.id,
                            });
                        }
                    });
                }, 100); // Delay kecil untuk memastikan DOM siap
            });


            $('#table').DataTable({
                scrollY: 1000000, // scroll vertikal, atur tinggi sesuai kebutuhan
                scrollX: true, // aktifkan scroll horizontal
                scrollCollapse: true,
                destroy: true,
                paging: true,
                pageLength: 2,
                pagingType: 'simple_numbers',
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                lengthMenu: [
                    [2, 10, 25, 50, 100],
                    [2, 10, 25, 50, 100]
                ],
                language: languageOptions[currentLocale],
            });
        });
    </script>

    <!-- Form Dokter Pada Input Select Jenis Poli -->
    <script>
        $(document).ready(function() {
            $('#selectSpesialis').select2({
                tags: true,
                placeholder: "Pilih atau ketik spesialis baru",
                width: '100%',
                height: '50%',
                allowClear: true
            });
        });
    </script>

    <!-- Translate -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('[data-translate]');
            elements.forEach(el => {
                const original = el.dataset.translate;
                fetch(`/translate?text=${encodeURIComponent(original)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.translated) {
                            // Cek jika elemen punya placeholder
                            if (el.hasAttribute('placeholder')) {
                                el.setAttribute('placeholder', data.translated);
                            }
                            // Atau jika elemen <option> atau lainnya
                            else if (el.tagName === 'OPTION') {
                                el.textContent = data.translated;
                            } else {
                                el.innerText = data.translated;
                            }
                        }
                    })
                    .catch(err => console.warn('Translate error:', err));
            });
        });
    </script>

    <!-- Ganti Bahasa -->
    <script>
        document.querySelectorAll('.lang-switch').forEach(item => {
            if (!item) return;
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload(); // Refresh agar semua teks berubah
                    }
                }).catch(error => {
                    console.error('Gagal ganti bahasa:', error);
                });
            });
        });
    </script>

    <!-- Modal Store Layanan -->
    <script>
        $(document).ready(function() {
            function cekSemuaValid() {
                let semuaValid = true;
                const inputs = $('input[name="Nama_Layanan[]"]');

                if (inputs.length === 0) {
                    semuaValid = false;
                }

                inputs.each(function() {
                    const val = $(this).val().trim();
                    if (val === '' || $(this).hasClass('is-invalid')) {
                        semuaValid = false;
                        return false; // break loop
                    }
                });

                $('#btn_tambah_layanan').prop('disabled', !semuaValid);
                $('#btn_simpan_layanan').prop('disabled', !semuaValid);
            }

            // Panggil saat pertama kali load
            cekSemuaValid();

            // Tambah input baru
            $('#btn_tambah_layanan').on('click', function() {
                let isEmpty = false;

                $('input[name="Nama_Layanan[]"]').each(function() {
                    if ($(this).val().trim() === '') {
                        isEmpty = true;
                    }
                });

                if (isEmpty) {
                    showError('❗ Isi jenis layanan terlebih dahulu.');
                    return;
                }

                const html = `
                <div class="col-md-6 keluhan-input mb-3">
                    <div class="form-group position-relative">
                        <label class="form-label fw-semibold">Jenis Layanan</label>
                        <div class="position-relative">
                            <input type="text" name="Nama_Layanan[]" class="form-control pe-5"
                                placeholder="Contoh: Layanan Anak / Pijat Refleksi" required>

                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 feedback-icon">
                                    <span class="spinner-border spinner-border-sm text-secondary d-none loader-icon"></span>
                                </span>
                            </div>

                            <button type="button" class="btn btn-outline-danger btn-sm mt-2 remove-keluhan">
                                <i class="bi bi-trash-fill"></i> Hapus
                            </button>

                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="col-md-6 d-none">
                        <input type="text" class="form-control form-control-lg" placeholder="Keterangan / Catatan Opsional">
                    </div>
                </div>
                `;
                $('#input-keluhan-wrapper').append(html);
                cekSemuaValid();
            });

            // Hapus input
            $(document).on('click', '.remove-keluhan', function() {
                $(this).closest('.keluhan-input').remove();
                cekSemuaValid();
            });

            // AJAX: Validasi Nama_Layanan duplikat
            function debounce(func, delay) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), delay);
                };
            }

            $(document).on('input', 'input[name="Nama_Layanan[]"]', debounce(function() {
                const input = $(this);
                const value = input.val().trim();
                const formGroup = input.closest('.form-group');
                const feedback = formGroup.find('.invalid-feedback');
                const iconContainer = formGroup.find('.feedback-icon');
                const loaderIcon = iconContainer.find('.loader-icon');
                const validIcon = iconContainer.find('.valid-icon');
                const invalidIcon = iconContainer.find('.invalid-icon');

                // Reset state
                input.removeClass('is-valid is-invalid');
                feedback.text('');
                validIcon.addClass('d-none');
                invalidIcon.addClass('d-none');
                loaderIcon.removeClass('d-none');

                if (value.length < 3) {
                    loaderIcon.addClass('d-none');
                    cekSemuaValid();
                    return;
                }

                $.get("{{ route('indikasi.checkLayanan') }}", {
                    Nama_Layanan: value
                }, function(res) {
                    loaderIcon.addClass('d-none');

                    if (res.exists) {
                        input.addClass('is-invalid');
                        feedback.text('⚠️ Jenis layanan ini sudah ada!');
                        invalidIcon.removeClass('d-none');
                    } else {
                        input.addClass('is-valid');
                        validIcon.removeClass('d-none');
                    }
                    cekSemuaValid();
                }).fail(function() {
                    loaderIcon.addClass('d-none');
                    input.addClass('is-invalid');
                    feedback.text('❌ Gagal memeriksa nama layanan.');
                    invalidIcon.removeClass('d-none');
                    cekSemuaValid();
                });
            }, 500));

            function showError(message) {
                const existing = document.getElementById('notification-error');
                if (existing) existing.remove();

                const notif = document.createElement('div');
                notif.className = 'notification notification-error';
                notif.id = 'notification-error';
                notif.innerHTML = `<i class="bi bi-exclamation-octagon-fill"></i> ${message}`;
                document.body.appendChild(notif);

                setTimeout(() => notif.classList.add("show"), 100);
                setTimeout(() => {
                    notif.classList.remove("show");
                    setTimeout(() => notif.remove(), 400);
                }, 4000);
            }
        });
    </script>


    <!-- script Modal Edit Layanan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalEditKeluhan');
            const form = document.getElementById('formEditKeluhan');
            const inputNama = modal.querySelector('#editNama');
            const btnSimpan = modal.querySelector('#btn_update_keluhan');
            const loader = modal.querySelector('#loader-validasi');
            const feedback = modal.querySelector('.invalid-feedback');

            // Fungsi validasi
            function cekValidEdit() {
                const val = inputNama.value.trim();

                if (val.length < 3) {
                    inputNama.classList.remove('is-valid', 'is-invalid');
                    feedback.textContent = '';
                    btnSimpan.disabled = true;
                    loader.classList.add('d-none');
                    return;
                }

                loader.classList.remove('d-none'); // Show loader

                $.get("{{ route('indikasi.checkLayanan') }}", {
                    Nama_Layanan: val
                }, function(res) {
                    loader.classList.add('d-none'); // Hide loader
                    inputNama.classList.remove('is-valid', 'is-invalid');
                    feedback.textContent = '';

                    if (res.exists && val !== inputNama.dataset.original) {
                        inputNama.classList.add('is-invalid');
                        feedback.textContent =
                            '⚠️ Nama layanan ini sudah digunakan. Mohon gunakan nama lain.';
                        btnSimpan.disabled = true;
                    } else {
                        inputNama.classList.add('is-valid');
                        btnSimpan.disabled = false;
                    }
                }).fail(function() {
                    loader.classList.add('d-none'); // Sembunyikan loader kalau gagal
                    inputNama.classList.add('is-invalid');
                    feedback.textContent = '❌ Gagal memeriksa nama layanan. Coba lagi.';
                    btnSimpan.disabled = true;
                });
            }

            // Isi modal ketika klik tombol edit
            document.querySelectorAll('.btn-edit-keluhan').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');

                    modal.querySelector('#editKode').value = id;
                    inputNama.value = nama;
                    inputNama.dataset.original = nama;
                    inputNama.classList.remove('is-valid', 'is-invalid');
                    feedback.textContent = '';
                    loader.classList.add('d-none');
                    btnSimpan.disabled = true;

                    form.action = `{{ route('indikasi.update', ':id') }}`.replace(':id', id);
                });
            });

            // Validasi input nama saat diubah
            inputNama.addEventListener('input', debounce(cekValidEdit, 400));

            // Fungsi debounce untuk delay input
            function debounce(func, delay) {
                let timer;
                return function() {
                    clearTimeout(timer);
                    timer = setTimeout(func, delay);
                };
            }
        });
    </script>

    <!-- script Modal Store Sesi -->
    <script>
        $(document).ready(function() {
            // ====== Fungsi: Cek Semua Input Valid ======
            function cekFormLengkap() {
                const nama = $('#nama_sesi');
                const mulai = $('#mulai_sesi');
                const selesai = $('#selesai_sesi');

                const namaValid = !nama.hasClass('is-invalid') && nama.val().trim().length >= 2;
                const jamValid = mulai.val() && selesai.val() && selesai.val() > mulai.val();

                $('#btn_simpan_sesi').prop('disabled', !(namaValid && jamValid));
            }

            // ====== Validasi Nama Sesi (Live Check via AJAX) ======
            $('#nama_sesi').on('input', function() {
                const input = $(this);
                const value = input.val().trim();
                const feedback = input.siblings('.invalid-feedback');
                const loader_sesi = document.querySelector('#loader-validasi-sesi');

                input.removeClass('is-valid is-invalid');
                feedback.text('');

                if (value.length >= 3) {
                    loader_sesi.classList.remove('d-none'); // Tampilkan loader

                    $.get("{{ route('sesi.checkStore') }}", {
                        Nama_Sesi: value
                    }, function(res) {
                        loader_sesi.classList.add('d-none'); // Sembunyikan loader

                        if (res.exists) {
                            input.addClass('is-invalid');
                            feedback.text('❗ Nama sesi sudah digunakan.');
                        } else {
                            input.addClass('is-valid');
                        }

                        cekFormLengkap();
                    }).fail(function() {
                        loader_sesi.classList.add('d-none');
                        input.addClass('is-invalid');
                        feedback.text('❌ Gagal memeriksa nama sesi.');
                        cekFormLengkap();
                    });
                } else {
                    input.addClass('is-invalid');
                    feedback.text('❗ Minimal 2 karakter.');
                    cekFormLengkap();
                }
            });

            // ====== Validasi Jam Mulai & Selesai + Cek Bentrok (AJAX) ======
            $('#mulai_sesi, #selesai_sesi').on('change', function() {
                const mulai = $('#mulai_sesi');
                const selesai = $('#selesai_sesi');
                const feedbackMulai = mulai.siblings('.invalid-feedback');
                const feedbackSelesai = selesai.siblings('.invalid-feedback');

                mulai.removeClass('is-valid is-invalid');
                selesai.removeClass('is-valid is-invalid');
                feedbackMulai.text('');
                feedbackSelesai.text('');

                const mulaiVal = mulai.val();
                const selesaiVal = selesai.val();

                if (mulaiVal && selesaiVal) {
                    if (selesaiVal <= mulaiVal) {
                        mulai.addClass('is-invalid');
                        selesai.addClass('is-invalid');
                        feedbackSelesai.text('❗ Jam selesai harus lebih besar dari jam mulai.');
                        $('#btn_simpan_sesi').prop('disabled', true);
                    } else {
                        $.get("{{ route('sesi.checkStoreJamBentrok') }}", {
                            Mulai_Sesi: mulaiVal,
                            Selesai_Sesi: selesaiVal
                        }, function(res) {
                            if (res.bentrok) {
                                mulai.addClass('is-invalid');
                                selesai.addClass('is-invalid');

                                const info = res.bentrok_with ?
                                    ` dengan sesi "${res.bentrok_with}"` : '';
                                feedbackSelesai.text('❗ Jam ini bertabrakan' + info + '.');

                                $('#btn_simpan_sesi').prop('disabled', true);
                            } else {
                                mulai.addClass('is-valid');
                                selesai.addClass('is-valid');
                                feedbackSelesai.text('');
                                cekFormLengkap();
                            }
                        });
                    }
                } else {
                    $('#btn_simpan_sesi').prop('disabled', true);
                }
            });

            // ====== Reset Form Saat Modal Ditutup ======
            $('#modalTambahSesi').on('hidden.bs.modal', function() {
                $('#nama_sesi, #mulai_sesi, #selesai_sesi')
                    .val('')
                    .removeClass('is-valid is-invalid');

                $('.invalid-feedback').text('');
                $('#btn_simpan_sesi').prop('disabled', true);
            });
        });
    </script>

    <!-- script Modal Edit Sesi -->
    <script>
        function debounce(fn, delay) {
            let timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, arguments), delay);
            };
        }

        const validateEditSesi = debounce(function() {
            const nama = $('#nama_sesi_edit');
            const mulai = $('#mulai_sesi_edit');
            const selesai = $('#selesai_sesi_edit');
            const id = $('#id_sesi_edit').val();

            const namaVal = nama.val().trim();
            const mulaiVal = mulai.val();
            const selesaiVal = selesai.val();

            // Reset state awal
            $('#btn_update_sesi').prop('disabled', true);
            nama.removeClass('is-invalid is-valid');
            mulai.removeClass('is-invalid is-valid');
            selesai.removeClass('is-invalid is-valid');
            $('#nama_sesi_edit_feedback').text('');
            $('#mulai_sesi_edit_feedback').text('');
            $('#selesai_sesi_edit_feedback').text('');

            // Validasi client-side awal
            let valid = true;

            if (namaVal.length < 2) {
                nama.addClass('is-invalid');
                $('#nama_sesi_edit_feedback').text('❗ Minimal 2 karakter.');
                valid = false;
            }

            if (!mulaiVal) {
                mulai.addClass('is-invalid');
                $('#mulai_sesi_edit_feedback').text('❗ Harus diisi.');
                valid = false;
            }

            if (!selesaiVal) {
                selesai.addClass('is-invalid');
                $('#selesai_sesi_edit_feedback').text('❗ Harus diisi.');
                valid = false;
            } else if (mulaiVal && selesaiVal <= mulaiVal) {
                mulai.addClass('is-invalid');
                selesai.addClass('is-invalid');
                $('#selesai_sesi_edit_feedback').text('❗ Jam selesai harus lebih besar dari jam mulai.');
                valid = false;
            }

            // Cek bentrok ke server jika lolos validasi awal
            if (valid) {
                $.get("{{ route('sesi.check') }}", {
                    Nama_Sesi: namaVal,
                    Mulai_Sesi: mulaiVal,
                    Selesai_Sesi: selesaiVal,
                    Id_Sesi: id
                }, function(res) {
                    if (res.exists_nama) {
                        nama.addClass('is-invalid');
                        $('#nama_sesi_edit_feedback').text('❗ Nama sesi sudah dipakai.');
                    } else {
                        nama.addClass('is-valid');
                    }

                    if (res.exists_jam) {
                        mulai.addClass('is-invalid');
                        selesai.addClass('is-invalid');

                        const info = res.bentrok_with ? ` dengan sesi "${res.bentrok_with}"` : '';
                        $('#mulai_sesi_edit_feedback').text('❗ Jam sesi bertabrakan' + info + '.');
                    } else {
                        if (!res.exists_nama) {
                            mulai.addClass('is-valid');
                            selesai.addClass('is-valid');
                        }
                    }

                    if (!res.exists_nama && !res.exists_jam) {
                        $('#btn_update_sesi').prop('disabled', false);
                    }
                });
            }
        }, 300);

        $(document).on('click', '.btn-edit-sesi', function() {
            const btn = $(this);

            $('#nama_sesi_edit').val(btn.data('nama'));
            $('#mulai_sesi_edit').val(btn.data('mulai'));
            $('#selesai_sesi_edit').val(btn.data('selesai'));
            $('#id_sesi_edit').val(btn.data('id'));

            const formAction = `/admin/sesi/${btn.data('id')}`;
            $('#formEditSesi').attr('action', formAction);

            // Reset style dan feedback
            $('#nama_sesi_edit, #mulai_sesi_edit, #selesai_sesi_edit').removeClass('is-valid is-invalid');
            $('#nama_sesi_edit_feedback').text('');
            $('#mulai_sesi_edit_feedback').text('');
            $('#selesai_sesi_edit_feedback').text('');

            // Jalankan validasi langsung (kalau mau)
            validateEditSesi();
        });

        $('#nama_sesi_edit, #mulai_sesi_edit, #selesai_sesi_edit').on('input change', validateEditSesi);
    </script>

    <!-- script Modal Store Ruangan -->
    <script>
        let validNamaRuangan = false;
        let validJenisRuangan = false;
        let validLantai = false;
        let validStatus = false;

        function cekFormRuanganLengkap() {
            const semuaValid = validNamaRuangan && validJenisRuangan && validLantai && validStatus;
            $('#btn_simpan_ruangan').prop('disabled', !semuaValid);
        }

        function debounce(callback, delay) {
            let timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(callback.bind(this), delay);
            };
        }

        $('#nama_ruangan').on('input', debounce(function() {
            const input = $(this);
            const val = input.val().trim();
            const feedback = $('#nama_ruangan_feedback');

            input.removeClass('is-invalid is-valid');
            feedback.text('');
            validNamaRuangan = false;
            cekFormRuanganLengkap();

            if (val.length < 2) {
                input.addClass('is-invalid');
                feedback.text('❗ Minimal 2 karakter.');
                return;
            }

            const lantaiVal = $('#lantai').val(); // kirim lantai juga
            $.get("{{ route('ruangan.checkStore') }}", {
                Nama_Ruangan: val,
                Lantai: lantaiVal
            }, function(res) {
                if (res.exists) {
                    input.addClass('is-invalid');
                    feedback.text('❗ Nama ruangan sudah dipakai di lantai ini.');
                    validNamaRuangan = false;
                } else {
                    input.addClass('is-valid');
                    validNamaRuangan = true;
                }
                cekFormRuanganLengkap();
            });
        }, 300));

        $('#jenis_ruangan').on('input', function() {
            const input = $(this);
            const val = input.val().trim();

            input.removeClass('is-valid is-invalid');
            $('#jenis_ruangan_feedback').text('');
            validJenisRuangan = false;

            if (val.length < 2) {
                input.addClass('is-invalid');
                $('#jenis_ruangan_feedback').text('❗ Wajib diisi.');
            } else {
                input.addClass('is-valid');
                validJenisRuangan = true;
            }
            cekFormRuanganLengkap();
        });

        $('#lantai').on('input', function() {
            const input = $(this);
            const val = input.val().trim();
            input.removeClass('is-valid is-invalid');
            $('#lantai_feedback').text('');
            validLantai = false;

            if (!val || isNaN(val)) {
                input.addClass('is-invalid');
                $('#lantai_feedback').text('❗ Masukkan angka.');
            } else {
                input.addClass('is-valid');
                validLantai = true;
            }
            cekFormRuanganLengkap();

            // juga trigger ulang cek nama_ruangan agar validasi ulang pakai lantai baru
            $('#nama_ruangan').trigger('input');
        });

        $('#status').on('change', function() {
            const val = $(this).val();
            validStatus = val !== '';
            cekFormRuanganLengkap();
        });

        // Reset modal saat ditutup
        $('#modalTambahRuangan').on('hidden.bs.modal', function() {
            $('#btn_simpan_ruangan').prop('disabled', true);

            $('#nama_ruangan, #jenis_ruangan, #lantai').val('').removeClass('is-valid is-invalid');
            $('#nama_ruangan_feedback, #jenis_ruangan_feedback, #lantai_feedback').text('');
            $('#status').val('aktif');

            validNamaRuangan = false;
            validJenisRuangan = false;
            validLantai = false;
            validStatus = false;
        });
    </script>

    <!-- script Modal Edit Ruangan -->
    <script>
        function debounce(fn, delay) {
            let timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, arguments), delay);
            };
        }

        const validateEditRuangan = debounce(function() {
            const nama = $('#nama_ruangan_edit');
            const jenis = $('#jenis_ruangan_edit');
            const lantai = $('#lantai_ruangan_edit');
            const status = $('#Status_ruangan_edit');
            const id = $('#id_Ruangan_edit').val();

            const namaVal = nama.val().trim();
            const jenisVal = jenis.val().trim();
            const lantaiVal = lantai.val();
            const statusVal = status.val();

            // Reset state awal
            $('#btn_update_ruangan').prop('disabled', true);
            nama.removeClass('is-invalid is-valid');
            jenis.removeClass('is-invalid is-valid');
            lantai.removeClass('is-invalid is-valid');
            status.removeClass('is-invalid is-valid');
            $('#nama_ruangan_edit_feedback').text('');
            $('#jenis_ruangan_edit_feedback').text('');
            $('#lantai_ruangan_edit_feedback').text('');
            $('#status_ruangan_edit_feedback').text('');

            // Validasi client-side awal
            let valid = true;

            if (namaVal.length < 2) {
                nama.addClass('is-invalid');
                $('#nama_ruangan_edit_feedback').text('❗ Minimal 2 karakter.');
                valid = false;
            }

            if (jenisVal.length < 2) {
                jenis.addClass('is-invalid');
                $('#jenis_ruangan_edit_feedback').text('❗ Minimal 2 karakter.');
                valid = false;
            }

            if (!lantaiVal || lantaiVal <= 0) {
                lantai.addClass('is-invalid');
                $('#lantai_ruangan_edit_feedback').text('❗ Harus lebih dari 0.');
                valid = false;
            }

            if (!statusVal) {
                status.addClass('is-invalid');
                $('#status_ruangan_edit_feedback').text('❗ Pilih status.');
                valid = false;
            }

            // Cek ke server jika valid
            if (valid) {
                $.get("{{ route('ruangan.checkEdit') }}", {
                    Nama_Ruangan: namaVal,
                    Id_Ruangan: id
                }, function(res) {
                    if (res.exists_nama) {
                        nama.addClass('is-invalid');
                        $('#nama_ruangan_edit_feedback').text('❗ Nama ruangan sudah digunakan.');
                    } else {
                        nama.addClass('is-valid');
                    }

                    if (!res.exists_nama) {
                        jenis.addClass('is-valid');
                        lantai.addClass('is-valid');
                        status.addClass('is-valid');
                        $('#btn_update_ruangan').prop('disabled', false);
                    }
                });
            }
        }, 300);

        // Tombol edit ditekan
        $(document).on('click', '.btn-edit-ruangan', function() {
            const btn = $(this);

            $('#id_Ruangan_edit').val(btn.data('id'));
            $('#nama_ruangan_edit').val(btn.data('nama'));
            $('#jenis_ruangan_edit').val(btn.data('jenis'));
            $('#lantai_ruangan_edit').val(btn.data('lantai'));
            $('#Status_ruangan_edit').val(btn.data('status'));
            $('#Keterangan_ruangan_edit').val(btn.data('keterangan'));

            $('#formEditRuangan').attr('action', '/admin/ruangan/' + btn.data('id'));

            // Reset style dan feedback
            $('#nama_ruangan_edit, #jenis_ruangan_edit, #lantai_ruangan_edit, #Status_ruangan_edit').removeClass(
                'is-valid is-invalid');
            $('#nama_ruangan_edit_feedback').text('');
            $('#jenis_ruangan_edit_feedback').text('');
            $('#lantai_ruangan_edit_feedback').text('');
            $('#status_ruangan_edit_feedback').text('');

            // Jalankan validasi awal (optional)
            validateEditRuangan();
        });

        // Trigger validasi saat input berubah
        $('#nama_ruangan_edit, #jenis_ruangan_edit, #lantai_ruangan_edit, #Status_ruangan_edit').on('input change',
            validateEditRuangan);
    </script>



    <script>
        function syncIdRuangan(selectEl) {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            const idRuangan = selectedOption.getAttribute('data-id-ruangan');
            const hiddenInput = selectEl.closest('.row').querySelector('.input-id-ruangan');
            if (hiddenInput && idRuangan) {
                hiddenInput.value = idRuangan;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ruang-select').forEach(selectEl => {
                syncIdRuangan(selectEl); // Sync saat halaman dimuat
            });
        });
    </script>






</body>

</html>
