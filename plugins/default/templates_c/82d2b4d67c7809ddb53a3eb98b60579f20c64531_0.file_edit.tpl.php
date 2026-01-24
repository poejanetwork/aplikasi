<?php
/* Smarty version 5.5.1, created on 2026-01-18 20:13:26
  from 'file:disposisi/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_696cdc763c4bd0_88338826',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '82d2b4d67c7809ddb53a3eb98b60579f20c64531' => 
    array (
      0 => 'disposisi/edit.tpl',
      1 => 1768740162,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_696cdc763c4bd0_88338826 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\disposisi';
?><form id="formEditData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
">
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['nama'];?>
">
    </div>
    <div class="mb-3">
        <label for="urutan" class="form-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['urutan'];?>
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
</form><?php }
}
