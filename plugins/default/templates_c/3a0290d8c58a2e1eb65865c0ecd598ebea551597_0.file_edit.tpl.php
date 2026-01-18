<?php
/* Smarty version 5.5.1, created on 2026-01-16 15:31:39
  from 'file:surat_masuk/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6969f76bf27170_62948513',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3a0290d8c58a2e1eb65865c0ecd598ebea551597' => 
    array (
      0 => 'surat_masuk/edit.tpl',
      1 => 1768552262,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6969f76bf27170_62948513 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\surat_masuk';
?><form id="formEditData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
">
    <div class="mb-3">
        <label for="asal_surat" class="form-label">Asal Surat</label>
        <input type="text" id="asal_surat" name="asal_surat" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['asal_surat'];?>
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
        <label for="tanggal_terima" class="form-label">Tanggal Surat Diterima</label>
        <input type="date" id="tanggal_terima" name="tanggal_terima" class="form-control" value="<?php if ($_smarty_tpl->getValue('data')['tanggal_terima']) {
echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')($_smarty_tpl->getValue('data')['tanggal_terima'],"%Y-%m-%d");
}?>">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Disposisi</label>
        <input type="text" id="keterangan" name="keterangan" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['keterangan'];?>
">
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" class="btn btn-primary">
            Simpan Data
        </button>
    </div>
</form><?php }
}
