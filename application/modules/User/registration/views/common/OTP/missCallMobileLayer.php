<div class="SgUp-lyr MCWaitingLayer" data-enhance="false" style="display: none">
	<div class="head">
		<p class="head-title misd-call-waiting-back"><a href="javascript:void(0);" data-rel="back"><i class="bck-arrw bck"></i> Back</a></p>
		<div class="clear"></div>
	</div>
	<div>
		<div class="outer_block">
		  <div class="middle_block">
		    <div class="inner_cell">
		    <div class="misd-call-vfy-waiting">
		    	<p class="confrm_call">Waiting for verification</p>
			      <p class="text_call">The call is being placed for verification. You may wait for few seconds to complete verification process and try again if it fails.</p>
			      <a class="reg-btn usr-vfy-fail  disabled-bg"  href="tel:<?php echo $missedCallNumber;?>">Try again in <span id = "misdCallTimeCounter"></span> seconds</a>
		    </div>

		    <div class="misd-call-vfy-failed" style="display: none">
		    	<p class="failed_call">Oops! The mobile number verification failed</p>
		      	<p class="text_call">Ensure you have given missed call from your mobile number (<?php echo '+'.$isdCode.'- '.$mobile; ?>)</p>
		   		<a class="reg-btn usr-vfy-fail misd-call-vfy-retry" href="tel:<?php echo $missedCallNumber;?>">Try again</a>
		    </div>
		    </div>
		  </div>
		</div>
	</div>
</div>