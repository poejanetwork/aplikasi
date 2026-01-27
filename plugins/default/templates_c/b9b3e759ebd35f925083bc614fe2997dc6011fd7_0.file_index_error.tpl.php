<?php
/* Smarty version 5.5.1, created on 2026-01-27 15:17:37
  from 'file:index_error.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_697874a117a7b1_59096719',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b9b3e759ebd35f925083bc614fe2997dc6011fd7' => 
    array (
      0 => 'index_error.tpl',
      1 => 1768532343,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
))) {
function content_697874a117a7b1_59096719 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\plugins\\default\\templates';
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
