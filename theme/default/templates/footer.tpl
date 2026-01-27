
 <section id="footer" class="pt-5 pb-5 bg_black">
   <div class="container-xl">
     <div class="row row-cols-1 row-cols-md-2">
	    <div class="col">
		  <div class="footer_left">
		    <b class="fs-4  d-block text-uppercase text-white center_sm"> <img src="{$theme}/assets/image/logo.png" class="me-1" width="55" height="56"> {$settings.APP_NAME}</b>
			<p class="gray_dark mt-3">{$settings.APP_DESCRIPTION}</p>
			<p class="gray_dark">We're accepting new partnerships right now.</p>
		   <ul class="mb-0 d-flex social mt-3">
		  <li><a class="d-block rounded-circle text-center text-white  link" href="{$settings.FACEBOOK}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
		  <li class="ms-2"><a class="d-block rounded-circle text-center text-white link" href="{$settings.YOUTUBE}" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
		   <li class="ms-2"><a class="d-block rounded-circle text-center text-white link" href="{$settings.TWITTER}" target="_blank"><i class="fa-brands fa-x"></i></a></li>
		   <li class="ms-2"><a class="d-block rounded-circle text-center text-white link" href="{$settings.INSTAGRAM}" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
		</ul>
		  </div>
		</div>
		<div class="col">
		  <div class="footer_left">
		    <b class="text-uppercase text-white font_13 d-block mb-4 mt-2 center_sm">Instagram Widget</b>
            <ul class="mb-0">
			 <li class="d-flex">
			   <a href="#"><img src="{$theme}/assets/image/10.jpg" width="80" alt="abc"></a>
			   <a class="mx-2" href="#"><img src="{$theme}/assets/image/11.jpg" width="80" alt="abc"></a>
			   <a href="#"><img src="{$theme}/assets/image/12.jpg" width="80" alt="abc"></a>
			 </li>
			 <li class="d-flex mt-2">
			   <a href="#"><img src="{$theme}/assets/image/14.jpg" width="80" alt="abc"></a>
			   <a class="mx-2" href="#"><img src="{$theme}/assets/image/15.jpg" width="80" alt="abc"></a>
			   <a href="#"><img src="{$theme}/assets/image/24.jpg" width="80" alt="abc"></a>
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
				   <li><a class="d-block" href="{$settings.BASE_URL}">Home</a></li>
				   <li><a class="d-block" href="{'contact'|surl}">Kontak</a></li>
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


<script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
<script src="{$theme}/assets/js/bootstrap.bundle.min.js"></script>
<script src="{$theme}/assets/js/theme.min.js"></script>
{if isset($page_js)}
<script src="{$theme}/assets/js/pages/{$page_js}"></script>
{/if}

</body>
</html>