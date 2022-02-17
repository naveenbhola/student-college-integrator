<div id='registrationFormWrapper_<?php echo $regFormId;?>'>
	<form action="/registration/Registration/register" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post" regFormId="<?php echo $regFormId; ?>">
		
		
		<div id="firstScreen" class="ui-link" data-transition="slideup" data-rel="dialog" data-inline="true">
				<?php $this->load->view('registration/fields/mobile/basicInterest'); ?>
				<a href="javascript:void(0);" class="reg-btn stp1" style="left:27px;" type="submit" regformid="<?php echo $regFormId; ?>" tabindex="2">Next</a>
		</div>

		<div id="secondLayer" class="ui-link ih" data-transition="slideup" data-rel="dialog" data-inline="true">
			<?php if(empty($customFormFields['isUserLoggedIn']) && $customFormFields['isUserLoggedIn'] != 'yes'){ ?>
				<div><p class="field-title stl">To continue, create your account</p></div>
			<?php } ?>
			<?php $this->load->view('registration/fields/mobile/prefYear'); ?>
			<?php $this->load->view('registration/fields/mobile/accountDetails'); ?>
			<?php $this->load->view('registration/fields/mobile/personalInfo'); ?>
			<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
			<input type='hidden' id='context_<?php echo $regFormId; ?>' name='context' value='<?php echo $context; ?>' />
			<input type='hidden' id='registrationSource' name='registrationSource' value='<?php echo $customRegistrationSource ? $customRegistrationSource : $_SERVER['HTTP_REFERER']; ?>' />
			<input type='hidden' id='isMR' name='isMR' value='YES' />
			<input type="hidden" id="tracking_keyid_<?php echo $regFormId; ?>" name="tracking_keyid" value='<?php echo $trackingKeyId; ?>' />
			<input type="hidden" id='userVerification_<?php echo $regFormId; ?>' name='userVerification' value='no' />
		</div>

	</form>
	<?php $this->load->view('registration/common/jsObjectInitialization'); ?>

</div>

<a href="#secondLayer" id="slideSecond" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
<script>
	<?php if(!empty($customFormData['stream']['value'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].getFormByStream($j('#stream_<?php echo $regFormId; ?>'));
	<?php } ?>

</script>