<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">➕ Tambah Pasien</h5>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pasien.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Nik" class="form-label">NIK</label>
                                <input type="text" placeholder="Masukan Nik" name="Nik" id="Nik" class="form-control" maxlength="16" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="Nama_Pasien" class="form-label">Nama Pasien</label>
                                <input type="text" placeholder="Masukan Nama" name="Nama_Pasien" id="Nama_Pasien" class="form-control" required>
                            </div>
                        </div>
 
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Tanggal_Lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="Tanggal_Lahir" id="Tanggal_Lahir" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="Jk" class="form-label">Jenis Kelamin</label>
                                <select name="Jk" id="Jk" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
  
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="No_Tlp" class="form-label">No Telepon</label>
                                <input type="text" placeholder="Masukan No Telepon" name="No_Tlp" id="No_Tlp" class="form-control" required>
                            </div>

                        </div>

                        <div class="col-md-6 mb-3">
                                <label for="Alamat" class="form-label">Alamat</label>
                                <textarea name="Alamat" placeholder="Masukan Alamat" id="Alamat" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">← Kembali</a>
                            <button type="submit" class="btn btn-primary">💾 Simpan</button>
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
