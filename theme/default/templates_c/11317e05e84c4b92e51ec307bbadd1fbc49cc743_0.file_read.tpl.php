<?php
/* Smarty version 5.5.1, created on 2026-01-27 11:02:58
  from 'file:news/read.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_697838f2823f10_66016884',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '11317e05e84c4b92e51ec307bbadd1fbc49cc743' => 
    array (
      0 => 'news/read.tpl',
      1 => 1769486576,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_697838f2823f10_66016884 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates\\news';
?> <section id="contact" class="bg-light">
   <div class="container-fluid p-0">
     <div class="row mx-0">
	       <div class="col-md-2 p-0">
		     <div class="footer_bottom_left contact_left text-xl-end p-3 position-relative">
			     <span class="text-white fs-6 fw-bold ms-5">News Detail</span>
			 </div>
		   </div>
		   <div class="col-md-10 p-0">
		     <div class="contact_righto  p-3 bg-white">
			      <span class="mb-0 font_11 light_gray"><a class="fw-bold" href="<?php echo $_smarty_tpl->getValue('settings')['BASE_URL'];?>
">Home</a> <span class="mx-2">/</span> News Detail</span>
			 </div>
		   </div>
		 </div>
   </div>
 </section>
 
 <section id="news" class="pt-5 pb-5 bg-light">
   <div class="container-xl">
     <div class="row news_1">
	  <div class="col-md-8">
	     <div class="news_dt">
		    <div class="news_dt1 text-center">
			    <a href="javascript:void(0);"><img  class="img-fluid" alt="<?php echo $_smarty_tpl->getValue('data')['title'];?>
" src="<?php echo $_smarty_tpl->getValue('data')['thumbnail'];?>
"></a>
			</div>
			<div class="news_dt2 p-4 bg-white">
			      <b class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1"><?php echo $_smarty_tpl->getValue('data')['category'];?>
</b>
					 <b class="d-block fs-3 text-uppercase mt-2 mb-1"><a href="javascript:void(0);"><?php echo $_smarty_tpl->getValue('data')['title'];?>
</a></b>
				    <ul class="mb-0 font_11 fw-bold text-uppercase justify-content-between d-flex">
		   <li>
		   <a href="javascript:void(0);"><img class="rounded-circle" alt="<?php echo $_smarty_tpl->getValue('data')['username'];?>
 Avatar" src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/9.png"></a>
		   <span class="gray_dark ms-2"><?php echo $_smarty_tpl->getValue('data')['username'];?>
</span>
		   </li>
		   <li class="my-auto">
			 <a class="gray_dark mx-3" href="javascript:void(0);" title="Tanggal posting"><i class="fa fa-calendar me-1 text-success"></i> <?php echo $_smarty_tpl->getValue('data')['created_at'];?>
</a>
			 <a class="gray_dark" href="javascript:void(0);" title="Total dibaca"><i class="fa fa-eye me-1 text-info"></i> <?php echo $_smarty_tpl->getValue('data')['views_count'];?>
</a>
		   </li>
		 </ul>
			<p class="mt-4 text-dark fs-6 text-justify"><?php echo $_smarty_tpl->getValue('data')['full_text_bbcode'];?>
</p>
			<ul class="mb-0 d-flex flex-wrap text-uppercase font_11 tags  mt-4">
				
			<?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('data')['tags'], 'weight', false, 'tag');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('tag')->value => $_smarty_tpl->getVariable('weight')->value) {
$foreach0DoElse = false;
?>
				<li class="mx-1 mt-1 mb-1"><a class="d-block border p-1 px-2 tag-size-<?php echo $_smarty_tpl->getValue('weight');?>
" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("news/tag/search?q=".((string)$_smarty_tpl->getValue('tag')));?>
'>
					<?php echo $_smarty_tpl->getValue('tag');?>

				</a></li>
			<?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
		   </ul>
			</div>
			<div class="news_dt3 mt-3">
			    <ul class="mb-0 text-uppercase fw-bold justify-content-between d-flex">
				 <li class="d-inline-block"><a href="#" class="d-inline-block bg-primary text-white rounded-1 font_11 p-3 px-5"><i class="fa-brands fa-facebook-f me-2 align-middle"></i> Share on facebook</a></li>
				  <li class="d-inline-block"><a href="#" class="d-inline-block bg-info text-white rounded-1 font_11 p-3 px-5"><i class="fa-brands fa-twitter me-2 align-middle"></i> Share on twitter</a></li>
				   <li class="d-inline-block"><a href="#" class="d-inline-block bg-danger text-white rounded-1 font_11 p-3 px-5"><i class="fa-brands fa-instagram me-2 align-middle"></i> Share on instagram</a></li>
				</ul>
			</div>
			<div class="news_dt4 p-4 bg-white mt-3">
			   <ul class="mb-0">
			     <li class="d-flex">
			   <span><a href="#"><img width="50" alt="abc" class="rounded-circle" src="image/25.jpg"></a></span>
			   <span class="flex-column ms-3">
				 <b class="d-block"><a href="#">Lorem Porta</a></b>
				 <span class="light_gray font_11 d-block  fw-bold"><i class="fa fa-user text-warning align-middle me-1"></i> User Comment</span>
			   </span>
			 </li>
			   </ul>
			   <p class="mb-0 mt-3">Esse Brute Fierent At Pri, Vim Inani Reprehendunt Cu. Torquatos Contentiones At Sed, Mea Ad Utinam Aperiam.Lorem Ipsum Dolor Sit Amet, Sonet Intellegat Deterruisset Usu At, Nec Zril Timeam In. Omnes Nostro Virtute Qui Te, Sed Ex Oblique Labitur. Maluisset Instructior An Vel, Bonorum Corpora His Id, Duo Debet Inermis Facilisis No. </p>
			</div>
			<div class="news_dt5 mt-3">
			  <div class="row row-cols-1 row-cols-md-2">
			    <div class="col">
				  <div class="news_dt5_left bg-white p-3">
				    <ul class="mb-0">
					  <li class="d-flex">
					   <span class="mt-4"><a class="d-inline-block bg-dark text-white rounded-1 p-2 px-3 font_13" href="#"><i class="fa fa-chevron-left"></i></a></span>
					   <span class="flex-column ms-1">
				 <b class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Newest</b>
				<b class="d-block font_13 text-uppercase mt-2  px-3"><a href="#">TEMPOR INCIDIDUNT UT LABORE  MAGNA ALIQUA. UT ENIM AD MINIM</a></b>
				<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i class="fa fa-clock me-1 text-warning align-middle"></i> Aug 14, 2016</span>
			   </span>
					  </li>
					</ul>
				  </div>
				</div>
				<div class="col">
				  <div class="news_dt5_left bg-white p-3">
				    <ul class="mb-0">
					  <li class="d-flex">
					   <span class="flex-column me-1">
				 <b class="d-inline-block bg_yellow text-white p-1 px-3 font_10 text-uppercase rounded-1 mx-3">Popular</b>
				<b class="d-block font_13 text-uppercase mt-2  px-3"><a href="#">TEMPOR INCIDIDUNT UT LABORE  MAGNA ALIQUA. UT ENIM AD MINIM</a></b>
				<span class="light_gray font_10 fw-bold  text-uppercase px-3"> <i class="fa fa-clock me-1 text-warning align-middle"></i> Aug 14, 2016</span>
			   </span>
			   <span class="mt-4"><a class="d-inline-block bg-black text-white rounded-1 p-2 px-3 font_13" href="#"><i class="fa fa-chevron-right"></i></a></span>
					  </li>
					</ul>
				  </div>
				</div>
			  </div>
			</div>
			<div class="news_1_right1 bg-white mt-3 border_light">
			  <b class="d-block text-uppercase p-3 border_thick">Recent Comments</b>
			  <ul class="mb-0 border-top pt-4 pb-4">
			    <li class="d-flex px-3">
			   <span><a href="#"><img width="50" class="rounded-circle" alt="abc" src="image/25.jpg"></a></span>
			   <span class="flex-column mx-2 mt-2">
			     <b class="d-block   font_14 lh-1">The Ipsum</b>
				 <span class="light_gray font_10 fw-bold  mt-1 d-block">  @ipsum</span>
			   </span>
			 </li>
			    <li class="px-3 mt-2">
			    <span class="flex-column">
				  <span class="gray_dark font_12 d-block">Lorem Ipsum Dolor Sit Amet, Sonet Intellegat Deterruisset Usu At, Nec Zril Timeam In. Omnes Nostro Virtute Qui Te, Sed Ex Oblique Labitur. Maluisset Instructior An Vel, Bonorum Corpora His Id, Duo Debet Inermis Facilisis No Ne Mei Sanctus Laoreet.</span>
				</span>
				</li>
				<li class="px-3 mt-2 font_12">
			       <span>
				     <i class="fa-brands fa-twitter text-info align-middle mt-1"></i> <span class="light_gray font_11 align-middle ms-1"> 2 hour ago</span>
				   </span>
				   <span class="float-end">
				     <a class="light_gray" href="#"><i class="fa fa-share col_yellow font_11 me-1"></i> Reply</a>
				   </span>
				</li>
			  </ul>
			  <ul class="mb-0 border-top pt-4 pb-4">
			    <li class="d-flex px-3">
			   <span><a href="#"><img width="50" class="rounded-circle" alt="abc" src="image/26.jpg"></a></span>
			   <span class="flex-column mx-2 mt-2">
			     <b class="d-block   font_14 lh-1">The Porta</b>
				 <span class="light_gray font_10 fw-bold  mt-1 d-block">  @porta</span>
			   </span>
			 </li>
			    <li class="px-3 mt-2">
			    <span class="flex-column">
				  <span class="gray_dark font_12 d-block">Lorem Ipsum Dolor Sit Amet, Sonet Intellegat Deterruisset Usu At, Nec Zril Timeam In. Omnes Nostro Virtute Qui Te, Sed Ex Oblique Labitur. Maluisset Instructior An Vel, Bonorum Corpora His Id, Duo Debet Inermis Facilisis No Ne Mei Sanctus Laoreet.</span>
				</span>
				</li>
				<li class="px-3 mt-2 font_12">
			       <span>
				     <i class="fa-brands fa-twitter text-info align-middle mt-1"></i> <span class="light_gray font_11 align-middle ms-1"> 3 hour ago</span>
				   </span>
				    <span class="float-end">
				     <a class="light_gray" href="#"><i class="fa fa-share col_yellow font_11 me-1"></i> Reply</a>
				   </span>
				</li>
			  </ul>
			  <ul class="mb-0 border-top pt-4 pb-4">
			    <li class="d-flex px-3">
			   <span><a href="#"><img width="50" class="rounded-circle" alt="abc" src="image/27.jpg"></a></span>
			   <span class="flex-column mx-2 mt-2">
			     <b class="d-block   font_14 lh-1">The Lorem</b>
				 <span class="light_gray font_10 fw-bold  mt-1 d-block">  @lorem</span>
			   </span>
			 </li>
			    <li class="px-3 mt-2">
			    <span class="flex-column">
				  <span class="gray_dark font_12 d-block">Lorem Ipsum Dolor Sit Amet, Sonet Intellegat Deterruisset Usu At, Nec Zril Timeam In. Omnes Nostro Virtute Qui Te, Sed Ex Oblique Labitur. Maluisset Instructior An Vel, Bonorum Corpora His Id, Duo Debet Inermis Facilisis No Ne Mei Sanctus Laoreet.</span>
				</span>
				</li>
				<li class="px-3 mt-2 font_12">
			       <span>
				     <i class="fa-brands fa-twitter text-info align-middle mt-1"></i> <span class="light_gray font_11 align-middle ms-1"> 4 hour ago</span>
				   </span>
				     <span class="float-end">
				     <a class="light_gray" href="#"><i class="fa fa-share col_yellow font_11 me-1"></i> Reply</a>
				   </span>
				</li>
			  </ul>
			</div>
			<div class="news_1_right1 bg-white mt-3 border_light">
			  <b class="d-block text-uppercase p-3 border_thick">Write a Comment</b>
			  <div class="pt-4 pb-4 border-top">
			   <form class="row g-3 needs-validation px-3" novalidate="">
			<div class="col-md-6">
				
				<input type="text" class="form-control font_12" id="name" value="Name" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid first name.
				</div>
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control font_12" id="email" value="Email" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid last name.
				</div>
			</div>
			<div class="col-md-6">
				
				<input type="text" class="form-control font_12" id="subject" value="Subject" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid subject.
				</div>
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control font_12" id="website" value="Website" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid website.
				</div>
			</div>
	        <div class="col-md-12">
			 <textarea id="message" value="Your Message (Maximum 300 words)" class="form-control form_text font_12" required=""></textarea>
			    <div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid message.
				</div>
			</div>
			<div class="col-12">
				<div class="form-check font_14">
					<input class="form-check-input" type="checkbox" value="" id="invalidCheck" required="">
					<label class="form-check-label" for="invalidCheck">
					  Before registration read our <a href="#">Privacy Statement</a> and <a href="#">Terms &amp; Conditions</a>
					</label>
					<div class="valid-feedback">
					  Looks good!
					</div>
					<div class="invalid-feedback">
					  You must agree before submitting.
					</div>
				</div>
			</div>
			<div class="col-12 center_sm">
			  <button class="btn btn-primary bg-dark text-white p-3 px-5 border-0 rounded-0 w-100 text-uppercase font_12 fw-bold" type="submit">Post Your Comment</button>
			</div>
		</form>
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
