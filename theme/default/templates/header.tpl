<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{$pagetitle} | {$settings.APP_NAME}</title>
	<meta name="description" content="{$settings.APP_DESCRIPTION}">
	<meta name="keywords" content="{$settings.APP_KEYWORD}">
	<link href="{$theme}/assets/css/bootstrap.min.css" rel="stylesheet" >
	<link href="{$theme}/assets/fonts/css/fontawesome.min.css" rel="stylesheet" >
	<link href="{$theme}/assets/fonts/css/brands.min.css" rel="stylesheet" />
    <link href="{$theme}/assets/fonts/css/solid.min.css" rel="stylesheet" />
	<link href="{$theme}/assets/css/global.css" rel="stylesheet">
	<link href="{$theme}/assets/css/index.css" rel="stylesheet">
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
            Language: <span class="col_blue">EN</span> <img src="{$theme}/assets/image/icons-svg/chevron-down.svg" width="10" height="10" alt="Submenu open/close icon">
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
            Currency: <span class="col_blue">Usd</span> <img src="{$theme}/assets/image/icons-svg/chevron-down.svg" width="10" height="10" alt="Submenu open/close icon">
          </a>
          <ul class="dropdown-menu drop_top shadow" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#"> Dollor</a></li>
			<li><a class="dropdown-item" href="#"> Pound</a></li>
			<li><a class="dropdown-item" href="#"> Euro</a></li>
			<li><a class="dropdown-item border-0" href="#"> Rupee</a></li>
          </ul>
        </li>
		 <li class="gray_dark mx-2">/</li>
		 <li><a class="text-white" href="#">Wishlist <span class="col_blue">7</span></a></li>
		 <li class="gray_dark mx-2">/</li>
		 <li><a class="text-white" href="#">Your Account </a></li>
		</ul>
	  </div>
	 </div>
   </div>
 </section>
 
 <section id="header_top" class="bg_black pt-3 pb-3 d-none">
   <div class="container-xl">
     <div class="row header_top_1 pt-1">
	  <div class="col-md-12">
	    <ul class="mb-0 text-uppercase font_10 d-flex">
		 <li>
		   <div class="input-group rounded-pill bg-dark px-3">
			<input type="text" class="form-control bg-transparent border-0 font_10 text-white" placeholder="Cari berita disini...">
			<span class="input-group-btn">
				<button class="btn btn-primary bg-transparent border-0 rounded-0 p-1 px-3" type="button">
					<i class="fa fa-search col_blue font_14"></i> </button>
			</span>
		</div>
		</li>
		<li class="d-flex ms-4">
				 <span class="col_blue fs-4 d-inline-block me-3">
				   <i class="fa fa-map-marker"></i>
				 </span>
				  <span class="flex-column">
				   <b class="text-white">Alamat</b>
				   <span class="gray_dark d-block">{$settings.SITE_ADDRESS}</span>
				 </span>
				</li>
		<li class="d-flex ms-4">
				 <span class="col_blue fs-4 d-inline-block me-3">
				   <i class="fa fa-envelope"></i>
				 </span>
				  <span class="flex-column">
				   <b class="text-white">Hubungi Kami</b>
				   <span class="gray_dark d-block">{$settings.SYSTEM_EMAIL}</span>
				 </span>
				</li>
				<li class="d-flex ms-4">
				 <span class="col_blue fs-4 d-inline-block me-3">
				   <i class="fa fa-phone"></i>
				 </span>
				  <span class="flex-column">
				   <b class="text-white">Phone</b>
				   <span class="gray_dark d-block">+{$settings.SITE_PHONE}</span>
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
         <a class="d-flex text-white" href="{$settings.BASE_URL}">
			 <b class="fs-4  d-block text-uppercase logo bg_black p-2 px-3"> <img src="{$theme}/assets/image/logo.png" class="me-1" width="35" height="36"> Tuanku Tambusai</b>
	     </a>
         <button class="navbar-toggler offcanvas-nav-btn  ms-auto me-3" type="button">
            <img src="{$theme}/assets/image/icons-svg/list.svg" width="40" height="40" alt="Open TemplateOnweb website menu"/>
         </button>
         <div class="offcanvas offcanvas-start offcanvas-nav" style="width: 20rem">
            <div class="offcanvas-header shadow">
			    <a class="d-flex text-white" href="{$settings.BASE_URL}">
					 <b class="fs-4  d-block text-uppercase logo"> <i class="fa-brands fa-nfc-directional col_blue me-1"></i> Tuanku Tambusai</b>
				 </a>
               <img src="{$theme}/assets/image/icons-svg/x.svg" width="40" height="40" class="ms-auto" data-bs-dismiss="offcanvas" aria-label="Close" alt="Close TemplateOnweb website menu"/>
			   
            </div>
            <div class="offcanvas-body pt-0 align-items-center">
               <ul class="navbar-nav align-items-lg-center ms-auto">
			      <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle active" href="{$settings.BASE_URL}" title="Visit home page">
					  Home
					  </a>
				  </li>
		
		          <li class="nav-item dropdown drop_border">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Norma<img src="{$theme}/assets/image/icons-svg/chevron-down.svg" width="15" height="15" alt="Submenu open/close icon">
					</a>
					<ul class="dropdown-menu drop_1 shadow" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href='{"contents/sapta-marga"|surl}'> Sapta Marga</a></li>
						<li><a class="dropdown-item" href='{"contents/sumpah-prajurit"|surl}'> Sumpah Prajurit</a></li>
						<li><a class="dropdown-item" href='{"contents/8-wajib-tni"|surl}'> 8 Wajib TNI</a></li>
						<li><a class="dropdown-item" href='{"contents/sumpah-prajurit"|surl}'> 11 Azas Kepemimpinan</a></li>
						<li><a class="dropdown-item" href='{"contents/sumpah-prajurit"|surl}'> Panca Prasetya Korpri</a></li>
						<li><a class="dropdown-item" href='{"contents/sumpah-prajurit"|surl}'> Perintah Harian Panglima TNI</a></li>
						<li><a class="dropdown-item border-0" href='{"contents/sumpah-prajurit"|surl}'> Perintah Harian Kasad</a></li>
					</ul>
					</li>

		          <li class="nav-item dropdown drop_border">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Prasaja<img src="{$theme}/assets/image/icons-svg/chevron-down.svg" width="15" height="15" alt="Submenu open/close icon">
					</a>
					<ul class="dropdown-menu drop_1 shadow" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href='{"contents/struktur-orgas"|surl}'> Struktur Orgas</a></li>
						<li><a class="dropdown-item" href='{"contents/visi-misi"|surl}'> Visi & Misi</a></li>
						<li><a class="dropdown-item border-0" href='{"contents/tugas-pokok"|surl}'> Tugas Pokok</a></li>
					</ul>
					</li>
				  
		          <li class="nav-item dropdown drop_border">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Identitas<img src="{$theme}/assets/image/icons-svg/chevron-down.svg" width="15" height="15" alt="Submenu open/close icon">
					</a>
					<ul class="dropdown-menu drop_1 shadow" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href='{"contents/lambang"|surl}'> Lambang</a></li>
						<li><a class="dropdown-item" href='{"contents/pataka"|surl}'> Pataka</a></li>
						<li><a class="dropdown-item border-0" href='{"contents/mars"|surl}'> Mars</a></li>
					</ul>
					</li>
		
				  <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle" href="{'login'|surl}" title="Masuk">
					  Masuk
					  </a>
				  </li>
				  
				  <li class="nav-item"> 
				      <a class="nav-link dropdown-toggle" href="{'contact'|surl}" title="Hubungi Kami">
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
 </section>