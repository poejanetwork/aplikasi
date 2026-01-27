<?php
/* Smarty version 5.5.1, created on 2026-01-27 15:31:23
  from 'file:user_privilege/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_697877db504d01_89967769',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '618dff8ce7a1515d94263390191b330ededfac41' => 
    array (
      0 => 'user_privilege/edit.tpl',
      1 => 1769502678,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_697877db504d01_89967769 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\user_privilege';
?><form id="formEditData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
">
    <div class="mb-3">
        <label for="user_group_id" class="form-label">Nama Group</label>
        <select class="form-select" id="user_group_id" name="user_group_id">
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('userGroupList'), 'value', false, 'key');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach0DoElse = false;
?>
            <option value="<?php echo $_smarty_tpl->getValue('value')['id'];?>
" <?php if ($_smarty_tpl->getValue('value')['id'] == $_smarty_tpl->getValue('data')['user_group_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->getValue('value')['group_name'];?>
</option>
            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="module_name" class="form-label">Module</label>
        <select class="form-select" id="module_name" name="module_name">
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('moduleList'), 'value', false, 'key');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('key')->value => $_smarty_tpl->getVariable('value')->value) {
$foreach1DoElse = false;
?>
            <option value="<?php echo $_smarty_tpl->getValue('value');?>
" <?php if ($_smarty_tpl->getValue('value') == $_smarty_tpl->getValue('data')['module_name']) {?>selected<?php }?>><?php echo $_smarty_tpl->getValue('value');?>
</option>
            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </select>
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
