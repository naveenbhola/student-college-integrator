<?php 
	$defaultFieldStates = json_encode($registrationHelper->getDefaultFieldStates()); 
	$customValidations = json_encode($registrationHelper->getCustomValidations());
?>

<script>

	userRegistrationRequest['<?php echo $regFormId; ?>'] = new UserRegistrationRequest('<?php echo $regFormId; ?>');
	userRegistrationRequest['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo $customValidations; ?>);
	userRegistrationRequest['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($fields)); ?>);
	
	<?php if(!empty($customFormData['customFields'])){ ?>
	userRegistrationRequest['<?php echo $regFormId; ?>'].customFieldValues = '<?php echo json_encode($customFormData['customFields']); ?>';
	userRegistrationRequest['<?php echo $regFormId; ?>'].customFieldValues = JSON.parse(userRegistrationRequest['<?php echo $regFormId; ?>'].customFieldValues);
	<?php } ?>

	<?php if(!empty($customFormData['customFieldValueSource'])){ ?>
	userRegistrationRequest['<?php echo $regFormId; ?>'].customFieldValueSource = '<?php echo json_encode($customFormData['customFieldValueSource']); ?>';
	userRegistrationRequest['<?php echo $regFormId; ?>'].customFieldValueSource = JSON.parse(userRegistrationRequest['<?php echo $regFormId; ?>'].customFieldValueSource);
	<?php } ?>

	<?php if(!empty($customFormData['callbackFunction'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].callbackFunction = '<?php echo $customFormData['callbackFunction']; ?>';
	<?php } ?>

	<?php if(!empty($customFormData['callbackFunctionParams'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].callbackFunctionParams = '<?php echo json_encode($customFormData['callbackFunctionParams']); ?>';
	<?php } ?>

	<?php if(!empty($customFormData['registrationIdentifier'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].registrationIdentifier = '<?php echo $customFormData['registrationIdentifier']; ?>';
	<?php } ?>

	<?php if(!empty($customFormData['customFields']['isUserLoggedIn']) && $customFormData['customFields']['isUserLoggedIn'] == 'yes'){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].skipSecondScreen = true;
	<?php } ?>
	
	<?php if(!empty($customFormData['clientCourseId'])){ ?>
		userRegistrationRequest['<?php echo $regFormId; ?>'].clientCourseId = '<?php echo $customFormData["clientCourseId"]; ?>';
		userRegistrationRequest['<?php echo $regFormId; ?>'].listingType = '<?php echo $customFormData["listingType"]; ?>';
		userRegistrationRequest['<?php echo $regFormId; ?>'].actionType = '<?php echo $customFormData["customFields"]["actionType"]; ?>';
	<?php } ?>

	if(typeof(blacklistWords) == 'undefined'){
		var blacklistWords = ["advisor", "expert", "Expert", "Advisor", "18002003922"];
	}else if(blacklistWords.length < 1){
		blacklistWords = ["advisor", "expert", "Expert", "Advisor", "18002003922"];
	}
</script>
