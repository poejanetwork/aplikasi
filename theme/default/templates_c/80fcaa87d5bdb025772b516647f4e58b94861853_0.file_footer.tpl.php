<?php
/* Smarty version 5.5.1, created on 2026-01-27 14:58:46
  from 'file:footer.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69787036af0790_58021642',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '80fcaa87d5bdb025772b516647f4e58b94861853' => 
    array (
      0 => 'footer.tpl',
      1 => 1769500725,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69787036af0790_58021642 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
?>
 <section id="footer" class="pt-5 pb-5 bg_black">
   <div class="container-xl">
     <div class="row row-cols-1 row-cols-md-2">
	    <div class="col">
		  <div class="footer_left">
		    <b class="fs-4  d-block text-uppercase text-white center_sm"> <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/logo.png" class="me-1" width="55" height="56"> <?php echo $_smarty_tpl->getValue('settings')['APP_NAME'];?>
</b>
			<p class="gray_dark mt-3"><?php echo $_smarty_tpl->getValue('settings')['APP_DESCRIPTION'];?>
</p>
			<p class="gray_dark">We're accepting new partnerships right now.</p>
		   <ul class="mb-0 d-flex social mt-3">
		  <li><a class="d-block rounded-circle text-center text-white  link" href="<?php echo $_smarty_tpl->getValue('settings')['FACEBOOK'];?>
" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
		  <li class="ms-2"><a class="d-block rounded-circle text-center text-white link" href="<?php echo $_smarty_tpl->getValue('settings')['YOUTUBE'];?>
" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
		   <li class="ms-2"><a class="d-block rounded-circle text-center text-white link" href="<?php echo $_smarty_tpl->getValue('settings')['TWITTER'];?>
" target="_blank"><i class="fa-brands fa-x"></i></a></li>
		   <li class="ms-2"><a class="d-block rounded-circle text-center text-white link" href="<?php echo $_smarty_tpl->getValue('settings')['INSTAGRAM'];?>
" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
		</ul>
		  </div>
		</div>
		<div class="col">
		  <div class="footer_left">
		    <b class="text-uppercase text-white font_13 d-block mb-4 mt-2 center_sm">Instagram Widget</b>
            <ul class="mb-0">
			 <li class="d-flex">
			   <a href="#"><img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/10.jpg" width="80" alt="abc"></a>
			   <a class="mx-2" href="#"><img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/11.jpg" width="80" alt="abc"></a>
			   <a href="#"><img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/12.jpg" width="80" alt="abc"></a>
			 </li>
			 <li class="d-flex mt-2">
			   <a href="#"><img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/14.jpg" width="80" alt="abc"></a>
			   <a class="mx-2" href="#"><img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/15.jpg" width="80" alt="abc"></a>
			   <a href="#"><img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/24.jpg" width="80" alt="abc"></a>
			 </li>
			  <li class="mt-4 center_sm">
			   <a class="font_10 text-uppercase button_1 fw-bold d-inline-block rounded-1" href="#">Follow Our Instagram <i class="fa fa-chevron-right ms-2 font_8"></i> </a>
			 </li>
			</ul>
		 </div>
		</div>
	 </div>
   </div>
 </section>
 
 <section id="footer_bottom" class="bg-dark">
    <div class="container-fluid p-0">
     	 <div class="row mx-0">
	       <div class="col-md-2 p-0">
		     <div class="footer_bottom_left bg_blue p-4 position-relative">
			    
			 </div>
		   </div>
		   <div class="col-md-8 p-0">
		     <div class="footer_bottom_center">
			      <ul class="mb-0 text-uppercas d-flex justify-content-center font_10 text-uppercase fw-bold flex-wrap">
				   <li><a class="d-block" href="<?php echo $_smarty_tpl->getValue('settings')['BASE_URL'];?>
">Home</a></li>
				   <li><a class="d-block" href="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')('contact');?>
">Kontak</a></li>
				  </ul>
			 </div>
		   </div>
		   <div class="col-md-2 p-0">
		     <div class="footer_bottom_right bg_blue p-4 position-relative">
			    
			 </div>
		   </div>
		 </div>
	</div>
   </section>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="appToast" class="toast align-items-center text-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="toast-header">
			<strong class="me-auto" id="appToastTitle">Notifikasi</strong>
			<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
		<div class="toast-body" id="appToastBody">
			Message
		</div>
    </div>
</div>


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
