<?php $firstScreenButtonText = 'Next'; 
if(!empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] == 'yes'){ 
		$firstScreenButtonText = 'Continue';
	}
?>

<div class="SgUp-lyr" data-enhance="false">
	<div class="head">
		<p class="head-title fs16" id="title1_<?php echo $regFormId; ?>">Sign Up</p>
		<?php if(!$customFormData['callbackFunctionParams']['AMP_FLAG']){?>
		<a href="javascript:void(0);" class="lyr-cls pg1cross" data-rel="back">&times;</a>
		<?php } ?>
		<div class="clear"></div>
	</div>
	
	<?php if($customFormData['showFormWithoutHelpText'] != '1'){ ?>
		<div class="sgup-Txtbox ih">
		    <p><?php echo $customHelpText['heading'];?></p>
			<ul class="ml18 lsd">
			    <?php foreach($customHelpText['body'] as $key=>$bodyText){ ?>
					<li class="clg-brTxt"><?php echo $bodyText; ?></li>
				<?php } ?>
			 </ul>
		 </div>
	<?php } ?>

	<div class="Sgup-FrmSec mt10">

		<form id="firstLayer_<?php echo $regFormId; ?>" class="screenHolder" regFormId="<?php echo $regFormId; ?>">
			
		 <div><p class="field-title">To continue, enter details below:</p></div>
			<?php $this->load->view('registration/fields/mobile/response/clientCourse'); ?>
			<div id="mappedFields_<?php echo $regFormId; ?>">
			</div>
			<div id="dependentFields_<?php echo $regFormId; ?>">
			</div>
			<a href="javascript:void(0);" class="reg-btn stp1" style="left:27px;" type="submit" regformid="<?php echo $regFormId; ?>" tabindex="2"><?php echo $firstScreenButtonText; ?></a>
			
			<?php if(empty($customFormData['customFields']['isUserLoggedIn']) || $customFormData['customFields']['isUserLoggedIn'] != 'yes'){  ?>
				<div class="btn-rltv clear">
					<div class="Sgup-box">
						<p class="newSks lgn1 loginScreen" regformid="<?php echo $regFormId; ?>">Already have an account? Login here</p>
						<!-- <a href="javascript:void(0);" class="sgup-btn lgn1 loginScreen"  >Login</a> -->
					</div>
				</div>
			<?php } ?>
		</form>
	</div>
	
</div>