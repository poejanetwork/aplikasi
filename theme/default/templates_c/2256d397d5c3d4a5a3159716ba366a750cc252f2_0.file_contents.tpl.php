<?php
/* Smarty version 5.5.1, created on 2025-11-09 07:50:22
  from 'file:contents.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_690fe54e2c24b1_36343253',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2256d397d5c3d4a5a3159716ba366a750cc252f2' => 
    array (
      0 => 'contents.tpl',
      1 => 1762040577,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
))) {
function content_690fe54e2c24b1_36343253 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
$_smarty_tpl->renderSubTemplate("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
<section id="news" class="pt-5 pb-5 bg-light">
   <div class="container-xl">
     <div class="row news_1">
	    <div class="col-md-12">
        <?php echo nl2br((string) $_smarty_tpl->getValue('pagecontent'), (bool) 1);?>

      </div>
    </div>
  </div>
</section>
<?php $_smarty_tpl->renderSubTemplate("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
