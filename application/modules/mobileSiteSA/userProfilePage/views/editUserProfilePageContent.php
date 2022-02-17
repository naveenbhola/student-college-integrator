<?php
	$dataForModuleRun = array();
	$dataForModuleRun['preferredCourse'] 			= $profileUserData['desiredCourse'];
	//if()
	{
		$dataForModuleRun['preferredSpecialization']	= $profileUserData['abroadSpecialization'];
	}
	$dataForModuleRun['preferredSpecialization']	= $profileUserData['abroadSpecialization'];
	$dataForModuleRun['currentLevel']  		  		= $profileUserData['currentLevel'];
	if($profileUserData['currentLevel'] == "Bachelors"){
		$dataForModuleRun['educationDetails']['currentSchoolName'] = $profileUserData['CurrentSchoolName'] ;
		$dataForModuleRun['educationDetails']['currentClass'] = $profileUserData['CurrentClass'] ;
		$dataForModuleRun['educationDetails']['tenthBoard'] = $profileUserData['tenthBoard'] ;
		$dataForModuleRun['educationDetails']['tenthMarks'] = $profileUserData['tenthMarks'] ;
	}else{ // if($profileUserData['EduName'] == 'Graduation')
		$dataForModuleRun['educationDetails']['graduationStream'] = $profileUserData['graduationStream'];
		$dataForModuleRun['educationDetails']['graduationPercentage'] = $profileUserData['graduationPercentage'];
		$dataForModuleRun['userWorkExperience']			= $profileUserData['workExperience'];
	}
	$dataForModuleRun['userCity']  		  			= $profileUserData['userCity'];
	$dataForModuleRun['passport'] 		  			= $profileUserData['passport'];
	$dataForModuleRun['userPreferredDestinations']	= $profileUserData['userPreferredDestinations'];
	$dataForModuleRun['trackingPageKeyId'] = $trackingPageKeyId;
	//echo "ZIK";_p($dataForModuleRun);
?>
<section data-enhance="false">
<div class="edit_box" data-enhance="false">
	<!--input type="file" accept="image/*" capture="camera" />
	<canvas></canvas>
	<video class="camera_stream" width="320" height="240"></video>
	<img src="" class="photo">
	<img width="100%" src="https://images.shiksha.ws/public/images/sa-hp-bg.jpg" id="myImg" /-->
	<?php echo Modules::run('registration/Forms/LDB',"editProfileSAMobile",'editProfileSAMobile', $dataForModuleRun);
	?>

	<input type="hidden" value="<?php echo $userMobile; ?>" id="userMobile">
 <!--div class="edit_fix">
   Edit Profile
   <a href="#">&#x2716;</a>
 </div-->
</div>
<?php $this->load->view('registration/common/OTP/abroadMobileOTP'); ?>
</section>
