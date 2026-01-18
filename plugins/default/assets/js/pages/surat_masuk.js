(function ($) {
    "use strict";

    function loadData(page = 1) {
        $.get(APP.getUrl + '/sm/data', { page: page }, function (res) {

            let rows = '';

            if (!Array.isArray(res.data)) {
                console.error('Invalid response', res);
                return;
            }

            res.data.forEach(function (row) {
            let rowClass = row.status == 0 ? 'table-danger text-muted' : '';
                rows += `
                    <tr class="${rowClass}">
                        <td>${row.id}</td>
                        <td>${row.nomor_surat}<br/>${row.asal_surat}</td>
                        <td>${row.perihal}</td>
                        <td>${row.tanggal_surat}</td>
                        <td>${row.tanggal_terima}</td>
                        <td>${row.keterangan}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-primary"
                                       href="javascript:void(0);"
                                       onclick="editData(${row.id})">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    ${row.status == 1 ? `
                                    <a href="javascript:void(0);" class="dropdown-item text-danger" onclick="deleteData(${row.id})">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </a>` : ''}
                                    ${row.status == 0 ? `
                                    <a href="javascript:void(0);" class="dropdown-item text-info" onclick="restoreData(${row.id})">
                                        <i class="ti ti-recycle me-1"></i> Restore
                                    </a>` : ''}
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#dataTable tbody').html(rows);
            renderPagination(res.pagination);
        });
    }

    function renderPagination(p) {
        let html = '';

        for (let i = 1; i <= p.last_page; i++) {
            html += '<button class="btn btn-sm ' +
                (i === p.current_page ? 'btn-primary' : 'btn-outline-primary') +
                '" onclick="loadData(' + i + ')">' + i + '</button> ';
        }

        $('#pagination').html(html);
    }

    /* ===============================
       MODAL ADD & EDIT
    =============================== */
    window.addData = function (id) {
        $('#dataModalTitle').text('Add Surat');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/sm/create/'
        );
    };
    $(document).on('submit', '#formAddData', function (e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: APP.getUrl + '/sm/store',
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (res) {
                console.log(res)
                if (res.status) {
                    $('#dataModal').modal('hide');
                    loadData();
                    Swal.fire('Berhasil', res.message, 'success');
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire(
                    'Server error',
                    'Please read console log',
                    'error'
                );
            }
        });
    });

    window.editData = function (id) {
        $('#dataModalTitle').text('Edit Data');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/sm/edit/' + id
        );
    };
    $(document).on('submit', '#formEditData', function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        $.ajax({
            url: APP.getUrl + '/sm/update/' + id,
            type: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            dataType: 'json',
            success: function (res) {
                if (res.status) {
                    $('#dataModal').modal('hide');
                    loadData();
                    Swal.fire('Berhasil', res.message, 'success');
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire(
                    'Server error',
                    'Please read console log',
                    'error'
                );
            }
        });
    });


    /* ===============================
       DELETE DATA
    =============================== */
    window.deleteData = function (id) {
        Swal.fire({
            title: 'Hapus data?',
            text: 'Data tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: APP.getUrl + '/sm/delete/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            Swal.fire('Berhasil', res.message, 'success');
                            loadData();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }
                });
            }
        });
    };
    window.restoreData = function (id) {
        Swal.fire({
            title: 'Restore data?',
            text: 'Data akan dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, restore',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: APP.getUrl + '/sm/restore/' + id,
                    type: 'POST',
                    data: { _method: 'PATCH' },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            Swal.fire('Berhasil', res.message, 'success');
                            loadData();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }
                });
            }
        });
    };


    $(function () {
        if ($('#dataTable').length) {
            loadData();
        }
    });

    // expose jika dibutuhkan global
    window.loadData = loadData;

})(jQuery);
