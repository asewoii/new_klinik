$(document).ready(function () {
    var languageOptions = {
        en: {
            search: '',
            searchPlaceholder: 'Search...',
            lengthMenu: 'Show _MENU_ entries per page',
            zeroRecords: 'No matching records found',
            info: '<strong>Total:</strong> _TOTAL_ | <strong>Displayed:</strong> _START_ - _END_ | <strong>Page:</strong> _PAGE_ / _PAGES_ | <strong>Rows:</strong> _START_ - _END_',
            infoEmpty: '<strong>Total Complaints:</strong> 0 | <strong>Displayed:</strong> 0 | <strong>Page:</strong> 0 / 0 | <strong>Rows:</strong> 0 - 0',
            infoFiltered: '(filtered from _MAX_ total entries)',
            paginate: {
                first: 'First',
                last: 'Last',
                next: 'Next',
                previous: 'Previous'
            }
        },
        id: {
            search: '',
            searchPlaceholder: 'Cari...',
            lengthMenu: 'Tampilkan _MENU_ data per halaman',
            zeroRecords: 'Tidak ada data ditemukan',
            info: '<strong>Total:</strong> _TOTAL_ | <strong>Ditampilkan:</strong> _START_ - _END_ | <strong>Halaman:</strong> _PAGE_ / _PAGES_ | <strong>Baris:</strong> _START_ - _END_',
            infoEmpty: '<strong>Total Keluhan:</strong> 0 | <strong>Ditampilkan:</strong> 0 | <strong>Halaman:</strong> 0 / 0 | <strong>Baris:</strong> 0 - 0',
            infoFiltered: '(disaring dari _MAX_ total data)',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            }
        }
    };

    var currentLocale = '{{ app()-> getLocale()}}';

    // Inisialisasi DataTables saat modal dibuka
    $(document).on('shown.bs.modal', function (e) {
        setTimeout(() => {
            $(e.target).find('table.datatable-dokter').each(function () {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        scrollY: 200,       // scroll vertikal, atur tinggi sesuai kebutuhan
                        scrollX: true,          // aktifkan scroll horizontal
                        scrollCollapse: true,
                        pageLength: 5,
                        paging: false,
                        pagingType: 'simple_numbers',
                        searching: true,
                        ordering: true,
                        info: false,
                        searchBox: false,
                        lengthChange: true,
                        lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                        language: languageOptions[currentLocale] || languageOptions.id,
                    });
                }
            });
        }, 100); // Delay kecil untuk memastikan DOM siap
    });

    $('#table').DataTable({
        scrollY: 200, // scroll vertikal, atur tinggi sesuai kebutuhan
        scrollX: true, // aktifkan scroll horizontal
        scrollCollapse: true,
        destroy: true,
        paging: true,
        pageLength: 2,
        pagingType: 'simple_numbers',
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        lengthMenu: [[2, 5, 10, 25, 50, 100], [2, 5, 10, 25, 50, 100]],
        language: languageOptions[currentLocale],
    });
});
