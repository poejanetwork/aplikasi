<?php
/* Smarty version 5.5.1, created on 2026-01-24 08:03:55
  from 'file:header.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.1',
  'unifunc' => 'content_69741a7b80f0e0_78513356',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '60c034b32f3f18d3f0ea5f1b6bd62fa1f2aee732' => 
    array (
      0 => 'header.tpl',
      1 => 1769216633,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69741a7b80f0e0_78513356 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\xampp\\htdocs\\apli.kasi\\theme\\default\\templates';
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $_smarty_tpl->getValue('settings')['sitename'];?>
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

 <section id="top" class="bg-dark pt-3 pb-3 d-none">
   <div class="container-xl">
     <div class="row top_1">
	  <div class="col-md-12">
	    <ul class="mb-0 text-uppercase font_10 d-flex justify-content-end">
		 <li><a class="gray_dark" href="#">Logout</a></li>
		 <li class="gray_dark mx-2">/</li>
		 <li class="nav-item dropdown">
          <a class="dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Language: <span class="col_yellow">EN</span> <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/chevron-down.svg" width="10" height="10" alt="Submenu open/close icon">
          </a>
          <ul class="dropdown-menu drop_top shadow" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#"> Hindi</a></li>
			<li><a class="dropdown-item" href="#"> English</a></li>
			<li><a class="dropdown-item" href="#"> German</a></li>
			<li><a class="dropdown-item border-0" href="#"> Spanish</a></li>
          </ul>
        </li>
		<li class="gray_dark mx-2">/</li>
		 <li class="nav-item dropdown">
          <a class="dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Currency: <span class="col_yellow">Usd</span> <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/chevron-down.svg" width="10" height="10" alt="Submenu open/close icon">
          </a>
          <ul class="dropdown-menu drop_top shadow" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#"> Dollor</a></li>
			<li><a class="dropdown-item" href="#"> Pound</a></li>
			<li><a class="dropdown-item" href="#"> Euro</a></li>
			<li><a class="dropdown-item border-0" href="#"> Rupee</a></li>
          </ul>
        </li>
		 <li class="gray_dark mx-2">/</li>
		 <li><a class="text-white" href="#">Wishlist <span class="col_yellow">7</span></a></li>
		 <li class="gray_dark mx-2">/</li>
		 <li><a class="text-white" href="#">Your Account </a></li>
		</ul>
	  </div>
	 </div>
   </div>
 </section>
 
 <section id="header_top" class="bg_black pt-3 pb-3">
   <div class="container-xl">
     <div class="row header_top_1 pt-1">
	  <div class="col-md-12">
	    <ul class="mb-0 text-uppercase font_10 d-flex">
		 <li>
		   <div class="input-group rounded-pill bg-dark px-3">
			<input type="text" class="form-control bg-transparent border-0 font_10 text-white" placeholder="Cari berita disini...">
			<span class="input-group-btn">
				<button class="btn btn-primary bg-transparent border-0 rounded-0 p-1 px-3" type="button">
					<i class="fa fa-search col_yellow font_14"></i> </button>
			</span>
		</div>
		</li>
		<li class="d-flex ms-4">
				 <span class="col_yellow fs-4 d-inline-block me-3">
				   <i class="fa fa-map-marker"></i>
				 </span>
				  <span class="flex-column">
				   <b class="text-white">Alamat</b>
				   <span class="gray_dark d-block"><?php echo $_smarty_tpl->getValue('settings')['siteaddress'];?>
</span>
				 </span>
				</li>
		<li class="d-flex ms-4">
				 <span class="col_yellow fs-4 d-inline-block me-3">
				   <i class="fa fa-envelope"></i>
				 </span>
				  <span class="flex-column">
				   <b class="text-white">Hubungi Kami</b>
				   <span class="gray_dark d-block"><?php echo $_smarty_tpl->getValue('settings')['system_email'];?>
</span>
				 </span>
				</li>
				<li class="d-flex ms-4">
				 <span class="col_yellow fs-4 d-inline-block me-3">
				   <i class="fa fa-phone"></i>
				 </span>
				  <span class="flex-column">
				   <b class="text-white">Phone</b>
				   <span class="gray_dark d-block">+ (123) 124-567-xxxx</span>
				 </span>
				</li>
		</ul>
	  </div>
	 </div>
   </div>
 </section>

 <section id="header">
        <nav class="navbar navbar-expand-lg navbar-light w-100">
      <div class="container-xl">
         <a class="d-flex text-white" href="<?php echo $_smarty_tpl->getValue('settings')['siteurl'];?>
">
			 <b class="fs-4  d-block text-uppercase logo bg_black p-2 px-3"> <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/logo.png" class="me-1" width="35" height="36"> Tuanku Tambusai</b>
	     </a>
         <button class="navbar-toggler offcanvas-nav-btn  ms-auto me-3" type="button">
            <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/list.svg" width="40" height="40" alt="Open TemplateOnweb website menu"/>
         </button>
         <div class="offcanvas offcanvas-start offcanvas-nav" style="width: 20rem">
            <div class="offcanvas-header shadow">
			    <a class="d-flex text-white" href="<?php echo $_smarty_tpl->getValue('settings')['siteurl'];?>
">
					 <b class="fs-4  d-block text-uppercase logo"> <i class="fa-brands fa-nfc-directional col_yellow me-1"></i> Tuanku Tambusai</b>
				 </a>
               <img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/x.svg" width="40" height="40" class="ms-auto" data-bs-dismiss="offcanvas" aria-label="Close" alt="Close TemplateOnweb website menu"/>
			   
            </div>
            <div class="offcanvas-body pt-0 align-items-center">
               <ul class="navbar-nav align-items-lg-center ms-auto">
			      <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle active" href="<?php echo $_smarty_tpl->getValue('settings')['siteurl'];?>
" title="Visit home page">
					  Home
					  </a>
				  </li>
		
		          <li class="nav-item dropdown drop_border">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Norma<img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/chevron-down.svg" width="15" height="15" alt="Submenu open/close icon">
					</a>
					<ul class="dropdown-menu drop_1 shadow" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=contents&1");?>
'> Sapta Marga</a></li>
						<li><a class="dropdown-item" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=contents&2");?>
'> Sumpah Prajurit</a></li>
						<li><a class="dropdown-item" href='#'> 8 Wajib TNI</a></li>
						<li><a class="dropdown-item" href='#'> 11 Azas Kepemimpinan</a></li>
						<li><a class="dropdown-item" href='#'> Panca Prasetya Korpri</a></li>
						<li><a class="dropdown-item" href='#'> Perintah Harian Panglima TNI</a></li>
						<li><a class="dropdown-item border-0" href='#'> Perintah Harian Kasad</a></li>
					</ul>
					</li>

		          <li class="nav-item dropdown drop_border">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Prasaja<img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/chevron-down.svg" width="15" height="15" alt="Submenu open/close icon">
					</a>
					<ul class="dropdown-menu drop_1 shadow" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=custom_pages&1");?>
'> Struktur Orgas</a></li>
						<li><a class="dropdown-item" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=custom_pages&2");?>
'> Visi & Misi</a></li>
						<li><a class="dropdown-item border-0" href='#'> Tugas Pokok</a></li>
					</ul>
					</li>
				  
		          <li class="nav-item dropdown drop_border">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Identitas<img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/chevron-down.svg" width="15" height="15" alt="Submenu open/close icon">
					</a>
					<ul class="dropdown-menu drop_1 shadow" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=custom_pages&1");?>
'> Lambang</a></li>
						<li><a class="dropdown-item" href='<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('surl')("?p=custom_pages&2");?>
'> Pataka</a></li>
						<li><a class="dropdown-item border-0" href='#'> Mars</a></li>
					</ul>
					</li>
		
		          <li class="nav-item dropdown drop_border">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            News<img src="<?php echo $_smarty_tpl->getValue('theme');?>
/assets/image/icons-svg/chevron-down.svg" width="15" height="15" alt="Submenu open/close icon">
          </a>
          <ul class="dropdown-menu drop_1 shadow" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="news.html"> News</a></li>
			<li><a class="dropdown-item border-0" href="news_detail.html"> News Detail</a></li>
          </ul>
        </li>
				  
				  <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle" href="login" title="Visit home page">
					  Contact Us
					  </a>
				  </li>
               </ul>
			   <ul class="navbar-nav align-items-lg-center ms-auto social_nav">
			        <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle px-3" href="#" title="Visit home page">
					    <i class="fa-brands fa-facebook-f"></i>
					  </a>
				  </li>
				  <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle px-3" href="#" title="Visit home page">
					    <i class="fa-brands fa-twitter"></i>
					  </a>
				  </li>
				  <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle col_yellow px-3" href="#" title="Visit home page">
					    <i class="fa-brands fa-instagram"></i>
					  </a>
				  </li>
                 </ul>
            </div>
         </div>    
         </div>
      </div>
   </nav>
 </section><?php }
}
