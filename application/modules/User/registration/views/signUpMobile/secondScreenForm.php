<div class="SgUp-lyr reg-pp" data-enhance="false">
	
	<div class="head">
		<p class="head-title"><a href="javascript:void(0);" data-rel="back"><i class="bck-arrw bck"></i> Back</a></p>
		<?php if(!$customFormData['callbackFunctionParams']['AMP_FLAG']){?>
		<a href="javascript:void(0);" class="lyr-cls bck bck2 pg2cross" onclick="window.history.go(-2);" data-direction="reverse">&times;</a>
		<?php }?>
		<div class="clear"></div>
	</div>

	<div class="popup_layer_reg" id="reg_comm_popup" style="display:none">
		<div class="hlp-popup_reg nopadng <?php if($hideCss){ echo 'auto-hgt'; } ?>">
		<!-- <a href="javascript:void(0);" class="hlp-rmv reg_comm_popupclose">×</a> -->
			<div class="head">			
				<a href="javascript:void(0);" class="reg_comm_popupclose">&times;</a>
				<div class="clear"></div>
			</div>
			<div class="reg-popup2">
	            <div class="tact"><i class="bl-logo"></i></div>
	            <div class="lyr-bx">
	            <?php foreach ($customHelpText as $key => $customText) {
					if($customText['heading'] && $customText['body']){
	            ?>
		            <div class="bg-txt">
		                <p class="wy-sgn"><?=$customText['heading'];?></p>
		                    <ul>
		                  	<?php foreach ($customText['body'] as $key => $bodyText) {?>
		                  		<li><?=$bodyText?></li>
		                  	<?php }  ?>		                        
		                    </ul>
		            </div>	      
		            <?php }?>
	            <?php }?>	            
		        </div>      	
   			</div>
   			<?php echo $registrationShikshaStats;?>
		</div>
	</div>


<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>

	<div class="Sgup-FrmSec">
		<form id="secondLayer_<?php echo $regFormId; ?>"  class="screenHolder" regFormId="<?php echo $regFormId; ?>"  data-enhance="false">
			<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
			<?php if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes'){ ?>
				<div><p class="field-title stl">To continue, create your account</p></div>
			<?php } ?>
			<?php $this->load->view('registration/fields/mobile/prefYear'); ?>
			<?php $this->load->view('registration/fields/mobile/accountDetails'); ?>
			<?php $this->load->view('registration/fields/mobile/personalInfo'); ?>
			<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
			<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
			<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $httpReferer ? $httpReferer : $_SERVER['HTTP_REFERER']; ?>' />
			<input type='hidden' id='isMR' name='isMR' value='YES' />
			<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?php echo empty($trackingKeyId)?905:$trackingKeyId; ?>' />
			<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
			<?php if(!empty($customFormData['customFields']['mmpFormId'])){ ?>
				<input type="hidden" id='mmpFormId_<?php echo $regFormId; ?>' name='mmpFormId' value='<?php echo $customFormData['customFields']['mmpFormId']; ?>' />
			<?php } ?>


			<a href="javascript:void(0);" class="reg-btn stp2 mt20" style="left:27px;" type="submit" regformid="<?php echo $regFormId; ?>" tabindex="2"><?php if(!empty($submitButtonText)){ echo $submitButtonText; }else{ echo 'Sign Up'; }?></a>

			<?php if(empty($customFormData['customFields']['isUserLoggedIn']) || $customFormData['customFields']['isUserLoggedIn'] != 'yes'){  ?>
			<div class="btn-rltv clear">
				<div class="Sgup-box">
					<p class="newSks lgn2" regformid="<?php echo $regFormId; ?>">Already have an account? Login here</p>
					<!-- <a href="javascript:void(0);" class="sgup-btn ">Login</a> -->
				</div>
			</div>
			<?php } ?>
			<p class="signup-msg">By clicking Sign Up, you agree to Shiksha's <br/><span href="javascript:void(0);" class="tnc gc">Terms of Use</span> and <span href="javascript:void(0);" class="privacyP gc">Privacy Policy</span></p>
		</form>
	</div>

</div>

 <?php $this->load->view('registration/common/jsObjectInitialization', true); ?>
 <script type="text/javascript">
 	<?php if(!empty($customFormData['customFields']['residenceCity']['value']) && $customFormData['customFields']['residenceCity']['value'] > 1){ ?>
		registrationForm.updateFieldLabel('residenceCityLocality', '<?php echo $regFormId; ?>');
	<?php } ?>

	<?php 
		if(!empty($customFormData['customFields']['isdCode']['value']) && $customFormData['customFields']['isdCode']['value'] != '91-2'){ ?>
			$('#isdCode_<?php echo $regFormId; ?>').change();
		<?php }
		if(!empty($customFormData['customFields']['residenceCityLocality']['value'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].preSelectResidentCity();
	<?php } ?>

	<?php if(!empty($customFormData['customFields']['residenceCityLocality']['value']) && $customFormData['customFields']['residenceCityLocality']['value'] > 1){ ?>
		
		var selectedResidentValue = $('#residenceCityLocality_<?php echo $regFormId; ?>').val();
		if(selectedResidentValue == '' || isNaN(parseInt(selectedResidentValue))){
			setTimeout(function(){
			$('#residenceCityLocality_block_<?php echo $regFormId; ?>').removeClass('filled ih disabled').show();
			}, 400);
		}
	<?php } ?>
 </script>
