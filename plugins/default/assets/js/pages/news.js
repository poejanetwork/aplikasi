(function ($) {
    "use strict";
    let currentPage = 1;
    let currentSearch = '';

    function loadData(page = 1) {
        currentPage = page;
        $.get(APP.getUrl + '/news/data', {
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
                        <td>${row.user_name}<br/>${row.created_at}</td>
                        <td>${row.title}</td>
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

    var textarea = document.getElementById('editor');
    sceditor.create(textarea, {
        format: 'bbcode',
        style: 'plugins/sceditor/minified/themes/content/default.min.css',
        toolbar: 'bold,italic,underline,strike|left,center,right,justify|font,size,color,removeformat|bulletlist,orderedlist|table,code,quote,horizontalrule|image,youtube,link,unlink|source',
        autoExpand: true,
        resizeHeight: true,
        resizeMinHeight: 400,
        autoUpdate: true,
        autoParagraph: false,
        emoticons: false
    });
    sceditor.instance(textarea).css(`
    img {
        max-width: 50%;
        height: auto;
        margin: 5px auto;
    }
    `);
    
Dropzone.autoDiscover = false;

$('[data-plugin="dropzone"]').each(function () {
    var previewsContainer = '#file-previews';
    var previewTemplateSelector = document.querySelector('#uploadPreviewTemplate').innerHTML;
    var previewTemplate = previewTemplateSelector ? $(previewTemplateSelector).html() : null;
    var imageType = $('#myAwesomeDropzone').data('imgtype');

    var myDropzone = new Dropzone(this, {
        previewsContainer: previewsContainer,
        previewTemplate: previewTemplate,
        maxFilesize: 5,
        autoProcessQueue: false,
        init: function () {
            this.on("addedfile", function (file) {

                // Tambahkan progress bar manual (karena pakai fetch)
                let progressBarContainer = document.createElement("div");
                progressBarContainer.classList.add("progress", "mt-2");
                progressBarContainer.style.height = "6px";

                let progressBar = document.createElement("div");
                progressBar.classList.add("progress-bar", "progress-bar-striped", "progress-bar-animated");
                progressBar.setAttribute("role", "progressbar");
                progressBar.style.width = "0%";
                progressBarContainer.appendChild(progressBar);

                if (file.previewElement)
                    file.previewElement.appendChild(progressBarContainer);

                // Fungsi update progress
                function updateProgress(percent) {
                    progressBar.style.width = percent + "%";
                }
                // Fungsi hapus progress bar
                function removeProgressBar() {
                    if (progressBarContainer && progressBarContainer.parentNode) {
                        setTimeout(() => {
                            progressBarContainer.remove();
                        }, 500); // hilang 0.5 detik setelah 100%
                    }
                }

                // ===================================================
                // =============== UPLOAD KE SERVER SENDIRI ==========
                // ===================================================
                <?php if (isset($settings['upload_place']) && $settings['upload_place'] == 0): ?>
                var formData = new FormData();
                formData.append('file', file);

                const xhr = new XMLHttpRequest();
                xhr.open("POST", '<?=$settings["siteurl"] . "/" . $settings["admin_dir"];?>?p=upload_img&act=upload&type=' + imageType, true);

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        updateProgress(percent);
                    }
                };

                xhr.onload = function () {
                    const data = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && data.result === 'success') {
                        updateProgress(100);
                        removeProgressBar();
                        handleUploadResult(file, data.url, true);
                    } else {
                        showUploadError(file, data.msg || "Gagal upload");
                    }
                };

                xhr.onerror = function () {
                    showUploadError(file, 'Terjadi error saat upload');
                };

                xhr.send(formData);
                <?php endif; ?>


                // ===================================================
                // ================== UPLOAD KE IMGUR ===============
                // ===================================================
                <?php if (isset($settings['upload_place']) && $settings['upload_place'] == 2): ?>
                var CLIENT_ID = '<?=$settings['upload_place_key'];?>';
                const readerImgur = new FileReader();

                readerImgur.onloadend = function () {
                    const base64Image = readerImgur.result.split(',')[1];
                    const formData = new FormData();
                    formData.append("image", base64Image);

                    // gunakan XMLHttpRequest agar bisa pakai progress
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "https://api.imgur.com/3/image", true);
                    xhr.setRequestHeader("Authorization", "Client-ID " + CLIENT_ID);

                    xhr.upload.onprogress = function (e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            updateProgress(percent);
                        }
                    };

                    xhr.onload = function () {
                        const data = JSON.parse(xhr.responseText);
                        if (xhr.status === 200 && data.success) {
                            updateProgress(100);
                            removeProgressBar();
                            handleUploadResult(file, data.data.link, true);
                        } else {
                            showUploadError(file, data.data?.error || "Gagal upload Imgur");
                        }
                    };

                    xhr.onerror = function () {
                        showUploadError(file, 'Terjadi error saat upload ke Imgur');
                    };

                    xhr.send(formData);
                };
                readerImgur.readAsDataURL(file);
                <?php endif; ?>


                // ===================================================
                // ================== UPLOAD KE IMGBB ===============
                // ===================================================
                <?php if (isset($settings['upload_place']) && $settings['upload_place'] == 1): ?>
                var APIkey = '<?=$settings['upload_place_key'];?>';
                const readerImgbb = new FileReader();

                readerImgbb.onloadend = function () {
                    const base64Image = readerImgbb.result.replace(/^data:image\/(png|jpeg|jpg);base64,/, '');
                    const formData = new FormData();
                    formData.append('key', APIkey);
                    formData.append('image', base64Image);

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "https://api.imgbb.com/1/upload", true);

                    xhr.upload.onprogress = function (e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            updateProgress(percent);
                        }
                    };

                    xhr.onload = function () {
                        const data = JSON.parse(xhr.responseText);
                        if (xhr.status === 200 && data.success) {
                            updateProgress(100);
                            removeProgressBar();
                            let imageUrl = data.data.url;
                            imageUrl = imageUrl.replace('i.ibb.co', 'i.ibb.co.com');
                            handleUploadResult(file, imageUrl, true);
                        } else {
                            showUploadError(file, data.error?.message || "Gagal upload ImgBB");
                        }
                    };

                    xhr.onerror = function () {
                        showUploadError(file, 'Terjadi error saat upload ke ImgBB');
                    };

                    xhr.send(formData);
                };
                readerImgbb.readAsDataURL(file);
                <?php endif; ?>

            });

            // =======================
            // Fungsi bantuan umum
            // =======================
            function handleUploadResult(file, imageUrl, success, errorMsg) {
                if (!file.previewElement) return;

                if (success) {
                    // tampilkan thumbnail
                    let img = file.previewElement.querySelector('img[data-dz-thumbnail]');
                    if (img) img.src = imageUrl;

                    // hapus pesan/error lama
                    let oldInfo = file.previewElement.querySelector('.dz-success-message, .dz-action-buttons, .dz-error-message');
                    if (oldInfo) oldInfo.remove();

                    // buat container tombol
                    let btnContainer = document.createElement('div');
                    btnContainer.classList.add('dz-action-buttons');
                    btnContainer.style.marginTop = '10px';

                    // tombol: tambah ke editor
                    let btnAddImg = document.createElement('button');
                    btnAddImg.type = 'button';
                    btnAddImg.classList.add('btn', 'btn-sm', 'btn-primary', 'me-2');
                    btnAddImg.textContent = 'Tambah ke Editor';
                    btnAddImg.onclick = function () {
                        var editorInstance = sceditor.instance(textarea);
                        if (editorInstance) {
                            editorInstance.insert('[img]' + imageUrl + '[/img]');
                            swalAlert("success", 'Gambar ditambahkan ke editor');
                        } else {
                            swalAlert("error", 'Editor belum siap');
                        }
                    };

                    // tombol: salin URL
                    let btnCopyUrl = document.createElement('button');
                    btnCopyUrl.type = 'button';
                    btnCopyUrl.classList.add('btn', 'btn-sm', 'btn-secondary');
                    btnCopyUrl.textContent = 'Salin URL';
                    btnCopyUrl.onclick = function () {
                        navigator.clipboard.writeText(imageUrl).then(
                            () => swalAlert("success", 'URL gambar berhasil disalin'),
                            () => swalAlert("error", 'Gagal menyalin URL gambar')
                        );
                    };

                    btnContainer.appendChild(btnAddImg);
                    btnContainer.appendChild(btnCopyUrl);
                    file.previewElement.appendChild(btnContainer);
                } else {
                    showUploadError(file, errorMsg);
                }
            }

            function showUploadError(file, msg) {
                if (!file.previewElement) return;
                let errorMsg = file.previewElement.querySelector('.dz-error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.classList.add('dz-error-message');
                    errorMsg.style.color = 'red';
                    errorMsg.textContent = msg;
                    file.previewElement.appendChild(errorMsg);
                }
            }
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
