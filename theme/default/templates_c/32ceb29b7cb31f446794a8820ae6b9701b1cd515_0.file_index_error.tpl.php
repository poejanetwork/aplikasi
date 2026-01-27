<?php
/* Smarty version 5.5.1, created on 2026-01-27 11:46:48
  from 'file:index_error.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_6978433819c396_85311744',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '32ceb29b7cb31f446794a8820ae6b9701b1cd515' => 
    array (
      0 => 'index_error.tpl',
      1 => 1769489206,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6978433819c396_85311744 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $_smarty_tpl->getValue('pagetitle');?>
 | <?php echo $_smarty_tpl->getValue('settings')['APP_NAME'];?>
</title>
	<link href="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/css/bootstrap.min.css" rel="stylesheet" >
	 <link href="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/fonts/css/fontawesome.min.css" rel="stylesheet" >
	<link href="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/fonts/css/brands.min.css" rel="stylesheet" />
    <link href="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/fonts/css/solid.min.css" rel="stylesheet" />
	<link href="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/css/global.css" rel="stylesheet">
	<link href="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/css/index.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

</head>
<body>
    
<?php if ((true && ($_smarty_tpl->hasVariable('content') && null !== ($_smarty_tpl->getValue('content') ?? null)))) {?>
    <?php $_smarty_tpl->renderSubTemplate($_smarty_tpl->getValue('content'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
} else { ?>
    <p>Content not found</p>
<?php }?>

<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/js/bootstrap.bundle.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/js/theme.min.js"><?php echo '</script'; ?>
>
<?php if ((true && ($_smarty_tpl->hasVariable('page_js') && null !== ($_smarty_tpl->getValue('page_js') ?? null)))) {
echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/js/pages/<?php echo $_smarty_tpl->getValue('page_js');?>
"><?php echo '</script'; ?>
>
<?php }?>

</body>
</html><?php }
}
