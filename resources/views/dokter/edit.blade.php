@extends('layouts.nav_admin')
@section('title', 'Edit Dokter')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Dokter</h3>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('dokter.update', $dokter->Id_Dokter) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="Nama_Dokter" class="form-label">Nama Dokter</label>
            <input type="text" name="Nama_Dokter" class="form-control" value="{{ old('Nama_Dokter', $dokter->Nama_Dokter) }}" required>
        </div>

        <div class="mb-3">
            <label for="Spesialis" class="form-label">Spesialis</label>
            <input type="text" name="Spesialis" class="form-control" value="{{ old('Spesialis', $dokter->Spesialis) }}" required>
        </div>

        <div class="mb-3">
            <label for="No_Telp" class="form-label">No. Telepon</label>
            <input type="text" name="No_Telp" class="form-control" value="{{ old('No_Telp', $dokter->No_Telp) }}">
        </div>

        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" name="Email" class="form-control" value="{{ old('Email', $dokter->Email) }}">
        </div>

        <div class="mb-3">
            <label for="Alamat" class="form-label">Alamat</label>
            <textarea name="Alamat" class="form-control">{{ old('Alamat', $dokter->Alamat) }}</textarea>
        </div>

        <hr>
        <h5>Jadwal Dokter</h5>

        @php
            $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            $jadwal = json_decode($dokter->Jadwal_Dokter, true) ?? [];
        @endphp

        @foreach ($hariList as $hari)
            <div class="mb-3">
                <h6>{{ $hari }}</h6>
                <div id="jadwal-{{ $hari }}">
                    @if (isset($jadwal[$hari]))
                        @foreach ($jadwal[$hari] as $index => $item)
                            <div class="row mb-2 jadwal-item">
                                <div class="col-md-2">
                                    <input type="time" name="jadwal[{{ $hari }}][start][]" class="form-control" value="{{ $item['start'] }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="time" name="jadwal[{{ $hari }}][end][]" class="form-control" value="{{ $item['end'] }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="jadwal[{{ $hari }}][kuota][]" class="form-control" value="{{ $item['kuota'] }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="jadwal[{{ $hari }}][ruang][]" class="form-select ruang-select" onchange="syncIdRuangan(this)">
                                        @foreach ($ruangans as $ruangan)
                                            <option value="{{ $ruangan->Nama_Ruangan }}" 
                                                    data-id-ruangan="{{ $ruangan->Id_Ruangan }}"
                                                @if(old("jadwal.$hari.ruang.$i", $jam['ruang'] ?? '') == $ruangan->Nama_Ruangan) selected @endif>
                                                {{ $ruangan->Nama_Ruangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="jadwal[{{ $hari }}][id_ruangan][]" class="input-id-ruangan"
                                        value="{{ old("jadwal.$hari.id_ruangan.$i", $jam['id_ruangan'] ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-sm btn-secondary add-row" data-hari="{{ $hari }}">Tambah Jam</button>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<script>
    document.querySelectorAll('.add-row').forEach(btn => {
        btn.addEventListener('click', function () {
            const hari = this.dataset.hari;
            const container = document.getElementById(`jadwal-${hari}`);

            const row = document.createElement('div');
            row.classList.add('row', 'mb-2', 'jadwal-item');
            row.innerHTML = `
                <div class="col-md-2"><input type="time" name="jadwal[${hari}][start][]" class="form-control"></div>
                <div class="col-md-2"><input type="time" name="jadwal[${hari}][end][]" class="form-control"></div>
                <div class="col-md-2"><input type="number" name="jadwal[${hari}][kuota][]" class="form-control"></div>
                <div class="col-md-3">
                    <select name="jadwal[${hari}][ruang][]" class="form-control ruang-select">
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach ($ruangans as $ruang)
                            <option value="{{ $ruang->Nama_Ruangan }}" data-id="{{ $ruang->Id_Ruangan }}">{{ $ruang->Nama_Ruangan }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="jadwal[${hari}][id_ruangan][]" class="input-id-ruangan">
                </div>
                <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></div>
            `;
            container.appendChild(row);
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.jadwal-item').remove();
        }
    });





    // Update hidden input saat ruangan dipilih
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('ruang-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const idRuangan = selectedOption.getAttribute('data-id');
            const parent = e.target.closest('.row');
            const hiddenInput = parent.querySelector('.input-id-ruangan');
            if (hiddenInput) {
                hiddenInput.value = idRuangan;
            }
        }
    });
</script>
@endsection
