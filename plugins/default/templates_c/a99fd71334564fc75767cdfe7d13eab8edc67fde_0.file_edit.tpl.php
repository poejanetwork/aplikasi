<?php
/* Smarty version 5.5.1, created on 2026-01-24 14:22:21
  from 'file:surat_keluar/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6974732d016426_72839329',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a99fd71334564fc75767cdfe7d13eab8edc67fde' => 
    array (
      0 => 'surat_keluar/edit.tpl',
      1 => 1769218264,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6974732d016426_72839329 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\surat_keluar';
?><form id="formEditData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
" enctype="multipart/form-data">
    
    <div class="mb-3">
        <label for="tujuan_surat" class="form-label">Tujuan Surat</label>
        <input type="text" id="tujuan_surat" name="tujuan_surat" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['tujuan_surat'];?>
">
    </div>

    <div class="mb-3">
        <label for="nomor_surat" class="form-label">Nomor Surat</label>
        <input type="text" id="nomor_surat" name="nomor_surat" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['nomor_surat'];?>
">
    </div>

    <div class="mb-3">
        <label for="perihal" class="form-label">Perihal</label>
        <input type="text" id="perihal" name="perihal" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['perihal'];?>
">
    </div>

    <div class="mb-3">
        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
        <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control" value="<?php if ($_smarty_tpl->getValue('data')['tanggal_surat']) {
echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')($_smarty_tpl->getValue('data')['tanggal_surat'],"%Y-%m-%d");
}?>">
    </div>

    <div class="mb-3">
        <label for="tanggal_kirim" class="form-label">Tanggal Surat Dikirim</label>
        <input type="date" id="tanggal_kirim" name="tanggal_kirim" class="form-control" value="<?php if ($_smarty_tpl->getValue('data')['tanggal_kirim']) {
echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')($_smarty_tpl->getValue('data')['tanggal_kirim'],"%Y-%m-%d");
}?>">
    </div>

    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['keterangan'];?>
">
    </div>

    <div class="mb-3">
        <label class="form-label">Upload File<br/><small>(jangan upload jika tidak mengganti)</small></label>
        <input class="form-control" type="file"
            name="file_surat"
            accept=".pdf,.doc,.docx,image/*"
            capture="environment">
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
