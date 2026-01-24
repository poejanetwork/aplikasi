(function ($) {
    "use strict";
    let currentPage = 1;
    let currentSearch = '';

    function loadData(page = 1) {
        currentPage = page;
        $.get(APP.getUrl + '/sk/data', {
            page: page,
            search: currentSearch
        }, function (res) {

            if (!Array.isArray(res.data)) {
                console.error('Invalid response', res);
                return;
            }
            let rows = '';

            res.data.forEach(function (row) {
            let rowClass = row.status == 0 ? 'table-danger text-muted' : '';
                rows += `
                    <tr class="${rowClass}">
                        <td>${row.id}</td>
                        <td>${row.nomor_surat}<br/>${row.tujuan_surat}</td>
                        <td>${row.perihal}</td>
                        <td>${row.tanggal_surat}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-primary"
                                       href="javascript:void(0);"
                                       onclick="showData(${row.id})">
                                        <i class="ti ti-eye me-1"></i> Lihat
                                    </a>
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
        let current = p.current_page;
        let last = p.last_page;
        let maxVisible = 5;
        let start = Math.max(1, current - Math.floor(maxVisible / 2));
        let end = start + maxVisible - 1;

        if (end > last) {
            end = last;
            start = Math.max(1, end - maxVisible + 1);
        }
        html += `
            <li class="page-item ${current === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" onclick="loadData(${current - 1})">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
        `;
        for (let i = start; i <= end; i++) {
            html += `
                <li class="page-item ${i === current ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0);" onclick="loadData(${i})">
                        ${i}
                    </a>
                </li>
            `;
        }
        html += `
            <li class="page-item ${current === last ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" onclick="loadData(${current + 1})">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        `;

        $('#pagination').html(html);
    }
    let searchTimer = null;

    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(() => {
            currentSearch = $(this).val().trim();
            loadData(1); // reset ke page 1
        }, 400);
    });


    /* ===============================
       MODAL ADD & EDIT
    =============================== */
    window.addData = function (id) {
        $('#dataModalTitle').text('Add Surat');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/sk/create/'
        );
    };
    $(document).on('submit', '#formAddData', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: APP.getUrl + '/sk/store',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
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

    window.showData = function (id) {
        $('#dataModalTitle').text('Lihat Data');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/sk/show/' + id
        );
    };
    window.editData = function (id) {
        $('#dataModalTitle').text('Edit Data');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/sk/edit/' + id
        );
    };
    $(document).on('submit', '#formEditData', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
            url: APP.getUrl + '/sk/update/' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
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
                    url: APP.getUrl + '/sk/delete/' + id,
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
                    url: APP.getUrl + '/sk/restore/' + id,
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
