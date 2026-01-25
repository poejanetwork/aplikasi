<?php
/* Smarty version 5.5.1, created on 2026-01-25 19:26:32
  from 'file:doktrin_categories/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69760bf811e787_67543425',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '84435d25dd7d3a523270ce9542a7993670487740' => 
    array (
      0 => 'doktrin_categories/create.tpl',
      1 => 1769333217,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69760bf811e787_67543425 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\doktrin_categories';
?><form id="formAddData">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Kategori</label>
        <input type="text" id="name" name="name" class="form-control" value="">
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Alamat Slug</label>
        <input type="text" id="slug" name="slug" class="form-control" placeholder="Kosongkan jika tidak ada" value="">
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
