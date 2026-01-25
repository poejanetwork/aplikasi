<?php
/* Smarty version 5.5.1, created on 2026-01-25 22:53:57
  from 'file:doktrin_categories/edit.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69763c95b811c1_21857915',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dcdf33ddb306c2ab946ba38d98bae930285f6fcf' => 
    array (
      0 => 'doktrin_categories/edit.tpl',
      1 => 1769333202,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69763c95b811c1_21857915 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\doktrin_categories';
?><form id="formEditData" data-id="<?php echo $_smarty_tpl->getValue('data')['id'];?>
">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Kategori</label>
        <input type="text" id="name" name="name" class="form-control" value="<?php echo $_smarty_tpl->getValue('data')['name'];?>
">
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Alamat Slug</label>
        <input type="text" id="slug" name="slug" class="form-control" placeholder="Kosongkan jika tidak ada" value="<?php echo $_smarty_tpl->getValue('data')['slug'];?>
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
