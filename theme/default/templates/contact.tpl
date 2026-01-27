
 <section id="contact" class="bg-light pb-5">
   <div class="container-fluid p-0">
     <div class="row mx-0">
	       <div class="col-md-2 p-0">
		     <div class="footer_bottom_left contact_left text-xl-end p-3 position-relative">
			     <span class="text-white fs-6 fw-bold ms-5">Contact Us</span>
			 </div>
		   </div>
		   <div class="col-md-10 p-0">
		     <div class="contact_righto  p-3 bg-white">
			      <span class="mb-0 font_11 light_gray"><a class="fw-bold" href="#">Home</a> <span class="mx-2">/</span> Contact Us</span>
			 </div>
		   </div>
		 </div>
		 <div class="row mx-0 contact_1">
	       <div class="col-md-12 p-0">
		     <div class="contact_1_inner">
                {$settings.GOOGLE_MAP}
			 </div>
		   </div>
		 </div>
		 <div class="row row-cols-1 row-cols-md-1 mx-0 w-75 mx-auto contact_2">
		   <div class="col p-0">
		    <div class="contact_right bg-white px-4 pt-5 pb-5">
			  <form id="contactForm" class="row g-3 needs-validation p-2" novalidate="">
                {csrf}
			<div class="col-md-12">
				
				<input type="text" class="form-control font_13" name="name" id="name" placeholder="Name" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid name.
				</div>
			</div>
			<div class="col-md-12">
				
				<input type="email" class="form-control font_13" name="email" id="email" placeholder="Email" required="">
				<div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid email.
				</div>
			</div>
			
			<div class="col-md-12">
				
			<select class="form-select font_13 light_gray" name="subject" id="select_box" aria-label="Default select example">
				<option selected="Masalah Umum">Masalah Umum</option>
				<option value="Lainnya">Masalah Lain</option>
			</select>
			</div>
			
	        <div class="col-md-12">
			 <textarea name="message" id="message" placeholder="Your Message" class="form-control form_text font_13" required=""></textarea>
			    <div class="valid-feedback">
				  Looks good!
				</div>
				<div class="invalid-feedback">
				  Please provide a valid message.
				</div>
			</div>
			<div class="col-12 center_sm">
			  <button class="btn btn-primary button p-3 px-4 border-0 rounded-0 w-100 font_13 fw-bold" type="submit">Send Message</button>
			</div>
		</form>
			</div>
		   </div>
		 </div>
		 <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 contact_3  mx-auto mt-5">
	       <div class="col px-2">
		    <div class="contact_3_left bg-white p-4 text-center">
			  <span class="d-inline-block bg-light text-center rounded-circle fs-4 icon"><i class="fa fa-question"></i></span>
			  <b class="font_13 d-block mt-3"><a href="#">General Inquiries</a></b>
			  <span class="d-block font_12 mt-3 mb-1">{$settings.SITE_ADDRESS}</span>
			</div>
		   </div>
		   <div class="col px-2">
		    <div class="contact_3_left bg-white p-4 text-center">
			  <span class="d-inline-block bg-light text-center rounded-circle fs-4 icon"><i class="fa fa-user"></i></span>
			  <b class="font_13 d-block mt-3"><a href="#">Join Our Team</a></b>
			  <span class="d-block font_12 mt-3 mb-1">Lorem Ipsum </span>
			</div>
		   </div>
		   <div class="col px-2">
		    <div class="contact_3_left bg-white p-4 text-center">
			  <span class="d-inline-block bg-light text-center rounded-circle fs-4 icon"><i class="fa fa-camera"></i></span>
			  <b class="font_13 d-block mt-3"><a href="#">Press and Media</a></b>
			  <span class="d-block font_12 mt-3 mb-1">Lorem Ipsum </span>
			</div>
		   </div>
		 </div>
   </div>
 </section>