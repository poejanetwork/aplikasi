(function ($) {
    "use strict";
    let currentPage = 1;
    let currentSearch = '';

    function loadData(page = 1) {
        currentPage = page;
        $.get(APP.getUrl + '/users/data', {
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
                        <td>${row.fullname}</td>
                        <td>${row.email}</td>
                        <td>satuan</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-primary"
                                       href="javascript:void(0);"
                                       onclick="editUser(${row.id})">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    ${row.status == 1 ? `
                                    <a href="javascript:void(0);" class="dropdown-item text-danger" onclick="deleteUser(${row.id})">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </a>` : ''}
                                    ${row.status == 0 ? `
                                    <a href="javascript:void(0);" class="dropdown-item text-info" onclick="restoreUser(${row.id})">
                                        <i class="ti ti-recycle me-1"></i> Restore
                                    </a>` : ''}
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#usersTable tbody').html(rows);
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
    window.addUser = function (id) {
        $('#dataModalTitle').text('Add User');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/users/create/'
        );
    };
    $(document).on('submit', '#formAddUser', function (e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: APP.getUrl + '/users/store',
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

    window.editUser = function (id) {
        $('#dataModalTitle').text('Edit User');
        $('#dataModalBody').html('Loading...');
        $('#dataModal').modal('show');

        $('#dataModalBody').load(
            APP.getUrl + '/users/edit/' + id
        );
    };
    $(document).on('submit', '#formEditUser', function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        $.ajax({
            url: APP.getUrl + '/users/update/' + id,
            type: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            contentType: false,
            processData: false,
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
       DELETE USER
    =============================== */
    window.deleteUser = function (id) {
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
                    url: APP.getUrl + '/users/delete/' + id,
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
    window.restoreUser = function (id) {
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
                    url: APP.getUrl + '/users/restore/' + id,
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
        if ($('#usersTable').length) {
            loadData();
        }
    });

    // expose jika dibutuhkan global
    window.loadData = loadData;

})(jQuery);
