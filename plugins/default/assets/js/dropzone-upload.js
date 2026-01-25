let uploadedFile = null;
Dropzone.autoDiscover = false;

export function initDropzone(selector) {

    const config = window.APP?.UPLOAD_CONFIG;
    if (!config) {
        console.error('UPLOAD_CONFIG not defined');
        return;
    }

    const dropzoneEl = document.querySelector(selector);
    if (!dropzoneEl) {
        console.error('Dropzone element not found:', selector);
        return;
    }

    const imageType = dropzoneEl.dataset.imgtype || 'default';
    const previewsContainer = '#file-previews';

    const previewTemplateEl = document.querySelector('#uploadPreviewTemplate');
    const previewTemplate = previewTemplateEl ? previewTemplateEl.innerHTML : null;

    const myDropzone = new Dropzone(dropzoneEl, {
        previewsContainer: previewsContainer,
        previewTemplate: previewTemplate,
        maxFilesize: 5,
        autoProcessQueue: false,

        init: function () {

            this.on('addedfile', file => {

                // ======================
                // PROGRESS BAR
                // ======================
                const progressWrap = document.createElement('div');
                progressWrap.className = 'progress mt-2';
                progressWrap.style.height = '6px';

                const progressBar = document.createElement('div');
                progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated';
                progressBar.style.width = '0%';

                progressWrap.appendChild(progressBar);
                file.previewElement.appendChild(progressWrap);

                const setProgress = percent => {
                    progressBar.style.width = percent + '%';
                };

                const removeProgress = () => {
                    setTimeout(() => progressWrap.remove(), 500);
                };

                // ======================
                // UPLOAD SWITCH
                // ======================
                if (Number(config.place) === 1) {
                    uploadSelfHosting(file,config);
                }

                if (Number(config.place) === 2) {
                    uploadImgBB(file);
                }

                if (Number(config.place) === 3) {
                    uploadImgur(file);
                }

                // ======================
                // SELF HOSTING
                // ======================
                function uploadSelfHosting(file,config) {
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('file_attr', JSON.stringify(config));

                    const xhr = new XMLHttpRequest();

                    const url = config.uploadUrl + `?type=${encodeURIComponent(imageType)}`;

                    xhr.open('POST', url, true);

                    xhr.upload.onprogress = e => {
                        if (e.lengthComputable) {
                            setProgress(Math.round((e.loaded / e.total) * 100));
                        }
                    };

                    xhr.onload = () => {
                        let res = {};
                        try {
                            res = JSON.parse(xhr.responseText);
                        } catch (e) {
                            handleError(file, 'Response server tidak valid');
                            return;
                        }

                        if (xhr.status === 200 && res.status === true) {
                            setProgress(100);
                            removeProgress();
                            handleSuccess(config.uploadType, file, res.data.file_path, res.data);
                        } else {
                            handleError(file, res.message || 'Upload gagal');
                        }
                    };

                    xhr.onerror = () => handleError(file, 'Upload error');
                    xhr.send(formData);
                }


                // ======================
                // IMGBB
                // ======================
                function uploadImgBB(file) {
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        const base64 = reader.result.replace(/^data:image\/\w+;base64,/, '');
                        const fd = new FormData();
                        fd.append('key', config.key);
                        fd.append('image', base64);

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'https://api.imgbb.com/1/upload', true);

                        xhr.upload.onprogress = e => {
                            if (e.lengthComputable) {
                                setProgress(Math.round(e.loaded / e.total * 100));
                            }
                        };

                        xhr.onload = () => {
                            const res = JSON.parse(xhr.responseText || '{}');
                            if (res.success) {
                                setProgress(100);
                                removeProgress();
                                handleSuccess(config.uploadType, file, res.data.url);
                            } else {
                                handleError(file, 'ImgBB gagal');
                            }
                        };

                        xhr.send(fd);
                    };
                    reader.readAsDataURL(file);
                }

                // ======================
                // IMGUR
                // ======================
                function uploadImgur(file) {
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        const base64 = reader.result.split(',')[1];
                        const fd = new FormData();
                        fd.append('image', base64);

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'https://api.imgur.com/3/image', true);
                        xhr.setRequestHeader('Authorization', 'Client-ID ' + config.key);

                        xhr.upload.onprogress = e => {
                            if (e.lengthComputable) {
                                setProgress(Math.round(e.loaded / e.total * 100));
                            }
                        };

                        xhr.onload = () => {
                            const res = JSON.parse(xhr.responseText || '{}');
                            if (res.success) {
                                setProgress(100);
                                removeProgress();
                                handleSuccess(config.uploadType, file, res.data.link);
                            } else {
                                handleError(file, 'Imgur gagal');
                            }
                        };

                        xhr.send(fd);
                    };
                    reader.readAsDataURL(file);
                }

                // ======================
                // UI HANDLER
                // ======================
                function setIfExists(id, value) {
                    const el = document.getElementById(id);
                    if (el) el.value = value;
                }
                function handleSuccess(fileType, file, imageUrl, res) {
                    file.previewElement.querySelector('[data-dz-thumbnail]').src = '/' + imageUrl;

                    const btnWrap = document.createElement('div');
                    btnWrap.className = 'mt-2s';
                    
                    if(fileType=="file"){
                        uploadedFile = {
                            file_path: res.file_path,
                            file_name: res.file_name,
                            file_size: res.file_size,
                            file_type: res.file_type
                        };
                        setIfExists('storage_type', config.place == 1 ? 'local' : 'external');
                        setIfExists('file_path', res.file_path);
                        setIfExists('file_name', res.file_name);
                        setIfExists('file_size', res.file_size);
                        setIfExists('file_type', res.file_type);
                    }else{
                        imageUrl = window.location.origin + '/' + imageUrl;
                        const btnAdd = document.createElement('button');
                        btnAdd.className = 'btn btn-sm btn-primary me-3';
                        btnAdd.textContent = 'Tambah ke Editor';
                        btnAdd.onclick = () => {
                            window.scEditor.insert(`[img]${imageUrl}[/img]`);
                        };

                        const btnCopy = document.createElement('button');
                        btnCopy.type = 'button';
                        btnCopy.className = 'btn btn-sm btn-secondary';
                        btnCopy.textContent = 'Salin URL';

                        btnCopy.onclick = function () {
                            navigator.clipboard.writeText(imageUrl)
                                .then(() => Swal.fire('Berhasil', 'URL disalin', 'success'))
                                .catch(() => Swal.fire('Gagal', 'Tidak bisa menyalin URL', 'error'));
                        };

                        btnWrap.appendChild(btnAdd);
                        btnWrap.appendChild(btnCopy);
                    }
                    file.previewElement.appendChild(btnWrap);
                }

                function handleError(file, msg) {
                    const el = document.createElement('div');
                    el.className = 'text-danger mt-1';
                    el.textContent = msg;
                    file.previewElement.appendChild(el);
                }
            });
        }
    });
}