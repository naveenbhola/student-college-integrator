<div class="Reglayer-bg"></div>

<div class="Reg-layer <?php if(!empty($formCustomData['customFields']['stream']['value']) && $formCustomData['showOTPOnly'] != 'yes'){ echo ' ih'; } ?>">
	<?php 
	//LHS text
		if(!$showFormWithoutHelpText){ 
			$this->load->view('registration/registrationLHSText',array('customHelpText' => $customHelpText));
		}
	?> 	
	<div class="regRgt-col">
		<div class="regRgt-tabs" id="regRgtabs">
			<div id="navTab">
				<p class="Signup">Sign Up</p>
			</div>
			<div>
				<p class="login">Already have an account? &nbsp;<a href="javascript:void(0);" class='loginScreen'>Login</a></p>
				<p class="clear"></p>
			</div>
		</div>

		<div class="regTab-cont">
            <?php echo Modules::run('registration/RegistrationForms/LDB','nationalDefault','signUp',$formCustomData); ?>
		</div>
	</div>	
	<a href="javascript:void(0);" class="regClose">&times;</a>
</div>
