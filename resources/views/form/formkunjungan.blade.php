@extends('layouts.nav_admin')
@section('content')

<div class="container py-1">
    <div class="row g-4">
        {{-- FORM REGISTRASI --}}
        <div class="col-md-7">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <h4 class="mb-4"><i class="bi bi-clipboard-plus"></i> Form Registrasi Kunjungan</h4>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route(Session::has('pasien') ? 'pasien.kunjungan.store' : 'admin.kunjungan.store') }}">
                        @csrf
                        <input type="hidden" name="id_pasien" value="{{ $pasien->Id_Pasien }}">

                        {{-- Nama Pasien --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" value="{{ $pasien->Nama_Pasien }}" disabled>
                        </div>

                        {{-- Poli --}}
                        <div class="mb-3 position-relative">
                            <label for="layanan" class="form-label">Pilih Poli / Layanan</label>
                            <div class="position-relative">
                                <select name="id_layanan" id="layanan" class="form-select" required>
                                    <option value="">-- Pilih Poli --</option>
                                    @foreach ($layananList as $layanan)
                                        <option value="{{ $layanan->Id_Layanan }}">{{ $layanan->Nama_Layanan }}</option>
                                    @endforeach
                                </select>
                                <div class="spinner-border spinner-border-sm text-secondary position-absolute top-50 end-0 me-4 d-none" id="loading-layanan" style="transform: translateY(-50%);" role="status"></div>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-3 position-relative">
                            <label for="tanggal" class="form-label">Pilih Tanggal</label>
                            <div class="position-relative">
                                <input type="text" name="pilih_tanggal" id="tanggal" class="form-control" placeholder="Klik untuk memilih" required disabled>
                            </div>
                        </div>

                        {{-- Dokter --}}
                        <div class="mb-3 position-relative">
                            <label for="dokter" class="form-label">Pilih Dokter</label>
                            <div class="position-relative">
                                <select name="id_dokter" id="dokter" class="form-select" required disabled>
                                    <option value="">-- Pilih Dokter --</option>
                                </select>
                                <div class="spinner-border spinner-border-sm text-secondary position-absolute top-50 end-0 me-4 d-none" id="loading-dokter" style="transform: translateY(-50%);" role="status"></div>
                            </div>
                        </div>

                        {{-- Jam --}}
                        <div class="mb-3 position-relative">
                            <label for="jam" class="form-label">Pilih Jam</label>
                            <div class="position-relative">
                                <select name="jam" id="jam" class="form-select" required disabled>
                                    <option value="">-- Pilih Jam --</option>
                                </select>
                                <div class="spinner-border spinner-border-sm text-secondary position-absolute top-50 end-0 me-4 d-none" id="loading-jam" style="transform: translateY(-50%);" role="status"></div>
                            </div>
                        </div>

                        {{-- Keluhan --}}
                        <div class="mb-4">
                            <label for="keluhan" class="form-label">Keluhan Pasien</label>
                            <textarea name="keluhan" id="keluhan" rows="3" class="form-control" required placeholder="Ceritakan keluhan Anda..."></textarea>
                        </div>

                        <button type="submit" id="btn-submit" class="btn btn-primary w-100 rounded-pill">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" id="btn-loading"></span>
                            <i class="bi bi-check-circle-fill me-1"></i> Daftar Kunjungan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- JADWAL DOKTER --}}
        <div class="col-md-5 ms-auto">
            <div class="card shadow-sm rounded-4" style="max-height: 600px; overflow-y: auto;">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-week"></i> Jadwal Harian Dokter</h5>
                    @php $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp
                    @foreach ($hariList as $hari)
                        <h6 class="mt-4 text-primary fw-bold">{{ $hari }}</h6>
                        <table class="table table-bordered table-sm mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Ruangan</th>
                                    <th>Dokter</th>
                                    <th>Jadwal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jadwalHariIni = collect();
                                    foreach ($jadwal as $dokter) {
                                        if (!empty($dokter['jadwal'][$hari])) {
                                            foreach ($dokter['jadwal'][$hari] as $j) {
                                                $jadwalHariIni->push([
                                                    'ruang' => $j['ruang'] ?? '-',
                                                    'nama'  => $dokter['nama'],
                                                    'start' => $j['start'],
                                                    'end' => $j['end'],
                                                    'spesialis' =>$dokter['spesialis'],
                                                ]);
                                            }
                                        }
                                    }
                                @endphp
                                @forelse ($jadwalHariIni as $j)
                                    <tr>
                                        <td>{{ $j['ruang'] }}</td>
                                        <td>{{ $j['nama'] }} ({{$j['spesialis']}})</td>
                                        <td>{{ $j['start'] }} - {{ $j['end'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada jadwal</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STYLES & SCRIPT --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = {
            tanggal: document.getElementById('tanggal'),
            layanan: document.getElementById('layanan'),
            dokter: document.getElementById('dokter'),
            jam: document.getElementById('jam'),
            loadingLayanan: document.getElementById('loading-layanan'),
            loadingDokter: document.getElementById('loading-dokter'),
            loadingJam: document.getElementById('loading-jam'),
            btnSubmit: document.getElementById('btn-submit'),
            btnLoading: document.getElementById('btn-loading'),
        };

        function resetSelect(select, text = '-- Pilih --', disable = true) {
            select.innerHTML = `<option value="">${text}</option>`;
            select.disabled = disable;
        }

        function showSpinner(spinnerId, show = true) {
            spinnerId.classList.toggle('d-none', !show);
        }

        let fpTanggal;

        // Event layanan
        el.layanan.addEventListener('change', function () {
            const idLayanan = this.value;
            el.tanggal.value = '';
            el.tanggal.disabled = true;
            resetSelect(el.dokter);
            resetSelect(el.jam);

            if (!idLayanan) return;

            showSpinner(el.loadingLayanan, true);

            fetch(`/get-tanggal-by-layanan?id_layanan=${idLayanan}`)
                .then(res => res.json())
                .then(tersedia => {
                    if (fpTanggal) fpTanggal.destroy();
                    el.tanggal.disabled = false;
                    fpTanggal = flatpickr(el.tanggal, {
                        dateFormat: "Y-m-d",
                        minDate: "today",
                        maxDate: new Date().fp_incr(14),
                        enable: tersedia
                    });
                })
                .finally(() => showSpinner(el.loadingLayanan, false));
        });

        // Event tanggal
        el.tanggal.addEventListener('change', function () {
            const tanggal = this.value;
            const idLayanan = el.layanan.value;
            resetSelect(el.dokter);
            resetSelect(el.jam);
            if (!tanggal || !idLayanan) return;

            showSpinner(el.loadingDokter, true);

            fetch('/get-dokter-by-tanggal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tanggal, id_layanan: idLayanan })
            })
            .then(res => res.json())
            .then(data => {
                data.forEach(dok => {
                    const opt = document.createElement('option');
                    opt.value = dok.id_dokter;
                    opt.text = `${dok.nama} (${dok.spesialis})`;
                    el.dokter.appendChild(opt);
                });
                el.dokter.disabled = false;
            })
            .finally(() => showSpinner(el.loadingDokter, false));
        });

        // Event dokter
        el.dokter.addEventListener('change', function () {
            const idDokter = this.value;
            const tanggal = el.tanggal.value;
            const idLayanan = el.layanan.value;
            resetSelect(el.jam);
            if (!idDokter || !tanggal || !idLayanan) return;

            showSpinner(el.loadingJam, true);

            fetch('/get-jam-by-dokter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tanggal, id_dokter: idDokter, id_layanan: idLayanan })
            })
            .then(res => res.json())
            .then(jamList => {
                jamList.forEach(j => {
                    const opt = document.createElement('option');
                    opt.value = j.jam;
                    opt.text = `${j.jam} (Ruangan: ${j.ruangan_nama}, Sisa: ${j.sisa_kuota})`;
                    el.jam.appendChild(opt);
                });
                el.jam.disabled = false;
            })
            .finally(() => showSpinner(el.loadingJam, false));
        });

        // Submit
        document.querySelector('form').addEventListener('submit', function (e) {
            if (!el.layanan.value || !el.tanggal.value || !el.dokter.value || !el.jam.value) {
                e.preventDefault();
                alert('Mohon lengkapi semua pilihan terlebih dahulu.');
                return;
            }

            el.btnSubmit.disabled = true;
            el.btnLoading.classList.remove('d-none');
        });
    });
</script>
@endsection
