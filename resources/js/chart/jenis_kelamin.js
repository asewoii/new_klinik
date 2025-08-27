import 'chartjs-adapter-moment';

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('JKChart');
    if (!canvas) {
        console.error('Elemen canvas untuk grafik jenis kelamin tidak ditemukan.');
        return;
    }

    const ctx = canvas.getContext('2d');
    const datasets = window.datagrafikjeniskelamin;

    // Validasi data
    if (!datasets?.hari?.labels?.length || !datasets?.hari?.count?.length) {
        console.error('Data grafik jenis kelamin (hari) kosong atau tidak valid.');
        showEmptyState(ctx);
        return;
    }

    // Konfigurasi warna yang konsisten
    const colorSchemes = {
        primary: {
            background: 'rgba(54, 162, 235, 0.1)',
            border: 'rgba(54, 162, 235, 1)',
            point: 'rgba(54, 162, 235, 1)'
        },
        secondary: {
            background: 'rgba(255, 99, 132, 0.1)',
            border: 'rgba(255, 99, 132, 1)',
            point: 'rgba(255, 99, 132, 1)'
        },
    };

    const labels = datasets.hari.labels;
    const jumlah = datasets.hari.count;

    let chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pasien',
                data: jumlah,
                backgroundColor: [colorSchemes.primary.background, '#e74a3b'], // Biru untuk L, merah untuk P
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
});
