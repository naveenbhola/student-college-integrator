<?php

$data['showOTPLayer']=$showVerificationLayer;
$data['mobile']=$mobile;
?>

<div class="layer-outer" style="width:365px; position: absolute; font-family: Trebuchet MS;">
    <div class="layer-title">
		<a class="close" title="Close" id="close" style="display : none" href="#" onclick="window.location.reload(); return false;"></a>
            <div class="title" id="registrationTitle_<?php echo regFormId; ?>" style="font-size : 18px"> Verify Mobile Number</div>
	    <div class="layer-contents-Layer" id="layer-contents">  
	<?php $this->load->view('registration/common/OTP/userOtpVerification',$data); ?>
    </div>
</div> 
</div>
</div>	
