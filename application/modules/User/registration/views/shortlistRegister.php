<div id="reg-layer-outer" class="layer-outer" style="width:610px; font-family: Trebuchet MS; padding: 0; display: none;">
    <div class="layer-title" style="padding:10px 15px 15px; background:#e6e6e6; margin-bottom:5px;">
		<a class="close" title="Close" id="close" href="javascript:void(0);" onclick="shikshaUserRegistration.closeRegisterFreeLayer(); return false;"></a>
        <div class="title" id="registrationTitle_<?php echo $regFormId; ?>"><span class="reg-star-box" style="margin-right:10px !important; padding: 2px !important; width:23px"><i class="common-sprite register-star-icon"></i></span><?php echo $layerTitle ? $layerTitle : "Join now for free";?></div>
    </div>
    <div class="layer-contents" id="layer-contents" style="padding:10px 15px 15px;">
		<div style="margin-bottom:12px;">
			<div style="float:left; font-size:14px;"><?php echo isset($layerHeading) ? $layerHeading : "New Users, Register Free!"; ?></div>
			<?php if(!($userData['userId'] > 0)) { ?>
				<div style="float:right;"><a onclick="shikshaUserRegistration.closeRegisterFreeLayer(); shikshaUserRegistration.showLoginLayer(); return false;" href="javascript:void(0);">Already registered?</a></div>
			<?php } ?>
			<div class="clearFix"></div>
		</div>
		<div class="registration-left-col flLt">
			<?php echo Modules::run('registration/Forms/LDB',NULL,'shortlistRegister',$formCustomData); ?>
		</div>
		<div class="registration-right-col flRt">
			<div class="reg-shortlist-title">Shortlist to Make an Informed College Decision </div>
			<div class="shortlist-sec">
				<div class="shortlist-col">
					<i class="common-sprite placmnt-data-icon"></i>
					<div class="shortlist-details">
						<strong>Find Placment Data </strong>
						<p>See where students end up after graduating</p>
					</div>
				</div>
				<div class="shortlist-col">
					<i class="common-sprite read-rvw-icon"></i>
					<div class="shortlist-details">
						<strong>Read College Reviews</strong>
						<p>See how alumni rate their college</p>
					</div>
				</div>
				<div class="shortlist-col">
					<i class="common-sprite ask-current-icon"></i>
					<div class="shortlist-details">
						<strong>Ask Current Students</strong>
						<p>Get answer from current students</p>
					</div>
				</div>
				<div class="shortlist-col last">
					<i class="common-sprite get-alert-icon"></i>
					<div class="shortlist-details">
						<strong>Get Alerts </strong>
						<p>Never miss a deadline on your target colleges</p>
					</div>
				</div>
			</div>
		</div>
		<div class="clearFix"></div>
		
		
	</div>
	<div style="padding: 0 0 15px 15px;">
		<?php $this->load->view('registration/common/OTP/userOtpVerification'); ?>
	</div>
</div>
