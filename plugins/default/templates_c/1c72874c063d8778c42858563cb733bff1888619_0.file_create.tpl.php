<?php
/* Smarty version 5.5.1, created on 2026-01-16 14:16:35
  from 'file:users/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6969e5d3bae243_11812029',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1c72874c063d8778c42858563cb733bff1888619' => 
    array (
      0 => 'users/create.tpl',
      1 => 1768547677,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6969e5d3bae243_11812029 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\users';
?><form id="formAddUser">
    <div class="mb-3">
        <label for="fullname" class="form-label">Nama Lengkap</label>
        <input type="text" id="fullname" name="fullname" class="form-control" value="" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" id="email" name="email" class="form-control" value="" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="text" id="password" name="password" class="form-control" value="" required>
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
