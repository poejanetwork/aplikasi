 <section id="contact" class="bg-light pb-5">
   <div class="container-fluid p-0">
     <div class="row mx-0">
	       <div class="col-md-2 p-0">
		     <div class="footer_bottom_left contact_left text-xl-end p-3 position-relative">
			     <span class="text-white fs-6 fw-bold ms-5">Login</span>
			 </div>
		   </div>
		   <div class="col-md-10 p-0">
		     <div class="contact_righto  p-3 bg-white">
			      <span class="mb-0 font_11 light_gray"><a class="fw-bold" href="#">Home</a> <span class="mx-2">/</span> Login</span>
			 </div>
		   </div>
		 </div>
		 <div class="row row-cols-1 row-cols-md-2 mx-0 w-75 mx-auto contact_2">
		   <div class="col p-0">
		    <div class="contact_right bg-white px-4 pt-5 pb-5">
			  <form id="formLogin" class="row g-3 needs-validation p-2" novalidate="">
				{csrf}
			<div class="col-md-12">
				<input name="email" type="text" class="form-control font_13" id="last" placeholder="Username" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid last name.
				</div>
			</div>
			<div class="col-md-12">
				
				<input name="password" type="password" class="form-control font_13" id="password" placeholder="Kata Sandi" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid password.
				</div>
			</div>
			
			<div class="col-12 center_sm">
				<input type="hidden" name="act" value="do_login">
				<button class="btn btn-primary button p-3 px-4 border-0 rounded-0 w-100 font_13 fw-bold" type="submit">Masuk</button>
			</div>
		</form>
			</div>
		   </div>
		 </div>
   </div>
 </section>
