<?php
/* Smarty version 5.5.1, created on 2026-01-18 15:14:08
  from 'file:surat_masuk/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_696c96502b2260_71652210',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cea6e5a20a4696704b509bbbba4254cdb8cf1f9f' => 
    array (
      0 => 'surat_masuk/create.tpl',
      1 => 1768722255,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_696c96502b2260_71652210 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\surat_masuk';
?><form id="formAddData" enctype="multipart/form-data">
    
    <div class="mb-3">
        <label for="asal_surat" class="form-label">Asal Surat</label>
        <input type="text" id="asal_surat" name="asal_surat" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="nomor_surat" class="form-label">Nomor Surat</label>
        <input type="text" id="nomor_surat" name="nomor_surat" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="perihal" class="form-label">Perihal</label>
        <input type="text" id="perihal" name="perihal" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
        <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label for="tanggal_terima" class="form-label">Tanggal Surat Diterima</label>
        <input type="date" id="tanggal_terima" name="tanggal_terima" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Lembar Disposisi</label>

        <div class="row">
            <div class="col-6 col-lg-6 col-md-6">
                <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('disposisiLeft'), 'd');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('d')->value) {
$foreach0DoElse = false;
?>
                    <div class="form-check form-checkbox-secondary my-1">
                        <input class="form-check-input"
                            type="checkbox"
                            name="disposisi[]"
                            value="<?php echo $_smarty_tpl->getValue('d')['id'];?>
"
                            id="disposisi<?php echo $_smarty_tpl->getValue('d')['id'];?>
">
                        <label for="disposisi<?php echo $_smarty_tpl->getValue('d')['id'];?>
" class="form-check-label">
                            <?php echo $_smarty_tpl->getValue('d')['nama'];?>

                        </label>
                    </div>
                <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </div>

            <div class="col-6 col-lg-6 col-md-6">
                <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('disposisiRight'), 'd');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('d')->value) {
$foreach1DoElse = false;
?>
                    <div class="form-check form-checkbox-secondary my-1">
                        <input class="form-check-input"
                            type="checkbox"
                            name="disposisi[]"
                            value="<?php echo $_smarty_tpl->getValue('d')['id'];?>
"
                            id="disposisi<?php echo $_smarty_tpl->getValue('d')['id'];?>
">
                        <label for="disposisi<?php echo $_smarty_tpl->getValue('d')['id'];?>
" class="form-check-label">
                            <?php echo $_smarty_tpl->getValue('d')['nama'];?>

                        </label>
                    </div>
                <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </div>
        </div>

    </div>

    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" class="form-control" value="">
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
</form><?php }
}
