document.addEventListener('DOMContentLoaded', function () {
    // Atur locale sesuai bahasa aktif dari Laravel
    moment.locale('{{ app()->getLocale() }}');

    function updateLiveTime() {
        const now = moment();
        const waktu = now.format('dddd, D MMMM YYYY [|] HH:mm:ss');

        const liveTimeElement = document.getElementById('liveTime'); // didefinisikan DI SINI
        if (liveTimeElement) {
            liveTimeElement.textContent = waktu;
        }

        // Pilih semua elemen dengan class live_Time
        const elements = document.querySelectorAll('.live_Time');
        elements.forEach(el => {
            el.textContent = waktu;
        });
    }

    updateLiveTime();
    setInterval(updateLiveTime, 1000);
});
