@extends('layouts.form')
@section('title', 'Form Registrasi Pasien')
@section('content')
<div class="registration-container fade-in">
    <div class="header-section">
        <i class="bi bi-person-plus-fill fs-1 mb-3"></i>
        <h2>Form Registrasi Pasien</h2>
        <p class="lead mb-0">Silakan lengkapi data diri Anda dengan benar</p>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-custom mb-4" role="alert">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Terdapat kesalahan:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('daftar.pasien.store') }}" method="POST" id="form-pasien">
            @csrf

            <div class="form-floating mb-3">
            <input type="text" class="form-control" id="Nik" name="Nik" placeholder="Masukan NIK Anda" maxlength="16" minlength="16" pattern="[0-9]{16}" value="{{ old('Nik') }}" required>
                <label for="Nik"><i class="bi bi-card-text me-2"></i>Nomor Induk Kependudukan (NIK)</label>
            </div>
            <div id="nik-status" class="text-danger status-message mb-3"></div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="Nama_Pasien" name="Nama_Pasien" placeholder="Masukan Nama Anda" value="{{ old('Nama_Pasien') }}" maxlength="255" required>
                <label for="Nama_Pasien"><i class="bi bi-person me-2"></i>Nama Lengkap</label>
            </div>

            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="Tanggal_Lahir" name="Tanggal_Lahir" value="{{ old('Tanggal_Lahir') }}" required>
                <label for="Tanggal_Lahir"><i class="bi bi-calendar3 me-2"></i>Tanggal Lahir</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" id="Jk" name="Jk" required>
                    <option value="">Pilih jenis kelamin</option>
                    <option value="L" {{ old('Jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('Jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <label for="Jk"><i class="bi bi-gender-ambiguous me-2"></i>Jenis Kelamin</label>
            </div>

            <div class="form-floating mb-3">
                <textarea class="form-control" id="Alamat" name="Alamat" placeholder="Masukan Alamat Anda" style="height: 100px" maxlength="255" required>{{ old('Alamat') }}</textarea>
                <label for="Alamat"><i class="bi bi-geo-alt me-2"></i>Alamat Lengkap</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="No_Tlp" name="No_Tlp" placeholder="Masukan No Telp" value="{{ old('No_Tlp') }}" required autocomplete="off" maxlength="15" minlength="10">
                <label for="No_Tlp"><i class="bi bi-telephone me-2"></i>Nomor Telepon</label>
            </div>
            <div id="notlp-status" class="text-danger status-message mb-4"></div>

            <div class="form-floating mb-4 position-relative">
                <input type="password" class="form-control" id="Pin" name="Pin" placeholder="Buat PIN Anda" minlength="4" maxlength="6" pattern="[0-9]{4,6}" required>
                <label for="Pin"><i class="bi bi-shield-lock me-2"></i>Buat PIN (4-6 digit angka)</label>

                <button type="button" id="togglePin" class="btn btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" tabindex="-1">
                    <i class="bi bi-eye-fill" id="icon-eye"></i>
                </button>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-register btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Daftar Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center mt-4">
    <small class="text-white-50">
        <i class="bi bi-shield-fill-check me-1"></i>Data Anda aman dan terlindungi
    </small>
</div>


@endsection
