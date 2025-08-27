import 'chartjs-adapter-moment';



document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('UmurChart');
    if (!canvas) {
        console.error('Elemen canvas untuk grafik umur tidak ditemukan.');
        return;
    }

    const ctx = canvas.getContext('2d');
    const datasets = window.datagrafikumur;

    if (!datasets.hari.labels.length || !datasets.hari.count.length) {
        console.error('Data grafik umur (hari) kosong atau tidak valid.');
        return;
    }

    const colorSchemess = {
        primary: {
            background: 'rgba(54, 162, 235, 0.1)',
            border: 'rgba(54, 162, 235, 1)',
            point: 'rgba(54, 162, 235, 1)'
        },
        secondary: {
            background: 'rgba(255, 99, 132, 0.1)',
            border: 'rgba(255, 99, 132, 1)',
            point: 'rgba(255, 99, 132, 1)'
        }
    };

    const colorSchemes = [
        'rgba(54, 162, 235, 0.8)',    // 0-5
        'rgba(75, 192, 192, 0.8)',    // 6-12
        'rgba(255, 206, 86, 0.8)',    // 13-17
        'rgba(255, 99, 132, 0.8)',    // 18-35
        'rgba(153, 102, 255, 0.8)',   // 36-59
        'rgba(255, 159, 64, 0.8)'     // 60+
    ];

    const getStackedDataset = (type) => {
        return datasets[type].labels.map((label, i) => ({
            label: label,
            data: [datasets[type].count[i]],
            backgroundColor: colorSchemes[i % colorSchemes.length],
            stack: type
        }));
    };

    let chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: window.datagrafikumur.bulan.moon,
            datasets: getStackedDataset('hari')
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Umur Terbanyak',
                    color: '#333',
                    font: { size: 18, weight: 'bold' }
                },
                legend: {
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 14, weight: 'bold' },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#333',
                    borderWidth: 2,
                    cornerRadius: 8,
                    displayColors: true,
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 10,
                    callbacks: {
                        title: function (context) {
                            return context[0].dataset.label;
                        },
                        label: function (context) {
                            return `Jumlah: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Kelompok Umur / Bulan / Tahun',
                        color: '#333',
                        font: { size: 14, weight: 'bold' }
                    },
                    ticks: { color: '#333', font: { size: 12 } }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pasien',
                        color: '#333',
                        font: { size: 14, weight: 'bold' }
                    },
                    ticks: { color: '#333', font: { size: 12 }, stepSize: 1 }
                }
            }
        }
    });

    const filterSelect = document.getElementById('filterUmur');
    if (filterSelect) {
        filterSelect.addEventListener('change', (e) => {
            const val = e.target.value;
            if (!datasets[val]) {
                console.error(`Data untuk filter '${val}' tidak ditemukan.`);
                return;
            }

            chart.data.datasets = getStackedDataset(val);

            // Update sumbu X sesuai filter
            if (val === 'bulan') {
                chart.options.scales.x.title.text = 'Bulan';
            } else if (val === 'tahun') {
                chart.options.scales.x.title.text = 'Tahun';
            } else {
                chart.options.scales.x.title.text = 'Kelompok Umur';
            }

            chart.update();
        });
    }
});
