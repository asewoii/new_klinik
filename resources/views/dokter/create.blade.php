@extends('layouts.nav_admin')
@section('title', 'Tambah Dokter')
@section('content')

<div class="container">
    <h3 class="mb-4">Tambah Dokter</h3>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('dokter.store') }}">
        @csrf

        <div class="mb-3">
            <label>Nama Dokter</label>
            <input type="text" name="Nama_Dokter" class="form-control" required minLength="10" maxlength="255" placeholder="Masukan Nama Dokter">
        </div>

        <div class="mb-3">
            <label for="selectSpesialis" class="form-label fw-semibold">Spesialis</label>
            <select name="Spesialis" id="selectSpesialis" class="form-select" required>
                <option value="">Pilih Spesialis</option>
                @foreach($layanan as $item)
                    <option value="{{ $item->Nama_Layanan }}">{{ $item->Nama_Layanan }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>No. Telepon</label>
            <input type="text" name="No_Telp" class="form-control" minLength="10" maxlength="15" placeholder="08...">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="Email" class="form-control" minLength="10" maxlength="255">
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="Alamat" class="form-control" rows="3" minLength="1" maxlength="255"></textarea>
        </div>

        <h5 class="mt-4">Jadwal Dokter</h5>
        @php $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']; @endphp
        @foreach($hari as $h)
        <div class="mb-3">
            <label>{{ $h }}</label>
            <div id="jadwal-{{ $h }}">
                <div class="row mb-2 align-items-center">
                    <div class="col-md-3">
                        <input type="text" name="jadwal[{{ $h }}][start][]" class="form-control datetimepicker start" placeholder="Jam Mulai">
                    </div> 
                    <div class="col-md-3">
                        <input type="text" name="jadwal[{{ $h }}][end][]" class="form-control datetimepicker end" placeholder="Jam Selesai">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="jadwal[{{ $h }}][kuota][]" class="form-control" placeholder="Kuota" min="1">
                    </div>
                    <div class="col-md-3">
                        <select name="jadwal[{{ $h }}][ruang][]" class="form-select ruang-select" onchange="syncIdRuangan(this)">
                            <option value="">Pilih Ruangan</option>
                            @foreach($ruangans as $ruang)
                                <option 
                                    value="{{ $ruang->Nama_Ruangan }}"
                                    data-id="{{ $ruang->Id_Ruangan }}"
                                    @if(strtolower($ruang->Status) !== 'aktif') disabled @endif
                                >
                                    {{ $ruang->Nama_Ruangan }} ({{ $ruang->Status }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jadwal[{{ $h }}][id_ruangan][]" class="input-id-ruangan">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-success" onclick="addJadwal('{{ $h }}')">+</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>

{{-- FLATPICKR --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const ruangans = @json($ruangans);

    function addJadwal(hari) {
        const container = document.getElementById('jadwal-' + hari);
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2', 'align-items-center');

        let ruanganOptions = '<option value=\"\">Pilih Ruangan</option>';
        ruangans.forEach(r => {
            ruanganOptions += `<option value="${r.Nama_Ruangan}" data-id="${r.Id_Ruangan}">${r.Nama_Ruangan}</option>`;
        });

        newRow.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="jadwal[${hari}][start][]" class="form-control datetimepicker start" placeholder="Jam Mulai">
            </div>
            <div class="col-md-3">
                <input type="text" name="jadwal[${hari}][end][]" class="form-control datetimepicker end" placeholder="Jam Selesai">
            </div>
            <div class="col-md-2">
                <input type="number" name="jadwal[${hari}][kuota][]" class="form-control" placeholder="Kuota" min="1">
            </div>
            <div class="col-md-3">
                <select name="jadwal[${hari}][ruang][]" class="form-select ruang-select">
                    ${ruanganOptions}
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.row').remove()">-</button>
            </div>
        `;


        container.appendChild(newRow);

        flatpickr(newRow.querySelectorAll('.datetimepicker'), {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 5,
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.datetimepicker', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 5,
        });

        // ⬇️ Tambahkan ini agar semua jadwal awal langsung sync ID ruangan
        syncSemuaIdRuangan();
    });
</script>


<script>
    function fetchAvailableRuangan(startInput, endInput, selectEl, hari) {
    const start = startInput.value;
    const end = endInput.value;

    if (start && end) {
        fetch(`{{ route('ruangan.tersedia') }}?hari=${hari}&start=${start}&end=${end}`)
            .then(res => res.json())
            .then(data => {
                selectEl.innerHTML = '<option value="">Pilih Ruangan</option>';

                // Tambahkan ruangan tersedia
                data.tersedia.forEach(nama => {
                    const opt = document.createElement('option');
                    opt.value = nama;
                    opt.text = nama;
                    selectEl.appendChild(opt);
                });

                // Tambahkan ruangan bentrok (disabled)
                data.bentrok.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.ruang;
                    opt.text = `${item.ruang} (BENTROK: ${item.dokter} ${item.start} - ${item.end})`;
                    opt.disabled = true;
                    opt.classList.add('text-danger');
                    selectEl.appendChild(opt);
                });

                // Tampilkan notifikasi bentrok
                let info = selectEl.closest('.row').querySelector('.ruang-info');
                if (!info) {
                    info = document.createElement('div');
                    info.classList.add('ruang-info');
                    info.classList.add('mt-1');
                    selectEl.closest('.row').appendChild(info);
                }

                info.innerHTML = ''; // kosongkan
            })
            .catch(() => {
                selectEl.innerHTML = '<option value="">Gagal memuat</option>';
            });
        }
    }


    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('datetimepicker')) {
            const row = e.target.closest('.row');
            const startInput = row.querySelector('.start');
            const endInput = row.querySelector('.end');
            const ruangSelect = row.querySelector('.ruang-select');

            const hari = row.closest('[id^="jadwal-"]').id.replace('jadwal-', '');

            if (startInput.value && endInput.value) {
                fetchAvailableRuangan(startInput, endInput, ruangSelect, hari);
            }
        }
    });

    function loadRuangan(hari, start, end, targetSelect, infoContainer) {
    fetch(`/get-available-ruangan?hari=${hari}&start=${start}&end=${end}`)
        .then(response => response.json())
        .then(data => {
            const select = document.querySelector(targetSelect);
            const info = document.querySelector(infoContainer);

            // Reset options
            select.innerHTML = '<option value="">Pilih Ruangan</option>';

            // Tambahkan opsi ruangan yang tersedia
            data.tersedia.forEach(ruang => {
                select.innerHTML += `<option value="${ruang}">${ruang}</option>`;
            });

            // Tampilkan info bentrok
            info.innerHTML = '';
            if (data.bentrok.length > 0) {
                data.bentrok.forEach(item => {
                    info.innerHTML += `<div class="alert alert-danger mb-1">
                        Ruangan <strong>${item.ruang}</strong> sudah dipakai oleh 
                        <strong>${item.dokter}</strong> dari <strong>${item.start}</strong> sampai <strong>${item.end}</strong>
                    </div>`;
                });
            }
        });
    }

</script>

@endsection