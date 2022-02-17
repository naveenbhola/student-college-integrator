<?php
	$footerComponents = array(
			    //'js'                => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
				//'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
				'hideHTML'			=> "true"
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<?php $this->load->view('registration/common/jsInitialization'); ?>
<script type="text/javascript">
	var rmcFormSpamControl = false;
	if(typeof(shikshaUserRegistrationForm['<?=$regFormId?>']) != 'undefined'){ 
		shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].triggerISDCodeOnchange('<?php echo INDIA_ISD_CODE; ?>');
	}
	shikshaUserRegistrationForm['<?=$regFormId?>'].getSAshikshaApplyFields({'ldbCourseId':'<?=$desiredCourse?>','context':'rmcPage'});
</script>