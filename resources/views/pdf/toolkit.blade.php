@extends('layouts.nav_admin')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">📤 Upload PDF / JPG</h2>

    <form id="upload-form" class="border border-2 border-secondary rounded p-4 text-center bg-light" enctype="multipart/form-data">
        <label for="files" class="form-label w-100">
            <div class="p-4 bg-white border border-dashed border-secondary rounded cursor-pointer" id="drop-zone">
                <p class="text-muted mb-0">Drag & drop file ke sini atau klik untuk memilih file</p>
            </div>
            <input id="files" name="files[]" type="file" class="form-control d-none" multiple accept=".pdf,.jpg,.jpeg,.png">
        </label>
    </form>

    <!-- Loading Indicator -->
    <div id="upload-status" class="mt-3 text-muted" style="display:none;">
        <i class="bi bi-arrow-repeat spin"></i> Mengunggah file...
    </div>

    <!-- Uploaded Files -->
    <div id="uploaded-files" class="mt-4">
        <h5>📁 File yang diupload:</h5>
        <ul class="list-group mt-2" id="file-list">
            <li class="list-group-item text-muted">Belum ada file.</li>
        </ul>
    </div>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
    #file-list img {
        display: block;
        margin-top: 10px;
    }
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    // Fungsi untuk ambil list file dari server
    function fetchUploadedFiles() {
        fetch('/pdf/list')
            .then(res => res.json())
            .then(data => tampilkanFiles(data.files))
            .catch(err => {
                console.error('Gagal ambil file:', err);
                fileList.innerHTML = '<li class="list-group-item text-danger">Gagal memuat file.</li>';
            });
    }

    // Panggil saat halaman pertama kali dibuka
    fetchUploadedFiles();

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('upload-form');
        const input = document.getElementById('files');
        const dropZone = document.getElementById('drop-zone');
        const fileList = document.getElementById('file-list');
        const status = document.getElementById('upload-status');

        function tampilkanFiles(files) {
            fileList.innerHTML = '';
            if (files.length === 0) {
                fileList.innerHTML = '<li class="list-group-item text-muted">Belum ada file.</li>';
                return;
            }

            files.forEach(file => {
                const li = document.createElement('li');
                li.className = 'list-group-item';

                const fileUrl = `/storage/uploads/${file}`;
                const isImage = file.match(/\.(jpg|jpeg|png)$/i);
                const isPDF = file.match(/\.pdf$/i);

                // Tampilkan nama & link
                const link = document.createElement('a');
                link.href = fileUrl;
                link.textContent = file;
                link.target = '_blank';
                link.className = 'fw-bold d-block mb-2';

                // Tambahkan ikon
                const icon = document.createElement('span');
                icon.className = 'me-2';
                icon.innerHTML = isImage ? '🖼' : isPDF ? '📄' : '📁';

                // Tombol Hapus
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = '🗑 Hapus';
                deleteBtn.className = 'btn btn-sm btn-danger ms-2';
                deleteBtn.onclick = function () {
                    if (!confirm(`Hapus file ${file}?`)) return;

                    fetch(`/pdf/delete/${file}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message || 'Berhasil dihapus');
                        li.remove();
                    })
                    .catch(err => alert('Gagal menghapus file.'));
                };

                li.appendChild(icon);
                li.appendChild(link);
                li.appendChild(deleteBtn);

                // Tambahkan preview
                if (isImage) {
                    const img = document.createElement('img');
                    img.src = fileUrl;
                    img.alt = file;
                    img.className = 'img-thumbnail';
                    img.style.maxWidth = '300px';
                    li.appendChild(img);
                } else if (isPDF) {
                    const iframe = document.createElement('iframe');
                    iframe.src = fileUrl;
                    iframe.width = '100%';
                    iframe.height = '400px';
                    iframe.className = 'mt-2 border rounded';
                    li.appendChild(iframe);
                }

                fileList.appendChild(li);
            });
        }


        input.addEventListener('change', () => {
            if (!input.files.length) return;

            status.style.display = 'block';
            const data = new FormData(form);
            fetch('{{ route("pdf.upload") }}', {
                method: 'POST',
                body: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                tampilkanFiles(data.files);
                status.style.display = 'none';
            })
            .catch(err => {
                alert("Upload gagal 😥");
                status.style.display = 'none';
            });
        });

        form.addEventListener('dragover', e => {
            e.preventDefault();
            dropZone.classList.add('bg-info-subtle');
        });

        form.addEventListener('dragleave', () => {
            dropZone.classList.remove('bg-info-subtle');
        });

        form.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('bg-info-subtle');

            const dataTransfer = new DataTransfer();
            for (const file of e.dataTransfer.files) {
                dataTransfer.items.add(file);
            }

            input.files = dataTransfer.files;
            input.dispatchEvent(new Event('change'));
        });

        dropZone.addEventListener('click', () => input.click());
    });
</script>
@endsection
