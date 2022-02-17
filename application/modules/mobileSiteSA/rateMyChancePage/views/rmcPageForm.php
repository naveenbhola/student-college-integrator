<?php
	$dataForModuleRun = array();
	$dataForModuleRun['widget'] 					= 'mobileRmcPage';
	$dataForModuleRun['responseSourcePage'] 		= 'mobileRmcPage';
	$dataForModuleRun['destinationCountry'] 		= $brochureDataObj['destinationCountryId'];
	$dataForModuleRun['destinationCountryName'] 	= $brochureDataObj['destinationCountryName'];
	$dataForModuleRun['pageReferer'] 				= $brochureDataObj['sourcePage'];
	$dataForModuleRun['listingTypeForBrochure'] 	= 'course';
	$courseData 									= json_decode(base64_decode($brochureDataObj['courseData']),true);
	$dataForModuleRun['clientCourseId'] 			= reset(array_keys($courseData));
	$desiredCourse 									= reset($courseData);
	$dataForModuleRun['desiredCourse'] 				= $desiredCourse['desiredCourse'];
	$dataForModuleRun['isPaid'] 					= $desiredCourse['paid'];
	$dataForModuleRun['specialization'] 			= $desiredCourse['subcategory'];
	$userStartTimePrefWithExamsTaken 				= $brochureDataObj['userStartTimePrefWithExamsTaken'];
    $userCity		       							= $userStartTimePrefWithExamsTaken['userCity'];
    $passport		       							= $userStartTimePrefWithExamsTaken['passport'];
	$userPreferredDestinations 						= $userStartTimePrefWithExamsTaken['userPreferredDestinations'];
	$dataForModuleRun['userCity']  		  			= $userCity ;
    $dataForModuleRun['passport'] 		  			= $passport;
    $dataForModuleRun['userPreferredDestinations']	= $userPreferredDestinations;
	$dataForModuleRun['forResponse']				= $forResponse;
?>

<div id="rmcFormContainer" data-enhance="false" class="rate-my-chance-form" style="padding-bottom: 10px;">
	<?php echo Modules::run('registration/Forms/LDB',"SAapply",'mobileRmcPage', $dataForModuleRun); ?>
</div>
<?php $this->load->view('registration/common/OTP/abroadMobileOTP'); ?>
<div style="display:none;" class="login-form">
    <?php $this->load->view("registration/loginStudyAbroadMobile",array('forResponse'=>true)); ?>
</div>