<?php
/* Smarty version 5.5.1, created on 2026-01-27 09:18:32
  from 'file:news/tags.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_697820784a77f1_19935206',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b6f8b064ed5095893faa92a3ac3510efe084ac2' => 
    array (
      0 => 'news/tags.tpl',
      1 => 1769480276,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_697820784a77f1_19935206 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates\\news';
?>
 <section id="contact" class="bg-light">
   <div class="container-fluid p-0">
     <div class="row mx-0">
	       <div class="col-md-2 p-0">
		     <div class="footer_bottom_left contact_left text-xl-end p-3 position-relative">
			     <span class="text-white fs-6 fw-bold ms-5">Pencarian</span>
			 </div>
		   </div>
		   <div class="col-md-10 p-0">
		     <div class="contact_righto  p-3 bg-white">
			      <span class="mb-0 font_11 light_gray"><a class="fw-bold" href="<?php echo $_smarty_tpl->getValue('settings')['BASE_URL'];?>
">Home</a> <span class="mx-2">/</span> <a class="fw-bold" href="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')('news');?>
">Berita</a></span>
			 </div>
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
                        <?php if ($_smarty_tpl->getValue('data')) {?>
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-md-1">

                        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('data'), 'item', false, NULL, 'test', array (
));
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
?>
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
                        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

                    </div>
                        <?php } else { ?>
                        
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-body p-0">
                                    <div class="text-center">
                                    Tidak ada hasil pada pencarian "<?php echo $_smarty_tpl->getValue('query');?>
"
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                </div>

            </div>
        </div>
        
	  <?php $_smarty_tpl->renderSubTemplate($_smarty_tpl->getValue('sidebar'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
?>
	 </div>
   </div>
 </section><?php }
}
