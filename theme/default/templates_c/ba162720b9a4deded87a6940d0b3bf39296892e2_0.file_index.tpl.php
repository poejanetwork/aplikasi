<?php
/* Smarty version 5.5.1, created on 2026-01-26 22:13:20
  from 'file:index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69778490a23700_86747144',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ba162720b9a4deded87a6940d0b3bf39296892e2' => 
    array (
      0 => 'index.tpl',
      1 => 1769440363,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
))) {
function content_69778490a23700_86747144 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
$_smarty_tpl->renderSubTemplate("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>

<?php if ((true && ($_smarty_tpl->hasVariable('content') && null !== ($_smarty_tpl->getValue('content') ?? null)))) {?>
    <?php $_smarty_tpl->renderSubTemplate($_smarty_tpl->getValue('content'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
} else { ?>
    <p>Content not found</p>
<?php }?>

<?php $_smarty_tpl->renderSubTemplate("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
