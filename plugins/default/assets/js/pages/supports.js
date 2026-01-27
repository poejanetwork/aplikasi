(function ($) {
    "use strict";
    let currentPage = 1;
    let currentSearch = '';

    function loadData(page = 1) {
        currentPage = page;
        $.get(APP.getUrl + '/supports/data', {
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
            let rowstatus = row.status == 0 ? '<button type="button" class="btn btn-danger btn-sm">Belum Dibaca</button>' : '<button type="button" class="btn btn-success btn-sm">Sudah dibalas</button>';
            const showUrl = `/admin/supports/show/${row.id}`;
                rows += `
                    <tr class="${rowClass} clickable-row" data-href="${showUrl}">
                        <td>${startNumber + index + 1}</td>
                        <td>${row.name}<br/>${row.email}</td>
                        <td>${row.subject}</td>
                        <td>${rowstatus}</td>
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

    document.addEventListener('click', function (e) {
        const tr = e.target.closest('tr.clickable-row');
        if (!tr) return;
        const href = tr.dataset.href;
        if (href) {
            window.location.href = href;
        }
    });

    $(document).on('submit', '#formReplyData', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
            url: APP.getUrl + '/supports/reply/' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.status === true) {
                    swalSuccess(res.message).then(() => {
                        window.location.href = APP.getUrl + '/supports';
                    });
                } else {
                    swalError(res.message, 'Tutup');
                }
            },
            error: function (xhr) {
                swalError('Please read console log', 'Tutup');
                console.error(xhr.responseText);
            }
        });
    });

    $(function () {
        if ($('#dataTable').length) {
            loadData();
        }
    });

    // expose jika dibutuhkan global
    window.loadData = loadData;

})(jQuery);
