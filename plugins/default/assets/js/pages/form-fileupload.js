Dropzone.autoDiscover = false;

$('[data-plugin="dropzone"]').each(function () {
    var previewsContainer = '#file-previews';
    var previewTemplateSelector = document.querySelector('#uploadPreviewTemplate').innerHTML;
    var previewTemplate = previewTemplateSelector ? $(previewTemplateSelector).html() : null;
    var actionURL = window.siteurl;

    var myDropzone = new Dropzone(this, {
        previewsContainer: previewsContainer,
        previewTemplate: previewTemplate,
        maxFilesize: 5, // MB contoh
        autoProcessQueue: false, // supaya tidak auto upload langsung
        init: function () {
            this.on("addedfile", function (file) {
                var formData = new FormData();
                formData.append('file', file); // sesuaikan key backend

                fetch('<?=$settings["siteurl"] . "/" . $settings["admin_dir"];?>?p=upload_img&act=upload&type=default', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (data.result == 'success') {
                        // Update preview file berhasil
                        console.log("Upload sukses:", data.url);

                        // Update preview thumbnail
                        if(file.previewElement) {
                            let img = file.previewElement.querySelector('img[data-dz-thumbnail]');
                            if(img) img.src = data.url;

                            // Tampilkan pesan sukses di preview
                            let info = file.previewElement.querySelector('.dz-success-message');
                            if(!info) {
                                info = document.createElement('div');
                                info.classList.add('dz-success-message');
                                info.style.color = 'green';
                                info.textContent = 'Upload berhasil!';
                                file.previewElement.appendChild(info);
                            }
                        }

                        // Opsi: set hidden input jika perlu
                        // $('#yourHiddenInputId').val(data.url);
                    } else {
                        // Tangani error dari server
                        console.error("Upload gagal:", data.message);
                        if(file.previewElement) {
                            let errorMsg = file.previewElement.querySelector('.dz-error-message');
                            if(!errorMsg) {
                                errorMsg = document.createElement('div');
                                errorMsg.classList.add('dz-error-message');
                                errorMsg.style.color = 'red';
                                errorMsg.textContent = 'Upload gagal: ' + data.message;
                                file.previewElement.appendChild(errorMsg);
                            }
                        }
                    }
                })
                .catch((err) => {
                    console.error("Error saat upload:", err);
                    if(file.previewElement) {
                        let errorMsg = file.previewElement.querySelector('.dz-error-message');
                        if(!errorMsg) {
                            errorMsg = document.createElement('div');
                            errorMsg.classList.add('dz-error-message');
                            errorMsg.style.color = 'red';
                            errorMsg.textContent = 'Terjadi error saat upload';
                            file.previewElement.appendChild(errorMsg);
                        }
                    }
                });
            });
        }
    });
});
