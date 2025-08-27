import 'chartjs-adapter-moment';



document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('layananChart');
    if (!canvas) {
        console.error('Elemen canvas untuk grafik layanan tidak ditemukan.');
        return;
    }

    const ctx = canvas.getContext('2d');

    // Ambil data global (pastikan dari Blade sudah didefinisikan)
    const datasets = {
        hari: {
            labels: window.layananHariLabels || [],
            data: window.layananHariData || [],
            detail: window.layananHariDetail || [] // Detail layanan per hari
        },
        bulan: {
            labels: window.layananBulanLabels || [],
            data: window.layananBulanData || [],
            detail: window.layananBulanDetail || [] // Detail layanan per bulan
        },
    };

    // Validasi minimal data harian tersedia
    if (!datasets.hari.labels.length || !datasets.hari.data.length) {
        console.error('Data grafik layanan (hari) kosong atau tidak valid.');
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
        }
    };

    // Buat chart default (hari)
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: datasets.hari.labels,
            datasets: [{
                label: 'Jumlah Layanan',
                data: datasets.hari.data,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                backgroundColor: colorSchemes.primary.background,
                borderColor: colorSchemes.primary.border,
                pointBackgroundColor: colorSchemes.primary.point,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: colorSchemes.primary.point,
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'

                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    cornerRadius: 8,
                    displayColors: true,
                    titleFont: {
                        size: 10,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 10,
                    callback: {
                        title: function (context) {
                            const filterType = document.getElementById('filterLayanan')?.value || 'hari';
                            const label = context[0].label;

                            if (filterType === 'hari') {
                                const tanggal = new Date(label);
                                const hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' });
                                const tgl = tanggal.toLocaleDateString('id-ID');
                                return `${hari}, ${tgl}`;
                            } else if (filterType === 'bulan') {
                                return `Bulan ${label}`;
                            } else {
                                return `Tahun ${label}`;
                            }
                        },
                        label: function (context) {
                            const jumlah = context.parsed.y;
                            const index = context.dataIndex;
                            const filterType = document.getElementById('filterLayanan')?.value || 'hari';

                            let result = [`Total Layanan: ${jumlah}`];

                            // Tambahkan detail jika tersedia
                            const currentDetail = datasets[filterType].detail?.[index];
                            if (currentDetail && typeof currentDetail === 'object') {
                                result.push(''); // Separator
                                Object.entries(currentDetail).forEach(([key, value]) => {
                                    result.push(`${key}: ${value}`);
                                });
                            }

                            return result;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Grafik Layanan Terbanyak',
                    color: '#333',
                    font: {
                        size: 18,
                        weight: 'bold'
                    },
                }
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal',
                        color: '#333',
                        font: { size: 14 }
                    },
                    ticks: {
                        color: '#333',
                        font: { size: 12 }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah Layanan',
                        color: '#333',
                        font: { size: 14 },
                        type: 'linear'
                    },
                    ticks: {
                        color: '#333',
                        font: { size: 12 },
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        }
    });


    const filterSelect = document.getElementById('filterLayanan');
    if (filterSelect) {
        filterSelect.addEventListener('change', (e) => {
            const val = e.target.value;

            if (!datasets[val]) {
                console.error(`Data untuk filter '${val}' tidak ditemukan.`);
                return;
            }

            // Update data chart
            chart.data.labels = datasets[val].labels;
            chart.data.datasets[0].data = datasets[val].data;

            // Ubah label sumbu X sesuai filter
            if (val === 'bulan') {
                chart.options.scales.x.title.text = 'Bulan';
            } else {
                chart.options.scales.x.title.text = 'Tanggal';
            }

            chart.update();
        });
    }



});
