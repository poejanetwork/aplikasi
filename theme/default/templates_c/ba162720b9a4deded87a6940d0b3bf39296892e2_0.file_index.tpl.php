<?php
/* Smarty version 5.5.1, created on 2025-11-09 08:02:48
  from 'file:index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_690fe838aab9e9_77772462',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ba162720b9a4deded87a6940d0b3bf39296892e2' => 
    array (
      0 => 'index.tpl',
      1 => 1762650158,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
))) {
function content_690fe838aab9e9_77772462 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
$_smarty_tpl->renderSubTemplate("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
<section id="center" class="center_home">
	<div id="carouselExampleCaptions" class="carousels slide" data-bs-ride="carousel">
		<div class="carousel-inner">	
			<div class="carousel-item active">
				<?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('allNews'), 'item', false, NULL, 'test', array (
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
					<h6 class="mb-0 mt-4"><a class="button" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=news&read=".((string)$_smarty_tpl->getValue('item')['id'])."&".((string)$_smarty_tpl->getValue('item')['slug']));?>
'>Read More <i
								class="fa fa-arrow-right ms-2 col_yellow"></i></a></h6>
				</div>
				<?php }?>
				<?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
			</div>

		</div>
	</div>
</section>

<section id="news" class="pt-5 pb-5 bg-light">
	<div class="container-xl">
		<div class="row news_1">
			<div class="col-md-8">
				<div class="news_1_left">

					<div class="news_1_left3">
						<div class="row row-cols-1 row-cols-lg-2 row-cols-md-1">
							
							<?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('allNews'), 'item', false, NULL, 'test', array (
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
											<div class="blog_1_inner_top"> <a href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=news&read=".((string)$_smarty_tpl->getValue('item')['id'])."&".((string)$_smarty_tpl->getValue('item')['slug']));?>
'><img
														src="<?php echo $_smarty_tpl->getValue('item')['thumbnail'];?>
" class="img-fluid"
														alt="<?php echo $_smarty_tpl->getValue('item')['title'];?>
"></a></div>
											<div class="blog_1_inner position-absolute top-0 p-3">
												<b
													class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('ucfirst')($_smarty_tpl->getValue('item')['category']);?>
</b>
											</div>
											<div class="blog_1_inner_1 position-absolute text-end w-100 p-3">
												<ul class="mb-0">

													<li class="d-block fs-6 mt-2"><a
															class="d-block bg-info text-white  rounded-circle text-center icon_1 icon_2"
															href="news_detail.html"><i
																class="fa-brands fa-facebook-f"></i></a></li>
													<li class="d-block fs-6 mt-2"><a
															class="d-block bg-danger text-white  rounded-circle text-center icon_1 icon_2"
															href="news_detail.html"><i
																class="fa-brands fa-twitter"></i></a></li>
													<li class="d-block fs-6 mt-2"><a
															class="d-block bg_violet text-white  rounded-circle text-center icon_1 icon_2"
															href="news_detail.html"><i
																class="fa-brands fa-instagram"></i></a></li>
													<li class="d-block fs-6 mt-2"><a
															class="d-block bg_yellow text-white  rounded-circle text-center icon_1"
															href="news_detail.html"><i class="fa fa-plus"></i></a></li>
												</ul>
											</div>
										</div>
										<div class="blog_2 pt-3 pb-3">
											<span class="light_gray font_12 fw-bold px-3 text-uppercase"><?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')($_smarty_tpl->getValue('item')['created_at'],"%b %e, %Y");?>
</span>
											<b class="d-block font_15 text-uppercase mt-2 px-3"><a
													href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=news&read=".((string)$_smarty_tpl->getValue('item')['id'])."&".((string)$_smarty_tpl->getValue('item')['slug']));?>
'><?php echo $_smarty_tpl->getValue('item')['title'];?>
</a></b>
											<hr>
											<ul
												class="mb-0 px-3 font_11 fw-bold text-uppercase justify-content-between d-flex">
												<li>
													<a href="javascript:void(0)"><img class="rounded-circle" alt="abc"
															src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/8.jpg"></a>
													<span class="light_gray ms-2">Admin</span>
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

			<div class="col-md-4">
				<div class="news_1_right">
					<div class="news_1_right1">
						<ul class="mb-0 bg_violet d-flex justify-content-between">
							<li><a class="bg_violet_dark social_icon d-inline-block text-center text-white"
									href="news_detail.html"><i class="fa-brands fa-facebook-f"></i></a></li>
							<li class="lh-1 pt-3 text-uppercase">
								<a class="text-white" href="news_detail.html">
									<span class="font_13">
										<b>Like Our Facebook Page </b><br>
										<span class="font_11">86500 Likes</span>
									</span>
								</a>
							</li>
							<li class="pt-3 pe-3"><a
									class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
									href="news_detail.html"><i class="fa-brands fa-plus"></i></a></li>
						</ul>
						<ul class="mb-0 bg-primary d-flex justify-content-between mt-3">
							<li><a class="bg_primary_dark social_icon d-inline-block text-center text-white"
									href="news_detail.html"><i class="fa-brands fa-twitter"></i></a></li>
							<li class="lh-1 pt-3 text-uppercase">
								<a class="text-white" href="news_detail.html">
									<span class="font_13">
										<b>Follow us twitter Page </b><br>
										<span class="font_11">58500 Followers</span>
									</span>
								</a>
							</li>
							<li class="pt-3 pe-3"><a
									class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
									href="news_detail.html"><i class="fa-brands fa-plus"></i></a></li>
						</ul>
						<ul class="mb-0 bg_yellow d-flex justify-content-between mt-3">
							<li><a class="bg_warning_dark social_icon d-inline-block text-center text-white"
									href="news_detail.html"><i class="fa fa-rss"></i></a></li>
							<li class="lh-1 pt-3 text-uppercase">
								<a class="text-white" href="news_detail.html">
									<span class="font_13">
										<b>Subscribe to our rss </b><br>
										<span class="font_11">585 Subscribers</span>
									</span>
								</a>
							</li>
							<li class="pt-3 pe-3"><a
									class="rounded-circle plus_icon rounded-circle text-white d-inline-block font_14 text-center"
									href="news_detail.html"><i class="fa-brands fa-plus"></i></a></li>
						</ul>
					</div>
					<div class="news_1_right1 bg-white mt-3 border_light">
						<b class="d-block text-uppercase p-3 border_thick">Popular News</b>
						<ul class="mb-0 border-top pt-3">
							<li class="d-flex border-bottom  pb-3 mb-3">
								<span class="ps-3"><a href="news_detail.html"><img width="70" alt="abc"
											src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/20.jpg"></a></span>
								<span class="flex-column mx-3">
									<b
										class="d-inline-block bg_violet text-white p-1 px-3 font_10 text-uppercase rounded-1">Latest</b>
									<b class="d-block font_13 text-uppercase mt-1"><a href="news_detail.html">TEMPOR
											INCIDIDUNT UT LABORE UT ENIM AD MINIM</a></b>
									<span class="light_gray font_10 fw-bold  text-uppercase"> <i
											class="fa fa-clock me-1 text-warning align-middle"></i> Aug 13, 2016</span>
								</span>
							</li>
							<li class="d-flex border-bottom pb-3 mb-3">
								<span class="ps-3"><a href="news_detail.html"><img width="70" alt="abc"
											src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/21.jpg"></a></span>
								<span class="flex-column mx-3">
									<b
										class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1">Popular</b>
									<b class="d-block font_13 text-uppercase mt-1"><a
											href="news_detail.html">CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD</a></b>
									<span class="light_gray font_10 fw-bold  text-uppercase"> <i
											class="fa fa-clock me-1 text-warning align-middle"></i> Aug 14, 2016</span>
								</span>
							</li>
							<li class="d-flex border-bottom pb-3 mb-3">
								<span class="ps-3"><a href="news_detail.html"><img width="70" alt="abc"
											src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/22.jpg"></a></span>
								<span class="flex-column mx-3">
									<b
										class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1">Latest</b>
									<b class="d-block font_13 text-uppercase mt-1"><a href="news_detail.html">TEMPOR
											INCIDIDUNT UT LABORE UT ENIM AD MINIM</a></b>
									<span class="light_gray font_10 fw-bold  text-uppercase"> <i
											class="fa fa-clock me-1 text-warning align-middle"></i> Aug 15, 2016</span>
								</span>
							</li>
							<li class="d-flex pb-3">
								<span class="ps-3"><a href="news_detail.html"><img width="70" alt="abc"
											src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/23.jpg"></a></span>
								<span class="flex-column mx-3">
									<b
										class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1">Popular</b>
									<b class="d-block font_13 text-uppercase mt-1"><a
											href="news_detail.html">CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD</a></b>
									<span class="light_gray font_10 fw-bold  text-uppercase"> <i
											class="fa fa-clock me-1 text-warning align-middle"></i> Aug 17, 2016</span>
								</span>
							</li>
						</ul>
					</div>
					<div class="news_1_right1 bg-white mt-3 border_light">
						<b class="d-block text-uppercase p-3 border_thick">Trending News</b>
						<div class="news_1_left1_inner_right">
							<ul
								class="nav nav-tabs mb-0 fw-bold font_11 border-top border-bottom pt-1 pb-1 justify-content-around">
								<li class="nav-item d-inline-block">
									<a href="#profile8" data-bs-toggle="tab" aria-expanded="false"
										class="nav-link active text-center">
										<span class="d-md-block text-uppercase">Newest</span>
									</a>
								</li>
								<li class="nav-item d-inline-block">
									<a href="#profile9" data-bs-toggle="tab" aria-expanded="true"
										class="nav-link text-center">
										<span class="d-md-block text-uppercase">Most Commented</span>
									</a>
								</li>
								<li class="nav-item d-inline-block">
									<a href="#profile10" data-bs-toggle="tab" aria-expanded="true"
										class="nav-link border-end-0 text-center">
										<span class="d-md-block text-uppercase">Popular</span>
									</a>
								</li>

							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane active" id="profile8">
								<div class="profile8_inner">
									<ul class="mb-0 mt-3">
										<li class="border-bottom pb-3 mb-3">
											<b
												class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Newest</b>
											<b class="d-block font_15 text-uppercase mt-2  px-3"><a
													href="news_detail.html">TEMPOR INCIDIDUNT UT LABORE MAGNA ALIQUA. UT
													ENIM AD MINIM</a></b>
											<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
													class="fa fa-clock me-1 text-warning align-middle"></i> Aug 13,
												2016</span>

											<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Praesent sapien
												massa, convallis a semper pellentesque nec, egestas non nisi. </span>
										</li>
										<li class="border-bottom pb-3 mb-3">
											<b
												class="d-inline-block bg_orange text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Newest</b>
											<b class="d-block font_15 text-uppercase mt-2  px-3"><a
													href="news_detail.html"> SIT AMET, CONSECTETUR ADIPISCING ELIT, SED
													DO EIUSMOD</a></b>
											<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
													class="fa fa-clock me-1 text-warning align-middle"></i> Aug 14,
												2016</span>

											<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Donec sollicitudin
												molestie malesuada. Mauris blandit aliquet elit"</span>
										</li>
										<li class="pb-3">
											<b
												class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Newest</b>
											<b class="d-block font_15 text-uppercase mt-2  px-3"><a
													href="news_detail.html">TEMPOR INCIDIDUNT UT LABORE MAGNA ALIQUA. UT
													ENIM AD MINIM</a></b>
											<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
													class="fa fa-clock me-1 text-warning align-middle"></i> Aug 16,
												2016</span>

											<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Praesent sapien
												massa, convallis a semper pellentesque nec, egestas non nisi. </span>
										</li>
									</ul>
								</div>
							</div>
							<div class="tab-pane" id="profile9">
								<div class="profile8_inner">
									<ul class="mb-0 mt-3">
										<li class="border-bottom pb-3 mb-3">
											<b
												class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Commented</b>
											<b class="d-block font_15 text-uppercase mt-2  px-3"><a
													href="news_detail.html">TEMPOR INCIDIDUNT UT LABORE MAGNA ALIQUA. UT
													ENIM AD MINIM</a></b>
											<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
													class="fa fa-clock me-1 text-warning align-middle"></i> Aug 13,
												2016</span>

											<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Praesent sapien
												massa, convallis a semper pellentesque nec, egestas non nisi. </span>
										</li>
										<li class="border-bottom pb-3 mb-3">
											<b
												class="d-inline-block bg_orange text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Commented</b>
											<b class="d-block font_15 text-uppercase mt-2  px-3"><a
													href="news_detail.html"> SIT AMET, CONSECTETUR ADIPISCING ELIT, SED
													DO EIUSMOD</a></b>
											<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
													class="fa fa-clock me-1 text-warning align-middle"></i> Aug 14,
												2016</span>

											<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Donec sollicitudin
												molestie malesuada. Mauris blandit aliquet elit"</span>
										</li>
										<li class="pb-3">
											<b
												class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Commented</b>
											<b class="d-block font_15 text-uppercase mt-2  px-3"><a
													href="news_detail.html">TEMPOR INCIDIDUNT UT LABORE MAGNA ALIQUA. UT
													ENIM AD MINIM</a></b>
											<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
													class="fa fa-clock me-1 text-warning align-middle"></i> Aug 16,
												2016</span>

											<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Praesent sapien
												massa, convallis a semper pellentesque nec, egestas non nisi. </span>
										</li>
									</ul>
								</div>
							</div>
							<div class="tab-pane" id="profile10">
								<ul class="mb-0 mt-3">
									<li class="border-bottom pb-3 mb-3">
										<b
											class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Popular</b>
										<b class="d-block font_15 text-uppercase mt-2  px-3"><a
												href="news_detail.html">TEMPOR INCIDIDUNT UT LABORE MAGNA ALIQUA. UT
												ENIM AD MINIM</a></b>
										<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
												class="fa fa-clock me-1 text-warning align-middle"></i> Aug 13,
											2016</span>

										<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Praesent sapien massa,
											convallis a semper pellentesque nec, egestas non nisi. </span>
									</li>
									<li class="border-bottom pb-3 mb-3">
										<b
											class="d-inline-block bg_orange text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Popular</b>
										<b class="d-block font_15 text-uppercase mt-2  px-3"><a href="news_detail.html">
												SIT AMET, CONSECTETUR ADIPISCING ELIT, SED DO EIUSMOD</a></b>
										<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
												class="fa fa-clock me-1 text-warning align-middle"></i> Aug 14,
											2016</span>

										<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Donec sollicitudin
											molestie malesuada. Mauris blandit aliquet elit"</span>
									</li>
									<li class="pb-3">
										<b
											class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Popular</b>
										<b class="d-block font_15 text-uppercase mt-2  px-3"><a
												href="news_detail.html">TEMPOR INCIDIDUNT UT LABORE MAGNA ALIQUA. UT
												ENIM AD MINIM</a></b>
										<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i
												class="fa fa-clock me-1 text-warning align-middle"></i> Aug 16,
											2016</span>

										<span class="gray_dark mt-3 px-3 font_12 mb-0 d-block">Praesent sapien massa,
											convallis a semper pellentesque nec, egestas non nisi. </span>
									</li>
								</ul>
							</div>

						</div>
					</div>
					<div class="news_1_right1 bg-white mt-3 border_light pb-3">
						<b class="d-block text-uppercase p-3 border_thick">Popular tags</b>
						<ul class="mb-0 d-flex flex-wrap text-uppercase font_11 tags border-top px-3 pt-3">
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">News</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">Headlines</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">Politics</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">Travel</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">Event</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">lorem</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">porta</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">ipsum</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">eget</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">nulla</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">semper</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">quis</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">popular</a></li>
							<li class="mx-1 mt-1 mb-1"><a class="d-block border p-2 px-3"
									href="news_detail.html">trending</a></li>
						</ul>
					</div>
					<div class="news_1_right1 bg-white mt-3 border_light pb-4">
						<b class="d-block text-uppercase p-3 border_thick">Our Newsletter</b>
						<b class="px-3 text-uppercase font_11 border-top pt-3 d-block">Subscribe Now!</b>
						<p class="px-3 mt-2 mb-3">Aliqm Lorem Ante, Dapibus In, Viverra Feugiat Phasellus.</p>
						<div class="input-group px-3">
							<input type="text" class="form-control font_11" placeholder="Your Email address...">
							<span class="input-group-btn">
								<button class="btn btn-primary bg-dark border-0 rounded-0 p-3 px-4 font_11"
									type="button">
									SEND </button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $_smarty_tpl->renderSubTemplate("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
