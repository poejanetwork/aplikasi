(function ($) {
    "use strict";
    let currentPage = 1;
    let currentSearch = '';

    function loadData(page = 1) {
        currentPage = page;
        $.get(APP.getUrl + '/sm/data', {
            page: page,
            search: currentSearch
        }, function (res) {

            if (!Array.isArray(res.data)) {
                console.error('Invalid response', res);
                return;
            }
            let rows = '';
            let startNumber = (res.pagination.current_page - 1) * res.pagination.per_page;

            res.data.forEach(function (row, index) {
            let rowClass = row.status == 0 ? 'table-danger text-muted' : '';
                rows += `
                    <tr class="${rowClass}">
                        <td>${startNumber + index + 1}</td>
                        <td>${row.nomor_surat}<br/>${row.asal_surat}</td>
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
            APP.getUrl + '/sm/create/'
        );
    };
    $(document).on('submit', '#formAddData', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: APP.getUrl + '/sm/store',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status === true) {
                    $('#dataModal').modal('hide');
                    loadData();
                    swalSuccess(res.message)
                } else {
                    swalError(res.message, 'Tutup')
                }
            },
            error: function (xhr) {
                swalError('Please read console log', 'Tutup');
                console.error(xhr.responseText);
            }
        });
    });

    window.showData = function (id) {
        $('#dataModalTitle').text('Lihat Data');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/sm/show/' + id
        );
    };
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
        let formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
            url: APP.getUrl + '/sm/update/' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.status === true) {
                    $('#dataModal').modal('hide');
                    loadData();
                    swalSuccess(res.message)
                } else {
                    swalError(res.message, 'Tutup')
                }
            },
            error: function (xhr) {
                swalError('Please read console log', 'Tutup');
                console.error(xhr.responseText);
            }
        });
    });


    /* ===============================
       DELETE DATA
    =============================== */
    window.deleteData = function (id) {
        swalDelete('Hapus data?','Data tidak bisa dikembalikan').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: APP.getUrl + '/sm/delete/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            swalSuccess(res.message)
                            loadData();
                        } else {
                            swalError(res.message, 'Tutup')
                        }
                    }
                });
            }
        });
    };
    window.restoreData = function (id) {
        swalDelete('Restore data?','Data akan dikembalikan').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: APP.getUrl + '/sm/restore/' + id,
                    type: 'POST',
                    data: { _method: 'PATCH' },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            swalSuccess(res.message)
                            loadData();
                        } else {
                            swalError(res.message, 'Tutup')
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
