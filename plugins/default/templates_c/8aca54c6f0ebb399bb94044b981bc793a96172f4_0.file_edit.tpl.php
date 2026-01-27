<?php
/* Smarty version 5.5.1, created on 2026-01-27 15:08:36
  from 'file:user_group/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6978728402da04_00229012',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8aca54c6f0ebb399bb94044b981bc793a96172f4' => 
    array (
      0 => 'user_group/edit.tpl',
      1 => 1769501307,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6978728402da04_00229012 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\user_group';
?><form id="formEditData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
">
    <div class="mb-3">
        <label for="group_name" class="form-label">Nama Group</label>
        <input type="text" id="group_name" name="group_name" class="form-control" placeholder="Jangan gunakan spasi atau karakter khusus" value="<?php echo $_smarty_tpl->getValue('data')['group_name'];?>
">
    </div>
    
    <div class="mb-3">
        <label for="status" class="form-label">Status Group</label>
        <select class="form-select" id="statuses" name="status">
            <option value="1" <?php if ($_smarty_tpl->getValue('data')['status'] == 1) {?>selected<?php }?>>Aktif</option>
            <option value="0" <?php if ($_smarty_tpl->getValue('data')['status'] == 0) {?>selected<?php }?>>Tidak Aktif</option>
        </select>
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
