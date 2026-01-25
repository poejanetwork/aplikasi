<?php
/* Smarty version 5.5.1, created on 2026-01-25 21:46:16
  from 'file:doktrin/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69762cb8412ab2_62438321',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca2011d0c2ddcce1e6a786c6623a7f1f9ff1355f' => 
    array (
      0 => 'doktrin/edit.tpl',
      1 => 1769351425,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69762cb8412ab2_62438321 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\doktrin';
?><div class="row">
	<div class="col-12">
		<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
			<div class="flex-grow-1">
				<h4 class="fs-18 text-uppercase fw-bold m-0"><?php echo $_smarty_tpl->getValue('pagetitle');?>
</h4>
			</div>
            <div class="text-end"><a href="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')('doktrin');?>
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
                    <input type="hidden" name="storage_type" id="storage_type" value="<?php echo $_smarty_tpl->getValue('data')['storage_type'];?>
">
                    <input type="hidden" name="file_name" id="file_name" value="<?php echo $_smarty_tpl->getValue('data')['file_name'];?>
">
                    <input type="hidden" name="file_size" id="file_size" value="<?php echo $_smarty_tpl->getValue('data')['file_size'];?>
">
                    <input type="hidden" name="file_type" id="file_type" value="<?php echo $_smarty_tpl->getValue('data')['file_type'];?>
">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Judul Data</label>
                        <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['nama'];?>
">
                    </div>

                    <div class="mb-3">
                        <label for="doktrin_id" class="form-label">Kategori Data</label>
                        <select class="form-select" id="doktrin_id" name="doktrin_id">
                            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('categories'), 'item');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
?>
                            <option value="<?php echo $_smarty_tpl->getValue('item')['id'];?>
" data-slug="<?php echo $_smarty_tpl->getValue('item')['slug'];?>
" <?php if ($_smarty_tpl->getValue('item')['id'] == $_smarty_tpl->getValue('data')['doktrin_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->getValue('item')['name'];?>
</option>
                            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun Data</label>
                        <input type="number" id="tahun" name="tahun" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['tahun'];?>
">
                    </div>

                    <div class="mb-3">
                        <label for="file_path" class="form-label">Link URL File</label>
                        <input type="text" id="file_path" name="file_path" class="form-control" placeholder="Masukkan link google drive atau gunakan uploader dibawah" value="<?php echo $_smarty_tpl->getValue('data')['file_path'];?>
">
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
                <h4 class="header-title">Gunakan untuk mengupload file data</h4>
            </div>

            <div class="card-body">
                <form action="/" method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-imgtype="default" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                    
                    <div class="dz-message needsclick">
                        <i class="ti ti-cloud-upload h1 text-muted"></i>
                        <h3>Jatuhkan files disini atau klik untuk upload.</h3>
                        <span class="text-muted fs-13">(File akan di upload di server dan akan
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
        uploadUrl: '<?php echo $_smarty_tpl->getValue('uploadConfig')['uploadUrlFile'];?>
',
        uploadFolder: 'doktrin',
        jenis: ''
    },
    getUrl: '<?php echo $_smarty_tpl->getValue('BASE_URL');
echo $_smarty_tpl->getValue('ADMIN_URL');?>
'
};
function updateJenisFromSelect() {
    const select = document.getElementById('doktrin_id');
    if (!select) return;

    const opt = select.options[select.selectedIndex];
    if (!opt) return;
    const slug = opt.dataset.slug || opt.getAttribute('data-slug') || '';
    window.APP.UPLOAD_CONFIG.jenis = slug;
}
document.addEventListener('DOMContentLoaded', function () {
    updateJenisFromSelect();
    const select = document.getElementById('doktrin_id');
    if (select) {
        select.addEventListener('change', updateJenisFromSelect);
    }
});
window.addEventListener('beforeunload', function () {
    rollbackUploadedFile();
});
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
