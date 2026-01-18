<?php
/* Smarty version 5.5.1, created on 2026-01-16 14:21:43
  from 'file:users/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6969e707df2b40_77917746',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '106e2942399f579df92635eaf269a8ea5eefade3' => 
    array (
      0 => 'users/edit.tpl',
      1 => 1768548022,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6969e707df2b40_77917746 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\users';
?><form id="formEditUser" data-id="<?php echo $_smarty_tpl->getValue('user')['id'];?>
">
    <div class="mb-3">
        <label for="fullname" class="form-label">Nama Lengkap</label>
        <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo $_smarty_tpl->getValue('user')['fullname'];?>
">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" id="email" name="email" class="form-control" value="<?php echo $_smarty_tpl->getValue('user')['email'];?>
">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="text" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
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
