<div class="Reglayer-bg"></div>
<div class="Reg-layer" <?php if(!empty($formCustomData['customFields'])){ echo "class='ih'"; } ?> >
	<?php 
	//LHS text
		if(!$showFormWithoutHelpText){ 
			$this->load->view('registration/registrationLHSText',array('customHelpText' => $customHelpText));
		}
	?> 	
	<div class="regRgt-col">
		<div class="regRgt-tabs" id="regRgtabs">
			<div id="navTab">
				<p class="Signup<?php if(!empty($formHeading)){ echo ' fs15 Slogin'; } ?>">
				<?php if(!empty($formHeading)){ echo "To ".$formHeading;
						if(empty($userId)){
							echo ", Sign Up or <a href='javascript:void(0);' class='loginScreen'>Login</a>";
						}else{ 
							echo ", enter the following details:";
						}  
					}else{ echo "Sign Up"; } ?></p>
			</div>
			<div>
				<?php if(empty($userId)){ ?>
					<!-- <p class="login">Already have an account? &nbsp;<a href="javascript:void(0);" class='loginScreen'>Login</a></p> -->
				<?php } ?>
				<p class="clear"></p>
			</div>
		</div>

		<div class="regTab-cont">
            <?php echo Modules::run('registration/RegistrationForms/LDB','nationalDefault','nationalExamResponse',$formCustomData); ?>
		</div>
	</div>	
	<a href="javascript:void(0);" class="regClose">&times;</a>
</div>
<script type="text/javascript">
	
	
</script>
