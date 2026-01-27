<?php
/* Smarty version 5.5.1, created on 2026-01-27 14:33:27
  from 'file:settings/create.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69786a4786eb74_60189632',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0a5b2148b91483630a5a7100f966320f0b021afd' => 
    array (
      0 => 'settings/create.tpl',
      1 => 1769499165,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69786a4786eb74_60189632 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates\\settings';
?><form id="formAddData">
    <div class="mb-3">
        <label for="setting_group" class="form-label">Group</label>
        <select class="form-select" id="setting_group" name="setting_group">
            <option value="news">Berita</option>
            <option value="social">Sosmed</option>
            <option value="umum">Umum</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="setting_key" class="form-label">Slug</label>
        <input type="text" id="setting_key" name="setting_key" class="form-control" placeholder="Jangan gunakan spasi atau karakter khusus" value="">
    </div>

    <div class="mb-3">
        <label for="setting_value" class="form-label">Value</label>
        <input type="text" id="setting_value" name="setting_value" class="form-control" value="">
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
