<?php
$dataForModuleRun = array();
if($courseId>0){
	$dataForModuleRun['courseId']=$courseId;
}
if($scholarshipId>0){
	$dataForModuleRun['scholarshipId']=$scholarshipId;
}

if($universityId>0){
	$dataForModuleRun['universityId']=$universityId;
}

if($trackingPageKeyId>0){
    $dataForModuleRun['trackingPageKeyId'] = $trackingPageKeyId;
}

if($userDetails !== 'false'){
	$dataForModuleRun['userCity']  		  			= $userCity;
	$dataForModuleRun['passport'] 		  			= $passport;
	$dataForModuleRun['userPreferredDestinations']	= $userPreferredDestinations;
	$dataForModuleRun['preferredCourse']	 = $preferredCourse;
	$dataForModuleRun['preferredSpecialisation']	 = $preferredSpecialisation;
	$dataForModuleRun['educationDetails']	= $educationDetails;
	if(is_numeric($workExperience)){
		$dataForModuleRun['userWorkExperience'] = $workExperience;	
	}
	$dataForModuleRun['userShortlistedCourses'] = $userShortlistedCourseIds;
}

$dataForModuleRun['singleSignUpFormType']	= $singleSignUpFormType;
echo Modules::run('registration/Forms/LDB',"SASingleRegistration",'SASingleRegistrationForm', $dataForModuleRun);
?>
<div id="AbroadAjaxLoaderFull"></div>
<style>
div#main-wrapper{width: 100%}
div#main-wrapper > div#header{width: 988px;margin: 0 auto}
</style>