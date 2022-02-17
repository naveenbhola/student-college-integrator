<?php if($validateuser == 'false') { 
	?> 
	<div class="content-tupple expg-widget">
		<p class="updates-p">
		<span class="tupple-title">
        <strong><?php echo $registrationWidgetHeading;?></strong></span>
		<input type="submit" class="orange-button pad2" value="Subscribe Now" onclick="var subscribeData = {'subscribeForExam' : '<?php echo $examPageData->getExamName(); ?>', 'examCategory' : '<?php echo $eventCalfilterArr['categoryName']; ?>','streamId':'<?php echo $eventCalfilterArr['streamId']; ?>','courseId':'<?php echo $eventCalfilterArr['courseId'];?>','educationTypeId':'<?php echo $eventCalfilterArr['educationTypeId']; ?>'};
			registrationForm.showRegistrationForm({'trackingKeyId' : '<?php echo $tracking_keyid ?>',
			 'callbackFunction' : 'examPageRegnCallback', 'callbackFunctionParams':subscribeData});"></p>
	</div>
<?php } ?>