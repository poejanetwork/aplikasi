<?php
/* Smarty version 5.5.1, created on 2025-11-02 06:43:00
  from 'file:custom_pages.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69069b04cf2201_27802010',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b3849bec4977285ed11df17aa7368eeec1e78c36' => 
    array (
      0 => 'custom_pages.tpl',
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
function content_69069b04cf2201_27802010 (\Smarty\Template $_smarty_tpl) {
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
