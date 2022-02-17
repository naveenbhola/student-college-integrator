<?php
	$dataForModuleRun = array();
	$dataForModuleRun['widget'] 					= 'rmcPage';
	$dataForModuleRun['responseSourcePage'] 		= 'rmcPage';
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
?>

<div id="rmcFormContainer" class="rate-my-chance-form" style="padding-bottom: 170px;">
	<?php echo Modules::run('registration/Forms/LDB',"SAapply",'rmcPage', $dataForModuleRun); ?>
	<a href="<?=$referrerPageURL?>" style=""> &lt; Go back to <?=htmlentities($referrerPageTitle)?></a>
</div>