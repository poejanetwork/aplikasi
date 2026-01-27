<?php
/* Smarty version 5.5.1, created on 2026-01-27 14:27:44
  from 'file:home.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_697868f088ac19_44284614',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3e0dee64723bdaa95cdd5a0a120bddda22732835' => 
    array (
      0 => 'home.tpl',
      1 => 1769498729,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_697868f088ac19_44284614 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
if ($_smarty_tpl->getSmarty()->getModifierCallback('count')($_smarty_tpl->getValue('data')) > 0) {?>
<section id="center" class="center_home">
	<div id="carouselExampleCaptions" class="carousels slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('data'), 'item', false, NULL, 'test', array (
  'iteration' => true,
));
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_test']->value['iteration']++;
?>
				<?php if (($_smarty_tpl->getValue('__smarty_foreach_test')['iteration'] ?? null) == 1) {?>
				<img src="<?php echo $_smarty_tpl->getValue('item')['thumbnail'];?>
" class="d-block img-fluid w-100" alt="<?php echo $_smarty_tpl->getValue('item')['title'];?>
">
				<div class="carousel-caption d-md-block text-center">
					<b class="text-white d-block mt-2 text-uppercase font_14"><?php echo $_smarty_tpl->getValue('item')['title'];?>
</b>
					<h1 class="mt-2 mb-3 text-uppercase font_60">Popular <span class="col_yellow">News</span></h1>
					<h6 class="mb-0 mt-4"><a class="button" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("news/read/".((string)$_smarty_tpl->getValue('item')['id'])."/".((string)$_smarty_tpl->getValue('item')['slug']));?>
'>Read More
							<i class="fa fa-arrow-right ms-2 col_yellow"></i></a></h6>
				</div>
				<?php }?>
				<?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
			</div>

		</div>
	</div>
</section>
<?php }?>

<section id="news" class="pt-5 pb-5 bg-light">
	<div class="container-xl">
		<div class="row news_1">
			<div class="col-md-8">
				<div class="news_1_left">

					<div class="news_1_left3">
						<div class="row row-cols-1 row-cols-lg-2 row-cols-md-1">

							<?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('data'), 'item', false, NULL, 'test', array (
  'iteration' => true,
));
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach1DoElse = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_test']->value['iteration']++;
?>
							<?php if (($_smarty_tpl->getValue('__smarty_foreach_test')['iteration'] ?? null) > 1) {?>
							<div class="col">
								<div class="card mb-3">
									<div class="card-body p-0">
										<div class="blog_1 position-relative">
											<div class="blog_1_inner_top"> <a
													href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("news/read/".((string)$_smarty_tpl->getValue('item')['id'])."/".((string)$_smarty_tpl->getValue('item')['slug']));?>
'><img
														src="<?php echo $_smarty_tpl->getValue('item')['thumbnail'];?>
" class="img-fluid"
														alt="<?php echo $_smarty_tpl->getValue('item')['title'];?>
"></a></div>
											<div class="blog_1_inner position-absolute top-0 p-3">
												<b
													class="d-inline-block bg_blue text-white p-1 px-3 font_10 text-uppercase rounded-1"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('ucfirst')($_smarty_tpl->getValue('item')['category']);?>
</b>
											</div>
											<div class="blog_1_inner_1 position-absolute text-end w-100 p-3">
												<ul class="mb-0">

													<li class="d-block fs-6 mt-2"><a
															class="d-block bg-info text-white  rounded-circle text-center icon_1 icon_2"
															href="<?php echo $_smarty_tpl->getValue('settings')['FACEBOOK'];?>
" target="_blank"><i
																class="fa-brands fa-facebook-f"></i></a></li>
													<li class="d-block fs-6 mt-2"><a
															class="d-block bg-danger text-white  rounded-circle text-center icon_1 icon_2"
															href="<?php echo $_smarty_tpl->getValue('settings')['TWITTER'];?>
" target="_blank"><i
																class="fa-brands fa-twitter"></i></a></li>
													<li class="d-block fs-6 mt-2"><a
															class="d-block bg_violet text-white  rounded-circle text-center icon_1 icon_2"
															href="<?php echo $_smarty_tpl->getValue('settings')['INSTAGRAM'];?>
" target="_blank"><i
																class="fa-brands fa-instagram"></i></a></li>
													<li class="d-block fs-6 mt-2"><a
															class="d-block bg_yellow text-white  rounded-circle text-center icon_1"
															href="<?php echo $_smarty_tpl->getValue('settings')['YOUTUBE'];?>
" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
												</ul>
											</div>
										</div>
										<div class="blog_2 pt-3 pb-3">
											<span
												class="light_gray font_12 fw-bold px-3 text-uppercase"><?php echo $_smarty_tpl->getValue('item')['created_at'];?>
</span>
											<b class="d-block font_15 text-uppercase mt-2 px-3"><a
													href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("news/read/".((string)$_smarty_tpl->getValue('item')['id'])."/".((string)$_smarty_tpl->getValue('item')['slug']));?>
'><?php echo $_smarty_tpl->getValue('item')['title'];?>
</a></b>
											<hr>
											<ul
												class="mb-0 px-3 font_11 fw-bold text-uppercase justify-content-between d-flex">
												<li>
													<a href="javascript:void(0)"><img class="rounded-circle" alt="Avatar"
															src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/9.png"></a>
													<span class="light_gray ms-2"><?php echo $_smarty_tpl->getValue('item')['username'];?>
</span>
												</li>
											</ul>
										</div>

									</div>
								</div>
							</div>
							<?php }?>
							<?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

						</div>
					</div>

				</div>
			</div>

			<?php $_smarty_tpl->renderSubTemplate($_smarty_tpl->getValue('sidebar'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
		</div>
	</div>
</section><?php }
}
