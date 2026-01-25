<?php
/* Smarty version 5.5.1, created on 2026-01-25 16:37:47
  from 'file:news_category/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6975e46bb2ddc6_42968484',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89ca99740fcad145a9ca8082c5ba5ec42c8d8429' => 
    array (
      0 => 'news_category/create.tpl',
      1 => 1769333217,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6975e46bb2ddc6_42968484 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\news_category';
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
