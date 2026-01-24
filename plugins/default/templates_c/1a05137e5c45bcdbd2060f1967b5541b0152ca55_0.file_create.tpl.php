<?php
/* Smarty version 5.5.1, created on 2026-01-24 11:28:16
  from 'file:news/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69744a60c39770_15104222',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1a05137e5c45bcdbd2060f1967b5541b0152ca55' => 
    array (
      0 => 'news/create.tpl',
      1 => 1769228895,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69744a60c39770_15104222 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\news';
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
				<h4 class="fs-18 text-uppercase fw-bold m-0"><?php echo $_smarty_tpl->getValue('pagename');?>
</h4>
			</div>
		</div><!-- end card header -->
	</div>
	<!--end col-->
</div> <!-- end row-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title"><?php echo $_smarty_tpl->getValue('pagename');?>
</h4>
            </div>

            <div class="card-body">
                <form id="formAddData" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Berita</label>
                        <input type="text" id="title" name="title" class="form-control" value="">
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Kategori Berita</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('category'), 'd');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('d')->value) {
$foreach0DoElse = false;
?>
                            <option value="<?php echo $_smarty_tpl->getValue('d')['id'];?>
"><?php echo $_smarty_tpl->getValue('d')['nama'];?>
</option>
                            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="full_text" class="form-label">Isi Berita</label>
                        <textarea name="full_text" class="form-control" id="editor" rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="created_at" class="form-label">Tanggal Berita</label>
                        <input type="date" id="created_at" name="created_at" class="form-control" value="">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input class="form-control" type="file"
                            name="file_surat"
                            accept=".pdf,.doc,.docx,image/*"
                            capture="environment"
                            required>
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
</div><?php }
}
