<?php
/* Smarty version 5.5.1, created on 2026-01-27 12:38:41
  from 'file:contents/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69784f61c50ee4_06812773',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fe3474b0835620323b4d161ac7dfdd78cecfb4d3' => 
    array (
      0 => 'contents/edit.tpl',
      1 => 1769492300,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69784f61c50ee4_06812773 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\contents';
?><link rel="stylesheet" href="/plugins/sceditor/minified/themes/default.min.css" />
<?php echo '<script'; ?>
 src="/plugins/sceditor/minified/sceditor.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/plugins/sceditor/minified/formats/bbcode.min.js"><?php echo '</script'; ?>
>
<div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0"><?php echo $_smarty_tpl->getValue('pagetitle');?>
</h4>
			</div>
            <div class="text-end"><a href="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')('contents');?>
" class="btn btn-sm btn-outline-dark"><i class="ti ti-arrow-left align-middle me-1"></i> kembali</a></div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title"><?php echo $_smarty_tpl->getValue('pagetitle');?>
</h4>
            </div>

            <div class="card-body">
                <form id="formEditData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Judul Konten</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['name'];?>
">
                    </div>

                    <div class="mb-3">
                        <label for="full_text" class="form-label">Isi Konten</label>
                        <textarea name="full_text" class="form-control" id="editor" rows="5"><?php echo $_smarty_tpl->getValue('data')['page_content_bbcode'];?>
</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status Konten</label>
                        <select class="form-select" id="statuses" name="status">
                            <option value="1" <?php if ($_smarty_tpl->getValue('data')['status'] == 1) {?>selected<?php }?>>Aktif</option>
                            <option value="0" <?php if ($_smarty_tpl->getValue('data')['status'] == 0) {?>selected<?php }?>>Tidak Aktif</option>
                        </select>
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
<?php echo '<script'; ?>
>
window.APP = {
    UPLOAD_CONFIG: {
        place: '<?php echo $_smarty_tpl->getValue('uploadConfig')['place'];?>
',
        key: '<?php echo $_smarty_tpl->getValue('uploadConfig')['key'];?>
',
        uploadUrl: '<?php echo $_smarty_tpl->getValue('uploadConfig')['uploadUrlImg'];?>
',
        uploadFolder: 'images',
        uploadType: 'image',
        jenis: ''
    },
    getUrl: '<?php echo $_smarty_tpl->getValue('BASE_URL');
echo $_smarty_tpl->getValue('ADMIN_URL');?>
'
};
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="module">
import { initDropzone } from '/plugins/default/assets/js/dropzone-upload.js';

document.addEventListener('DOMContentLoaded', () => {
    initDropzone('#myAwesomeDropzone');
});
<?php echo '</script'; ?>
><?php }
}
