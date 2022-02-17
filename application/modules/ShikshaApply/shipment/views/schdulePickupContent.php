<div class="start-container">
<div class="extrn-wrap">
     <h1 class="color-3 f24 f-semi">Shiksha DHL Express<p class="color-9 f12 f-nrml">All fields are mandatory</p></h1>
    <div class="schedul-wrap">
      <div class="cmn-col">
	    <?php
			$this->load->view('widgets/pickupDetails');
			$this->load->view('widgets/destinationDetails');
		  ?>
		    </div>
       <!--bottom col-->
       <div class="fot-col">
           <div class="right-dat">
              <div class="col">
                <div id="packagePrice" style="display:none">
                <p class="color-3 f18 f-bold"><H2 class="color-3 f18 f-bold" style="display:inline">Price:Rs.899/-</H2><span class="f-nrml f12"> (Inclusive of all taxes ) </span></p> 
                   
                </div>
                <div id="packagePriceDefault">
                  <p class="color-3 f18 f-bold"  >Price: <span class="f12 f-nrml">Please click on "Check Price and Availability" button on the top to get the price details.</span></p>
                </div>
                <a class="book-btn f-bold" id = "submitShippingInformation">Book your pickup</a>
              </div>
               <p class="color-9 f12">Payment amount will be collected by DHL person at the time of pickup</p>
           </div>
       </div>
    </div>
</div>
</div>
<div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('//<?php echo IMGURL; ?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>
<div style="margin:8px 0 20px 10px;display:none" id="errorLayer">

  <div id="errorMessage"></div>
  <div style="margin-top:10px"> In case you are not able to resolve the above error, you can take screenshot of this window and contact us on studyabroad@shiksha.com</div>
  <a onclick="hideAbroadOverlay();" href="JavaScript:void(0);" class="button-style big-button" style="font-size:16px; margin-top:15px; padding:5px 30px;">OK</a>


</div>