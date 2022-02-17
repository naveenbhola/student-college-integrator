<?php
$firstScreenButtonText = 'Next';
if(!empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] == 'yes'){
	$firstScreenButtonText = 'Continue';
} ?>

<div class="SgUp-lyr reg-pp" data-enhance="false">
	<?php if($customFormData['showFormWithoutHelpText'] != '1'){ ?>
	<div class="popup_layer_reg" id="reg_comm_popup" style="display:none">
		<div class="hlp-popup_reg nopadng <?php if($hideCss){ echo "auto-hgt"; } ?> " >
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
	<?php } ?>

	<div class="head">
		<p class="head-title fs16">Sign Up 
		<?php if($customFormData['showFormWithoutHelpText'] != '1'){ ?> 
		<span class="whySgnup" regformid="<?php echo $regFormId; ?>">Why should I sign up? <i class="irt-icon"></i></span>
		<?php } ?></p>
		<?php if(!$customFormData['callbackFunctionParams']['AMP_FLAG']){?>
		<a href="javascript:void(0);" class="lyr-cls pg1cross" data-rel="back">&times;</a>
		<?php } ?>
		<div class="clear"></div>
	</div>


	<div class="Sgup-FrmSec">

		<form id="firstLayer_<?php echo $regFormId; ?>" class="screenHolder" regFormId="<?php echo $regFormId; ?>">
			<?php $this->load->view('registration/fields/mobile/basicInterest'); ?>
			<a href="javascript:void(0);" class="reg-btn stp1" style="left:27px;" type="submit" regformid="<?php echo $regFormId; ?>" tabindex="2"><?php echo $firstScreenButtonText; ?></a>

			<?php if(empty($customFormData['customFields']['isUserLoggedIn']) || $customFormData['customFields']['isUserLoggedIn'] != 'yes'){  ?>
			<div class="btn-rltv clear">
				<div class="Sgup-box">
					<p class="newSks lgn1">Already have an account? Login here</p>
					<!-- <a href="javascript:void(0);" class="sgup-btn ">Login</a> -->
				</div>
			</div>
			<?php } ?>
		</form>
	</div>
	
</div>