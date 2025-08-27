<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
 
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">✏️ Edit Data Pasien</h5>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pasien.update', $pasien->Id_Pasien) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6 mb-3">
                            <label for="Nik" class="form-label">NIK</label>
                            <input type="text" name="Nik" id="Nik" class="form-control" maxlength="16"
                                value="{{ old('Nik', $pasien->Nik) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Nama_Pasien" class="form-label">Nama Pasien</label>
                                <input type="text" name="Nama_Pasien" id="Nama_Pasien" class="form-control"
                                    value="{{ old('Nama_Pasien', $pasien->Nama_Pasien) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="Tanggal_Lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="Tanggal_Lahir" id="Tanggal_Lahir" class="form-control"
                                    value="{{ old('Tanggal_Lahir', $pasien->Tanggal_Lahir) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="Jk" class="form-label">Jenis Kelamin</label>
                                <select name="Jk" id="Jk" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('Jk', $pasien->Jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('Jk', $pasien->Jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="No_Tlp" class="form-label">No Telepon</label>
                                <input type="text" name="No_Tlp" id="No_Tlp" class="form-control"
                                    value="{{ old('No_Tlp', $pasien->No_Tlp) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Alamat" class="form-label">Alamat</label>
                            <textarea name="Alamat" id="Alamat" class="form-control" rows="2" required>{{ old('Alamat', $pasien->Alamat) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Dibuat_Oleh" class="form-label">Dibuat Oleh</label>
                                <input type="text" name="Dibuat_Oleh" id="Dibuat_Oleh" class="form-control"
                                    value="{{ old('Dibuat_Oleh', $pasien->Dibuat_Oleh) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="Tanggal_Registrasi" class="form-label">Tanggal Registrasi</label>
                                <input type="date" name="Tanggal_Registrasi" id="Tanggal_Registrasi" class="form-control"
                                    value="{{ old('Tanggal_Registrasi', \Carbon\Carbon::parse($pasien->Tanggal_Registrasi)->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">← Kembali</a>
                            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
