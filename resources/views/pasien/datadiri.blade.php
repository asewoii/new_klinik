<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Pasien</title>

    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #00A9B7;
            --secondary-color: #007C85;
            --accent-color: #F4B400;
            --danger-color: #E53935;
            --success-color: #43A047;
            --warning-color: #FB8C00;
            --info-color: #29B6F6;
            --background-color: #F9FAFB;
            --surface-color: #FFFFFF;
            --border-color: #E0E0E0;
            --text-color: #333333;
            --text-muted-color: #6B7280;
            --font-family: 'Poppins', 'Helvetica Neue', sans-serif;
            --base-font-size: 16px;
            --heading-font-weight: 600;
            --text-font-weight: 400;
            --border-radius: 0.75rem;
            --button-radius: 0.5rem;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --spacing-xs: 4px;
            --spacing-sm: 8px;
            --spacing-md: 16px;
            --spacing-lg: 24px;
            --spacing-xl: 32px;
            --transition-fast: 0.2s;
            --transition-medium: 0.4s;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: var(--font-family);
            font-size: var(--base-font-size);
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4 {
            font-weight: var(--heading-font-weight);
            color: var(--primary-color);
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: var(--heading-font-weight);
            color: var(--primary-color);
        }

        .patient-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: var(--spacing-md);
            margin-top: var(--spacing-md);
        }

        .card-header-custom {
            background-color: var(--primary-color);
            color: #fff;
            padding: var(--spacing-sm);
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
            font-weight: var(--heading-font-weight);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        button, .btn {
            border-radius: var(--button-radius);
            transition: background-color var(--transition-fast), transform var(--transition-fast);
        }

        button:hover, .btn:hover {
            transform: scale(1.02);
        }

        .btn-primary { background-color: var(--primary-color); border: none; }
        .btn-primary:hover { background-color: var(--secondary-color); }
        .btn-secondary { background-color: var(--secondary-color); border: none; }
        .btn-secondary:hover { background-color: var(--primary-color); }
        .btn-warning { background-color: var(--warning-color); color: #fff; }
        .btn-warning:hover { background-color: #e57c00; }
        .btn-danger { background-color: var(--danger-color); border: none; }
        .btn-danger:hover { background-color: #c62828; }
        .btn-success { background-color: var(--success-color); border: none; }
        .btn-success:hover { background-color: #2e7d32; }

        .table th { background-color: var(--primary-color); color: #fff; }
        .table-hover tbody tr:hover { background-color: rgba(0, 169, 183, 0.1); }

        .modal-header.bg-primary { background-color: var(--primary-color) !important; }
        .modal-header.bg-warning { background-color: var(--warning-color) !important; }

        .fade-in {
            animation: fadeIn var(--transition-medium) ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="header-title m-0"><i class="bi bi-file-medical text-primary"></i> Data Pasien</h1>
            <div class="d-flex align-items-center gap-3 mt-2 mt-sm-0 flex-wrap">
                <span id="liveTime" class="badge bg-light text-dark fs-6 p-2"></span>

                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetPinModal">
                    <i class="bi bi-key"></i> <span class="d-none d-sm-inline">Reset PIN</span>
                </button>

                <form method="POST" action="{{ route('logout_pasien') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Card Data Pasien -->

    <div class="patient-card card shadow-sm p-3 mt-4">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="bi bi-person-circle me-2"></i> Data Diri Pasien
        </div>

        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">ID Pasien:</div>
                <div class="col-sm-8">{{ $pasien->Id_Pasien }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">Nama:</div>
                <div class="col-sm-8">{{ $pasien->Nama_Pasien }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">NIK:</div>
                <div class="col-sm-8">{{ $pasien->Nik }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">Tanggal Lahir:</div>
                <div class="col-sm-8">{{ \Carbon\Carbon::parse($pasien->Tanggal_Lahir)->format('d-m-Y') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">Umur:</div>
                <div class="col-sm-8">{{ $pasien->Umur }} Tahun</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">Jenis Kelamin:</div>
                <div class="col-sm-8">{{ $pasien->Jk }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">Alamat:</div>
                <div class="col-sm-8">{{ $pasien->Alamat }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-2 fw-bold">No. Telp:</div>
                <div class="col-sm-8">{{ $pasien->No_Tlp }}</div>
            </div>

            <div class="text-center mt-4">
                <div id="qrcode">{!! QrCode::size(200)->generate($pasien->Qr_Url) !!}</div>
                <small class="d-block mt-2 text-muted">Simpan QR Code ini untuk akses cepat ke data Anda</small>
                <a href="{{ route('pasien.download_qr', $pasien->Id_Pasien) }}" class="btn btn-outline-primary mt-2">
                    <i class="bi bi-download"></i> Download QR Code
                </a>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('redirect.formkunjungan', ['id' => $pasien->Id_Pasien]) }}" class="btn btn-secondary me-2">
                    Form Register Kunjungan
                </a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#riwayatKunjunganModal">Riwayat Kunjungan</button>
            </div>
        </div>

        <!-- Modal Riwayat Kunjungan -->
<div class="modal fade" id="riwayatKunjunganModal" tabindex="-1" aria-labelledby="riwayatKunjunganLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="riwayatKunjunganLabel">📝 Riwayat Kunjungan Pasien</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($riwayatKunjungan->isEmpty())
                    <div class="alert alert-info text-center">
                        Belum ada riwayat kunjungan.
                    </div>
                @else
                    <!-- Filter Section -->
                    <div class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="filterTanggalMulai">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="filterTanggalSelesai">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Dokter / Ruangan</label>
                                <input type="text" class="form-control" id="filterKeyword" placeholder="Contoh: dr. Wahyu, RU02">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Poli</label>
                                <input type="text" class="form-control" id="filterPoli" placeholder="Contoh: MATA, GIGI">
                            </div>
                            <div class="col-12 text-end mt-3">
                                <button class="btn btn-primary me-2" id="btnFilterRiwayat">
                                    <i class="bi bi-funnel-fill me-1"></i>Filter
                                </button>
                                <button class="btn btn-outline-secondary" id="btnResetRiwayat">
                                    <i class="bi bi-x-circle-fill me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Riwayat -->
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @foreach ($riwayatKunjungan as $kunjungan)
                            <div class="col riwayat-item"
                                data-tanggal="{{ \Carbon\Carbon::parse($kunjungan->Tanggal)->format('d-m-Y') }}"
                                data-dokter="{{ strtolower($kunjungan->dokter->Nama_Dokter ?? '') }}"
                                data-ruangan="{{ strtolower($kunjungan->Id_Ruangan ?? '') }}"
                                data-poli="{{ strtolower($kunjungan->indikasi->deskripsi ?? '') }}"
                                data-keluhan="{{ strtolower($kunjungan->Keluhan ?? '') }}"
                                data-indikasi="{{ strtolower($kunjungan->indikasi->kode ?? $kunjungan->Kode_Indikasi ?? '') }}">
                                
                                <div class="card border border-light shadow-sm rounded-4 h-100 hover-shadow transition">
                                    <div class="card-body p-4"
                                        data-tanggal="{{ \Carbon\Carbon::parse($kunjungan->Tanggal)->format('Y-m-d') }}"
                                        data-dokter="{{ strtolower($kunjungan->dokter->Nama_Dokter ?? '') }}"
                                        data-ruangan="{{ strtolower($kunjungan->Id_Ruangan ?? '') }}">
                                        
                                        <h6 class="card-title text-primary mb-2 fw-bold">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ \Carbon\Carbon::parse($kunjungan->Tanggal)->format('d M Y') }}
                                        </h6>
                                        
                                        <ul class="list-unstyled small mb-0">
                                            <li><strong>Poli:</strong> {{ $kunjungan->layanan->Nama_Layanan ?? '-' }}</li>
                                            <li><strong>Dokter:</strong> {{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</li>
                                            <li><strong>Ruangan:</strong> {{ $kunjungan->Id_Ruangan }}</li>
                                            <li><strong>Jadwal:</strong> {{ $kunjungan->Jadwal }}</li>
                                            <li><strong>Keluhan:</strong> {{ $kunjungan->Keluhan }}</li>
                                            <li><strong>No Urut:</strong> {{ $kunjungan->Nomor_Urut }}</li>
                                            <li class="mt-2">
                                                <strong>Status:</strong>
                                                @php
                                                    $status = $kunjungan->Status;
                                                    $badge = match($status) {
                                                        'Diperiksa' => 'success',
                                                        'Belum Hadir' => 'warning text-dark',
                                                        'Selesai' => 'secondary',
                                                        default => 'light text-dark'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $badge }}">{{ $status }}</span>

                                                @if ($status === 'Selesai')
                                                    <button class="btn btn-sm btn-outline-info mt-2 lihat-detail-periksa"
                                                        data-id="{{ $kunjungan->Id_Kunjungan }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#riwayatPemeriksaanModal">
                                                        <i class="bi bi-eye"></i> Lihat Detail
                                                    </button>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


        <!-- Modal Riwayat Pemeriksaan -->
        <div class="modal fade" id="riwayatPemeriksaanModal" tabindex="-1" aria-labelledby="riwayatPemeriksaanLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="riwayatPemeriksaanLabel">🧾 Detail Pemeriksaan</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="detailPemeriksaanContent">
                        <div class="text-center text-muted">Memuat data...</div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Reset PIN -->
        <div class="modal fade" id="resetPinModal" tabindex="-1" aria-labelledby="resetPinModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="btn btn-warning modal-title" id="resetPinModalLabel" title="Ganti PIN akun">🔒 Reset PIN Pasien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div id="resetPinStep1">
                            <p>Klik tombol di bawah untuk mengirim OTP ke nomor WhatsApp pasien.</p>
                            <button class="btn btn-primary" id="kirimOtpResetBtn">Kirim OTP</button>
                        </div>

                        <div id="resetPinStep2" style="display: none;">
                            <label>Kode OTP:</label>
                            <input type="text" class="form-control mb-2" id="kodeOtpReset" placeholder="Masukkan OTP">
                            <button class="btn btn-success" id="verifikasiOtpResetBtn">Verifikasi OTP</button>
                        </div>

                        <div id="resetPinStep3" style="display: none;">
                            <label>PIN Baru:</label>
                            <input type="password" class="form-control mb-2" id="pinBaru" placeholder="PIN Baru">
                            <label>Konfirmasi PIN:</label>
                            <input type="password" class="form-control mb-2" id="konfirmasiPin" placeholder="Konfirmasi PIN">
                            <button class="btn btn-success" id="simpanPinBaruBtn">Simpan PIN</button>
                        </div>

                        <div id="pesanResetPin" class="text-danger mt-2"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<!---->
    <script>
        // CSRF token setup untuk semua request
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Live Clock
        function updateLiveTime() {
            const now = moment();
            $('#liveTime').text(now.format('dddd, D MMMM YYYY | HH:mm:ss'));
        }
        setInterval(updateLiveTime, 1000);
        updateLiveTime();

        // Download QR as PNG
        $('#downloadQrBtn').click(function () {
            const svg = document.querySelector('#qrcode svg');
            if (!svg) return alert("QR Code belum tersedia.");

            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");
            const svgData = new XMLSerializer().serializeToString(svg);
            const bbox = svg.getBoundingClientRect();
            canvas.width = bbox.width || 200;
            canvas.height = bbox.height || 200;

            const img = new Image();
            const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
            const url = URL.createObjectURL(svgBlob);

            img.onload = () => {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                URL.revokeObjectURL(url);
                const pngUrl = canvas.toDataURL("image/png");
                const link = document.createElement("a");
                link.href = pngUrl;
                link.download = "qrcode.png";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };
            img.src = url;
        });

        // Kirim OTP
        $('#kirimOtpResetBtn').click(function () {
            const btn = $(this);
            btn.prop('disabled', true);

            $.post('{{ route("reset.pin.requestOtp") }}', { no_tlp: '{{ $pasien->No_Tlp }}' }, function (res) {
                if (res.status === 'otp_sent' || res.status === 'waiting') {
                    $('#pesanResetPin')
                        .text(res.message || 'Kode OTP dikirim ke WhatsApp pasien.')
                        .removeClass('text-danger').addClass('text-success');
                    $('#resetPinStep1').hide();
                    $('#resetPinStep2').show();
                } else {
                    $('#pesanResetPin')
                        .text(res.message || 'Gagal mengirim OTP.')
                        .removeClass('text-success').addClass('text-danger');
                }
            }).always(() => {
                btn.prop('disabled', false);
            });
        });

        // Verifikasi OTP
        $('#verifikasiOtpResetBtn').click(function () {
            const kodeOtp = $('#kodeOtpReset').val().trim();

            if (!kodeOtp) {
                $('#pesanResetPin').text('Silakan isi kode OTP.').removeClass('text-success').addClass('text-danger');
                return;
            }

            $.post('{{ route("reset.pin.verifikasiOtp") }}', {
                no_tlp: '{{ $pasien->No_Tlp }}',
                kode_otp: kodeOtp
            }, function (res) {
                if (res.status === 'verified') {
                    $('#pesanResetPin')
                        .text('OTP terverifikasi, silakan buat PIN baru.')
                        .removeClass('text-danger').addClass('text-success');
                    $('#resetPinStep2').hide();
                    $('#resetPinStep3').show();
                } else {
                    $('#pesanResetPin')
                        .text(res.message || 'OTP salah atau kadaluarsa.')
                        .removeClass('text-success').addClass('text-danger');
                }
            });
        });

        // Simpan PIN Baru
        $('#simpanPinBaruBtn').click(function () {
            const pinBaru = $('#pinBaru').val().trim();
            const konfirmasiPin = $('#konfirmasiPin').val().trim();

            if (!pinBaru || !konfirmasiPin) {
                $('#pesanResetPin').text('Silakan isi semua kolom PIN baru.')
                    .removeClass('text-success').addClass('text-danger');
                return;
            }

            if (pinBaru.length < 4 || pinBaru.length > 6) {
                $('#pesanResetPin').text('PIN harus terdiri dari 4-6 digit.')
                    .removeClass('text-success').addClass('text-danger');
                return;
            }

            if (pinBaru !== konfirmasiPin) {
                $('#pesanResetPin').text('PIN dan konfirmasi tidak sama.')
                    .removeClass('text-success').addClass('text-danger');
                return;
            }

            $.post('{{ route("reset.pin.simpan") }}', {
                no_tlp: '{{ $pasien->No_Tlp }}',
                pin: pinBaru,
                pin_confirmation: konfirmasiPin
            }, function (res) {
                if (res.status === 'success') {
                    alert('PIN berhasil direset.');

                    // Reset form modal
                    $('#resetPinModal').modal('hide');
                    $('#pinBaru, #konfirmasiPin, #kodeOtpReset').val('');
                    $('#resetPinStep1').show();
                    $('#resetPinStep2, #resetPinStep3').hide();
                    $('#pesanResetPin').text('');
                } else {
                    $('#pesanResetPin')
                        .text(res.message || 'Gagal menyimpan PIN baru.')
                        .removeClass('text-success').addClass('text-danger');
                }
            });
        });

        // Reset form otomatis saat modal ditutup
        $('#resetPinModal').on('hidden.bs.modal', function () {
            $('#pinBaru, #konfirmasiPin, #kodeOtpReset').val('');
            $('#resetPinStep1').show();
            $('#resetPinStep2, #resetPinStep3').hide();
            $('#pesanResetPin').text('').removeClass('text-danger text-success');
        });

//PERIKSAAA
    $('.lihat-detail-periksa').click(function () {
    const idKunjungan = $(this).data('id');
    const container = $('#detailPemeriksaanContent');

    container.html('<div class="text-center text-muted">Memuat data...</div>');

    $.get(`/pasien/periksa/${idKunjungan}`, function (res) {
        if (res.status === 'success') {
            const p = res.data;
            const k = res.data;

            container.html(`
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Poli:</strong> ${p.layanan}</li>
                    <li class="list-group-item"><strong>Dokter:</strong> ${p.dokter}</li>
                    <li class="list-group-item"><strong>Ruangan:</strong> ${k.ruangan}</li>
                    <li class="list-group-item"><strong>Jam Diperiksa:</strong> ${p.jam_diperiksa}</li>
                    <li class="list-group-item"><strong>Keluhan:</strong> ${k.keluhan}</li>
                    <li class="list-group-item"><strong>Tindakan:</strong> ${p.tindakan}</li>
                    <li class="list-group-item"><strong>Diagnosa:</strong> ${p.diagnosa}</li>
                    <li class="list-group-item"><strong>Resep:</strong> ${p.resep}</li>
                    <li class="list-group-item"><strong>Jam Diperiksa:</strong> ${p.jam_diperiksa}</li>
                    <li class="list-group-item"><strong>Catatan:</strong> ${p.catatan}</li>
                    <li class="list-group-item"><strong>Tanggal Kunjungan:</strong> ${p.tanggal_pemeriksaan}</li>
                </ul>
            `);
        } else {
            container.html(`<div class="alert alert-danger text-center">${res.message}</div>`);
        }
        console.log(res);

    }).fail(function () {
        container.html('<div class="alert alert-danger text-center">Gagal memuat data pemeriksaan.</div>');
    });
});

// === FILTER RIWAYAT KUNJUNGAN ===
    $('#btnFilterRiwayat').click(function () {
    const tanggalMulai = $('#filterTanggalMulai').val();
    const tanggalSelesai = $('#filterTanggalSelesai').val();
    const keyword = $('#filterKeyword').val().toLowerCase();
    const poli = $('#filterPoli').val().toLowerCase();

    $('.riwayat-item').each(function () {
        const tanggal = $(this).data('tanggal');
        const dokter = $(this).data('dokter') || '';
        const ruangan = $(this).data('ruangan') || '';
        const indikasi = $(this).data('indikasi') || '';

        let tampil = true;

        if (tanggalMulai && tanggal < tanggalMulai) tampil = false;
        if (tanggalSelesai && tanggal > tanggalSelesai) tampil = false;

        if (keyword && !(dokter.includes(keyword) || ruangan.includes(keyword))) {
            tampil = false;
        }

        if (poli && !indikasi.includes(poli)) {
            tampil = false;
        }

        $(this).closest('.col').toggle(tampil);
    });
});


    $('#btnResetRiwayat').click(function () {
        $('#filterTanggalMulai, #filterTanggalSelesai, #filterKeyword ,#filterPoli').val('');
        $('.riwayat-item').closest('.col').show();
});


    </script>
</body>



</html>