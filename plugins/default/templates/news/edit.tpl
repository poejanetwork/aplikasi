<link rel="stylesheet" href="/plugins/sceditor/minified/themes/default.min.css" />
<script src="/plugins/sceditor/minified/sceditor.min.js"></script>
<script src="/plugins/sceditor/minified/formats/bbcode.min.js"></script>
<div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0">{$pagetitle}</h4>
			</div>
            <div class="text-end"><a href="{'news'|surl}" class="btn btn-sm btn-outline-dark"><i class="ti ti-arrow-left align-middle me-1"></i> kembali</a></div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">{$pagetitle}</h4>
            </div>

            <div class="card-body">
                <form id="formEditData" data-id="{$data.id}" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Berita</label>
                        <input type="text" id="title" name="title" class="form-control" value="{$data.title}">
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Kategori Berita</label>
                        <select class="form-select" id="category_id" name="category_id">
                            {foreach $categories as $item}
                            <option value="{$item.id}" {if $item.id == $data.category_id}selected{/if}>{$item.name}</option>
                            {/foreach}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="full_text" class="form-label">Isi Berita</label>
                        <textarea name="full_text" class="form-control" id="editor" rows="5">{$data.full_text_bbcode}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="created_at" class="form-label">Tanggal Berita</label>
                        <input type="date" id="created_at" name="created_at" class="form-control" value="{if $data.created_at}{$data.created_at|date_format:"%Y-%m-%d"}{/if}">
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div>

        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">Gunakan untuk mengupload file gambar</h4>
            </div>

            <div class="card-body">
                <form action="/" method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-imgtype="default" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                    
                    <div class="dz-message needsclick">
                        <i class="ti ti-cloud-upload h1 text-muted"></i>
                        <h3>Jatuhkan files disini atau klik untuk upload.</h3>
                        <span class="text-muted fs-13">(Gambar akan di upload di server dan akan
                            <strong>tampil</strong> dibawah.)</span>
                    </div>
                </form>

                <!-- Preview -->
                <div class="dropzone-previews mt-3" id="file-previews"></div>
            </div>
            <!-- file preview template -->
            <div class="d-none" id="uploadPreviewTemplate">
                <div class="card mt-1 mb-0 shadow-none border">
                    <div class="p-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                            </div>
                            <div class="col ps-0">
                                <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                <p class="mb-0" data-dz-size></p>
                            </div>
                            <div class="col-auto">
                                <!-- Button -->
                                <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                    <i class="ti ti-x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        
    </div>
</div>
<script>
window.APP = {
    UPLOAD_CONFIG: {
        place: '{$uploadConfig.place}',
        key: '{$uploadConfig.key}',
        uploadUrl: '{$uploadConfig.uploadUrlImg}',
        uploadFolder: 'images',
        uploadType: 'image',
        jenis: ''
    },
    getUrl: '{$BASE_URL}{$ADMIN_URL}'
};
</script>
<script type="module">
import { initDropzone } from '/plugins/default/assets/js/dropzone-upload.js';

document.addEventListener('DOMContentLoaded', () => {
    initDropzone('#myAwesomeDropzone');
});
</script>