<?php
/* Smarty version 5.5.1, created on 2026-01-18 19:53:19
  from 'file:disposisi/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_696cd7bf23d075_52974549',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '53d9839970379131bd3bfeefc7463924553734e5' => 
    array (
      0 => 'disposisi/create.tpl',
      1 => 1768740189,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_696cd7bf23d075_52974549 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\disposisi';
?><form id="formAddData">
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" id="nama" name="nama" class="form-control">
    </div>
    <div class="mb-3">
        <label for="urutan" class="form-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="form-control">
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
